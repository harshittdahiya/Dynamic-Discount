<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Offer;
use App\Models\Coupon;

class FrontendController extends Controller
{
    public function home()
    {
        $banners = Banner::active()->get();
        $featuredProducts = Product::with('category')->where('status', 'active')->orderBy('created_at', 'desc')->take(12)->get();

        // Filter in PHP to avoid Mongo date type mismatch issues for start/end_date.
        $offers = Offer::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(fn ($offer) => $this->isOfferActiveNow($offer))
            ->take(3)
            ->values();

        $coupons = Coupon::where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
            })
            ->orderBy('created_at', 'desc')->take(8)->get();

        return view('frontend.home', compact('banners', 'featuredProducts', 'offers', 'coupons'));
    }

    public function products(Request $request)
    {
        $query = Product::with('category')->where('status', 'active');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $categoryIds = Category::where('status', 'active')
                ->where('category_name', 'like', '%' . $search . '%')
                ->pluck('id')
                ->all();

            $query->where(function ($productQuery) use ($search, $categoryIds) {
                $productQuery->where('product_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');

                if (!empty($categoryIds)) {
                    $productQuery->orWhereIn('category_id', $categoryIds);
                }
            });
        }

        if ($request->has('category')) {
            $category = Category::where('category_slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = Category::where('status', 'active')->get();

        return view('frontend.products.index', compact('products', 'categories'));
    }

    public function productSuggestions(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        if ($search === '') {
            return response()->json(['items' => []]);
        }

        $items = Product::with('category')
            ->where('status', 'active')
            ->where(function ($query) use ($search) {
                $query->where('product_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->product_name,
                    'slug' => $product->product_slug,
                    'price' => number_format((float) $product->price, 2),
                    'category' => $product->category->category_name ?? 'Uncategorized',
                    'url' => route('frontend.products.show', $product->product_slug),
                    'image' => $product->image_url,
                ];
            });

        return response()->json(['items' => $items]);
    }

    public function productDetails($slug)
    {
        $product = Product::with('category')->where('product_slug', $slug)->where('status', 'active')->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    public function offers()
    {
        $filteredOffers = Offer::with(['product', 'category'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(fn ($offer) => $this->isOfferActiveNow($offer))
            ->values();

        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $pagedItems = $filteredOffers->slice(($page - 1) * $perPage, $perPage)->values();

        $offers = new LengthAwarePaginator(
            $pagedItems,
            $filteredOffers->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('frontend.offers', compact('offers'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    private function isOfferActiveNow(Offer $offer): bool
    {
        $now = now();

        try {
            $startDate = $offer->start_date ? Carbon::parse($offer->start_date) : null;
            $endDate = $offer->end_date ? Carbon::parse($offer->end_date) : null;
        } catch (\Throwable $e) {
            // If a stored date is malformed, fail open so admin can still see offers.
            return true;
        }

        if ($startDate && $startDate->gt($now)) {
            return false;
        }

        if ($endDate && $endDate->lt($now)) {
            return false;
        }

        return true;
    }
}
