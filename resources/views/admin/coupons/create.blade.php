@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">Create New Coupon</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="coupon_code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control text-uppercase @error('coupon_code') is-invalid @enderror" id="coupon_code" name="coupon_code" value="{{ old('coupon_code') }}" required autofocus>
                                    <button class="btn btn-outline-secondary" type="button" onclick="generateCouponCode()">Auto Generate</button>
                                    @error('coupon_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Customers will enter this code at checkout.</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h6 class="text-muted mb-3 border-bottom pb-2">Discount Rules</h6>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="discount_type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type" required onchange="toggleMaxDiscount()">
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                                </select>
                                @error('discount_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" class="form-control @error('discount_value') is-invalid @enderror" id="discount_value" name="discount_value" value="{{ old('discount_value') }}" required>
                                    @error('discount_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 mb-3" id="max_discount_container">
                                <label for="max_discount" class="form-label">Maximum Discount (₹) (Optional)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('max_discount') is-invalid @enderror" id="max_discount" name="max_discount" value="{{ old('max_discount') }}">
                                <small class="text-muted">Cap for percentage discounts.</small>
                                @error('max_discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h6 class="text-muted mb-3 border-bottom pb-2">Usage Restrictions</h6>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="min_purchase" class="form-label">Minimum Purchase (₹) (Optional)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('min_purchase') is-invalid @enderror" id="min_purchase" name="min_purchase" value="{{ old('min_purchase') }}">
                                @error('min_purchase')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="usage_limit" class="form-label">Total Usage Limit (Optional)</label>
                                <input type="number" min="1" class="form-control @error('usage_limit') is-invalid @enderror" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}">
                                <small class="text-muted">Total times this coupon can be used across all users.</small>
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="per_user_limit" class="form-label">Per User Limit (Optional)</label>
                                <input type="number" min="1" class="form-control @error('per_user_limit') is-invalid @enderror" id="per_user_limit" name="per_user_limit" value="{{ old('per_user_limit') }}">
                                <small class="text-muted">Times a single user can use this code.</small>
                                @error('per_user_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date (Optional)</label>
                                <input type="datetime-local" class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Coupon</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function generateCouponCode() {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        const length = 8;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        document.getElementById('coupon_code').value = result;
    }

    function toggleMaxDiscount() {
        const type = document.getElementById('discount_type').value;
        const maxDiscountContainer = document.getElementById('max_discount_container');
        if (type === 'fixed') {
            maxDiscountContainer.style.display = 'none';
            document.getElementById('max_discount').value = '';
        } else {
            maxDiscountContainer.style.display = 'block';
        }
    }

    // Run on load to set initial state
    document.addEventListener('DOMContentLoaded', function() {
        toggleMaxDiscount();
    });
</script>
@endsection
