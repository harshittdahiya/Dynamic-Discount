<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->load('items.product', 'coupon');
        return view('user.orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_country' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals securely
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
                
                // Check stock
                $product = Product::find($item['id']);
                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception("Product {$item['name']} is out of stock or requested quantity exceeds available stock.");
                }
            }

            $discount = 0;
            $couponId = null;
            $couponSession = Session::get('coupon');
            $activeCoupon = null;

            if ($couponSession) {
                $activeCoupon = Coupon::where('coupon_code', strtoupper($couponSession['code']))->first();
                if ($activeCoupon && $activeCoupon->isValid()) {
                    if ($activeCoupon->discount_type == 'percentage') {
                        $discount = ($subtotal * $activeCoupon->discount_value) / 100;
                        if ($activeCoupon->max_discount && $discount > $activeCoupon->max_discount) {
                            $discount = $activeCoupon->max_discount;
                        }
                    } else {
                        $discount = $activeCoupon->discount_value;
                    }
                    $couponId = $activeCoupon->id;
                }
            }

            $finalAmount = max(0, $subtotal - $discount);

            // Create Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $subtotal,
                'discount_amount' => $discount,
                'final_amount' => $finalAmount,
                'coupon_id' => $couponId,
                'order_status' => 'pending',
                'billing_name' => $request->billing_name,
                'billing_address' => $request->billing_address,
                'billing_country' => $request->billing_country,
                'billing_state' => $request->billing_state,
                'billing_zip' => $request->billing_zip,
            ]);

            // Create Order Items and decrease stock
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }

            // Register Coupon Usage
            if ($activeCoupon) {
                $activeCoupon->registerUsage(Auth::id(), $order->id);
            }

            DB::commit();

            Session::forget('cart');
            Session::forget('coupon');

            return redirect()->route('orders.success')->with('success', 'Your order has been placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', $e->getMessage());
        }
    }

    public function success()
    {
        if (!session('success')) {
            return redirect()->route('frontend.home');
        }
        return view('frontend.checkout.success');
    }
}
