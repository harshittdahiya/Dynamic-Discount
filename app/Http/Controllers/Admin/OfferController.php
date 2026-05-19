<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Offer;
use App\Models\Product;
use App\Models\Category;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $query = Offer::with(['product', 'category']);
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('offer_title', 'like', "%{$search}%");
        }

        $offers = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('admin.offers.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'offer_title' => 'required|string|max:255',
            'offer_type' => 'required|in:festival,product,category,first_order,flash_sale,seasonal',
            'discount_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'product_id' => 'required_if:offer_type,product|nullable|exists:products,id',
            'category_id' => 'required_if:offer_type,category|nullable|exists:categories,id',
        ]);

        $data = $request->all();
        if ($request->offer_type !== 'product') {
            $data['product_id'] = null;
        }
        if ($request->offer_type !== 'category') {
            $data['category_id'] = null;
        }

        Offer::create($data);

        return redirect()->route('admin.offers.index')->with('success', 'Offer created successfully.');
    }

    public function show(string $id)
    {
        // Not implemented
    }

    public function edit(Offer $offer)
    {
        $products = Product::all();
        $categories = Category::all();
        return view('admin.offers.edit', compact('offer', 'products', 'categories'));
    }

    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'offer_title' => 'required|string|max:255',
            'offer_type' => 'required|in:festival,product,category,first_order,flash_sale,seasonal',
            'discount_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'product_id' => 'required_if:offer_type,product|nullable|exists:products,id',
            'category_id' => 'required_if:offer_type,category|nullable|exists:categories,id',
        ]);

        $data = $request->all();
        if ($request->offer_type !== 'product') {
            $data['product_id'] = null;
        }
        if ($request->offer_type !== 'category') {
            $data['category_id'] = null;
        }

        $offer->update($data);

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        
        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted successfully.');
    }

    public function toggleStatus(Offer $offer)
    {
        $offer->status = $offer->status === 'active' ? 'inactive' : 'active';
        $offer->save();
        
        return redirect()->route('admin.offers.index')->with('success', 'Offer status updated successfully.');
    }
}
