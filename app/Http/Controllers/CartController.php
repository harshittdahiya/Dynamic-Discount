<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function viewCart()
    {
        $cart = Session::get('cart', []);
        $cart = $this->refreshCartProductDetails($cart);
        Session::put('cart', $cart);

        $coupon = Session::get('coupon', null);
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        if ($coupon) {
            if ($coupon['type'] == 'percentage') {
                $discount = ($subtotal * $coupon['value']) / 100;
                if (isset($coupon['max_discount']) && $discount > $coupon['max_discount']) {
                    $discount = $coupon['max_discount'];
                }
            } else {
                $discount = $coupon['value'];
            }
            
            if ($subtotal < ($coupon['min_purchase'] ?? 0)) {
                $discount = 0;
                Session::forget('coupon');
                session()->flash('error', 'Coupon removed. Minimum purchase requirement not met.');
            }
        }

        $total = max(0, $subtotal - $discount);
        $couponStatuses = $this->getCouponStatuses($subtotal, $coupon);

        return view('frontend.cart.index', compact('cart', 'subtotal', 'discount', 'total', 'coupon', 'couponStatuses'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->product_name,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'image' => $product->image_url,
                'id' => $product->id
            ];
        }

        Session::put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function removeFromCart(Request $request)
    {
        if ($request->id) {
            $cart = Session::get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                Session::put('cart', $cart);
            }
            return redirect()->route('cart.index')->with('success', 'Product removed successfully');
        }
    }

    public function applyCoupon(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to apply a coupon.');
        }

        $request->validate(['coupon_code' => 'required|string']);

        $code = strtoupper($request->coupon_code);
        $coupon = Coupon::where('coupon_code', $code)->first();

        $logFailedAttempt = function($reason) use ($code, $request) {
            \App\Models\FailedCouponAttempt::create([
                'user_id' => Auth::id(),
                'attempted_code' => $code,
                'ip_address' => $request->ip(),
                'reason' => $reason
            ]);
            return redirect()->route('cart.index')->with('error', $reason);
        };

        if (!$coupon || !$coupon->isValid()) {
            return $logFailedAttempt('Invalid or expired coupon code.');
        }

        // Global usage limit check
        if ($coupon->usage_limit && $coupon->usages()->count() >= $coupon->usage_limit) {
            return $logFailedAttempt('This coupon has reached its maximum usage limit.');
        }

        // Per user usage limit check
        if ($coupon->per_user_limit) {
            if (!Auth::check()) {
                return redirect()->route('cart.index')->with('error', 'Please log in to apply this coupon.');
            }

            $userUsageCount = $coupon->usages()->where('user_id', Auth::id())->count();
            if ($userUsageCount >= $coupon->per_user_limit) {
                return $logFailedAttempt('You have reached your usage limit for this coupon.');
            }
        }

        // Subtotal calculation to check min_purchase
        $cart = Session::get('cart', []);
        $subtotal = array_reduce($cart, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        if ($coupon->min_purchase && $subtotal < $coupon->min_purchase) {
            return $logFailedAttempt('Minimum purchase of ₹' . number_format($coupon->min_purchase, 2) . ' required.');
        }

        Session::put('coupon', [
            'code' => $coupon->coupon_code,
            'type' => $coupon->discount_type,
            'value' => $coupon->discount_value,
            'max_discount' => $coupon->max_discount,
            'min_purchase' => $coupon->min_purchase
        ]);

        return redirect()->route('cart.index')->with('success', 'Coupon applied successfully!');
    }

    public function removeCoupon()
    {
        Session::forget('coupon');
        return redirect()->route('cart.index')->with('success', 'Coupon removed.');
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.products.index')->with('error', 'Your cart is empty.');
        }

        $cart = $this->refreshCartProductDetails($cart);
        Session::put('cart', $cart);

        // Same calculation as viewCart for summary
        $coupon = Session::get('coupon', null);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        if ($coupon) {
            if ($coupon['type'] == 'percentage') {
                $discount = ($subtotal * $coupon['value']) / 100;
                if (isset($coupon['max_discount']) && $discount > $coupon['max_discount']) {
                    $discount = $coupon['max_discount'];
                }
            } else {
                $discount = $coupon['value'];
            }
        }
        $total = max(0, $subtotal - $discount);

        return view('frontend.checkout.index', compact('cart', 'subtotal', 'discount', 'total'));
    }

    private function getCouponStatuses(float|int $subtotal, ?array $appliedCoupon = null)
    {
        $coupons = Coupon::orderBy('coupon_code')->get();

        return $coupons->map(function ($coupon) use ($subtotal, $appliedCoupon) {
            $canApply = true;
            $reason = 'Eligible';

            if (!Auth::check()) {
                $canApply = false;
                $reason = 'Login required';
            } elseif ($appliedCoupon && ($appliedCoupon['code'] ?? null) === $coupon->coupon_code) {
                $canApply = false;
                $reason = 'Already applied';
            } elseif ($coupon->status !== 'active') {
                $canApply = false;
                $reason = 'Inactive coupon';
            } elseif ($coupon->expiry_date && $coupon->expiry_date->isPast()) {
                $canApply = false;
                $reason = 'Expired';
            } elseif ($coupon->usage_limit && $coupon->usages()->count() >= $coupon->usage_limit) {
                $canApply = false;
                $reason = 'Usage limit reached';
            } elseif ($coupon->per_user_limit) {
                $userUsageCount = $coupon->usages()->where('user_id', Auth::id())->count();
                if ($userUsageCount >= $coupon->per_user_limit) {
                    $canApply = false;
                    $reason = 'Per-user usage limit reached';
                }
            }

            if ($canApply && $coupon->min_purchase && $subtotal < $coupon->min_purchase) {
                $canApply = false;
                $reason = 'Minimum purchase ₹' . number_format($coupon->min_purchase, 2) . ' required';
            }

            return [
                'coupon' => $coupon,
                'can_apply' => $canApply,
                'reason' => $reason,
            ];
        })->values();
    }

    private function refreshCartProductDetails(array $cart): array
    {
        foreach ($cart as $key => $item) {
            if (empty($item['id'])) {
                continue;
            }

            $product = Product::find($item['id']);
            if (! $product) {
                continue;
            }

            $cart[$key]['name'] = $product->product_name;
            $cart[$key]['image'] = $product->image_url;
        }

        return $cart;
    }
}
