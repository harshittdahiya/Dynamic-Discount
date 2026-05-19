<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = \App\Models\Product::with('category')
            ->when($search, function ($query, $search) {
                return $query->where('product_name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('admin.products.index', compact('products', 'search'));
    }

    public function create()
    {
        $categories = \App\Models\Category::where('status', 'active')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255|unique:products,product_name',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
        }

        \App\Models\Product::create([
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'product_slug' => \Illuminate\Support\Str::slug($request->product_name),
            'product_image' => $imagePath,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(string $id)
    {
        // Not implemented
    }

    public function edit(\App\Models\Product $product)
    {
        $categories = \App\Models\Category::where('status', 'active')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, \App\Models\Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255|unique:products,product_name,' . $product->id,
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $product->product_image;
        if ($request->hasFile('product_image')) {
            $this->deleteStoredProductImage($imagePath);
            $imagePath = $request->file('product_image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'product_slug' => \Illuminate\Support\Str::slug($request->product_name),
            'product_image' => $imagePath,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(\App\Models\Product $product)
    {
        $this->deleteStoredProductImage($product->product_image);

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
    
    public function toggleStatus(\App\Models\Product $product)
    {
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();
        
        return redirect()->route('admin.products.index')->with('success', 'Product status updated successfully.');
    }

    private function deleteStoredProductImage(?string $imagePath): void
    {
        if (! $imagePath || Str::startsWith($imagePath, ['http://', 'https://', '//'])) {
            return;
        }

        Storage::disk('public')->delete($imagePath);
    }
}
