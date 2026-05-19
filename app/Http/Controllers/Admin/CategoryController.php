<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $categories = \App\Models\Category::when($search, function ($query, $search) {
                return $query->where('category_name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
            'status' => 'required|in:active,inactive',
        ]);

        \App\Models\Category::create([
            'category_name' => $request->category_name,
            'category_slug' => \Illuminate\Support\Str::slug($request->category_name),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function show(string $id)
    {
        // Not implemented
    }

    public function edit(\App\Models\Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, \App\Models\Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $category->id,
            'status' => 'required|in:active,inactive',
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'category_slug' => \Illuminate\Support\Str::slug($request->category_name),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(\App\Models\Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function toggleStatus(\App\Models\Category $category)
    {
        $category->status = $category->status === 'active' ? 'inactive' : 'active';
        $category->save();
        
        return redirect()->route('admin.categories.index')->with('success', 'Category status updated successfully.');
    }
}
