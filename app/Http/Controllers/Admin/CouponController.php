<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Coupon;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('coupon_code', 'like', "%{$search}%");
        }

        $coupons = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50|unique:coupons',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0' . ($request->discount_type === 'percentage' ? '|max:100' : ''),
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        Coupon::create($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function show(string $id)
    {
        // Not implemented
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'coupon_code' => ['required', 'string', 'max:50', Rule::unique('coupons')->ignore($coupon->id)],
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0' . ($request->discount_type === 'percentage' ? '|max:100' : ''),
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $coupon->update($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->status = $coupon->status === 'active' ? 'inactive' : 'active';
        $coupon->save();
        
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon status updated successfully.');
    }
}
