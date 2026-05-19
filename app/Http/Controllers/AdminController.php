<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\CouponUsage;
use App\Models\Coupon;
use App\Models\FailedCouponAttempt;
use App\Models\Offer;
use App\Models\OrderItem;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Top KPIs
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('order_status', '!=', 'cancelled')->sum('final_amount');
        $totalCouponsUsed = CouponUsage::count();

        // Best Selling Product (MongoDB-safe aggregation)
        $bestProductId = OrderItem::all(['product_id', 'quantity'])
            ->groupBy('product_id')
            ->map(fn ($items) => $items->sum('quantity'))
            ->sortDesc()
            ->keys()
            ->first();
        $bestSellingProduct = $bestProductId ? Product::find($bestProductId) : null;

        // Most Used Coupon (MongoDB-safe aggregation)
        $mostUsedCouponId = CouponUsage::all(['coupon_id'])
            ->groupBy('coupon_id')
            ->map(fn ($items) => $items->count())
            ->sortDesc()
            ->keys()
            ->first();
        $mostUsedCoupon = $mostUsedCouponId ? Coupon::find($mostUsedCouponId) : null;

        // Failed Coupon Attempts
        $failedAttemptsCount = FailedCouponAttempt::count();
        $recentFailedAttempts = FailedCouponAttempt::orderBy('created_at', 'desc')->take(5)->get();

        // Monthly Sales Report (Current Year, MongoDB-safe)
        $currentYear = date('Y');
        $salesData = array_fill(1, 12, 0);
        $yearOrders = Order::where('order_status', '!=', 'cancelled')->get(['created_at', 'final_amount']);
        foreach ($yearOrders as $order) {
            if (! $order->created_at) {
                continue;
            }

            if ((int) $order->created_at->format('Y') !== (int) $currentYear) {
                continue;
            }

            $month = (int) $order->created_at->format('n');
            $salesData[$month] += (float) ($order->final_amount ?? 0);
        }

        // Offer Performance Report (Simplified for dashboard)
        $offers = Offer::with(['product', 'category'])->where('status', 'active')->get();
        $offerPerformance = [];
        
        foreach ($offers as $offer) {
            $soldQty = 0;
            $revenueGenerated = 0;

            if ($offer->offer_type === 'product' && $offer->product_id) {
                $items = OrderItem::where('product_id', $offer->product_id)
                    ->whereBetween('created_at', [$offer->start_date ?? now()->subYears(10), $offer->end_date ?? now()->addYears(10)])
                    ->get(['quantity', 'price']);
                $soldQty = (int) $items->sum('quantity');
                $revenueGenerated = (float) $items->reduce(
                    fn ($carry, $item) => $carry + ((float) ($item->quantity ?? 0) * (float) ($item->price ?? 0)),
                    0
                );
            } elseif ($offer->offer_type === 'category' && $offer->category_id) {
                // Get all product IDs in this category
                $productIds = Product::where('category_id', $offer->category_id)->pluck('id');
                $items = OrderItem::whereIn('product_id', $productIds)
                    ->whereBetween('created_at', [$offer->start_date ?? now()->subYears(10), $offer->end_date ?? now()->addYears(10)])
                    ->get(['quantity', 'price']);
                $soldQty = (int) $items->sum('quantity');
                $revenueGenerated = (float) $items->reduce(
                    fn ($carry, $item) => $carry + ((float) ($item->quantity ?? 0) * (float) ($item->price ?? 0)),
                    0
                );
            }

            $offerPerformance[] = [
                'title' => $offer->offer_title,
                'type' => $offer->offer_type,
                'sold_qty' => $soldQty,
                'revenue' => $revenueGenerated
            ];
        }

        // Sort by revenue descending
        usort($offerPerformance, function($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });

        // Top 5 offers
        $offerPerformance = array_slice($offerPerformance, 0, 5);

        return view('admin.dashboard', compact(
            'totalProducts', 'totalCategories', 'totalOrders', 'totalRevenue',
            'totalCouponsUsed', 'bestSellingProduct', 'mostUsedCoupon',
            'failedAttemptsCount', 'recentFailedAttempts', 'salesData', 'currentYear',
            'offerPerformance'
        ));
    }
}
