@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">Edit Offer</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="offer_title" class="form-label">Offer Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('offer_title') is-invalid @enderror" id="offer_title" name="offer_title" value="{{ old('offer_title', $offer->offer_title) }}" required autofocus>
                                @error('offer_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $offer->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $offer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="offer_type" class="form-label">Offer Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('offer_type') is-invalid @enderror" id="offer_type" name="offer_type" required onchange="toggleTargetFields()">
                                    <option value="" disabled>Select Offer Type...</option>
                                    <option value="festival" {{ old('offer_type', $offer->offer_type) == 'festival' ? 'selected' : '' }}>Festival Offer</option>
                                    <option value="product" {{ old('offer_type', $offer->offer_type) == 'product' ? 'selected' : '' }}>Product-wise Offer</option>
                                    <option value="category" {{ old('offer_type', $offer->offer_type) == 'category' ? 'selected' : '' }}>Category-wise Offer</option>
                                    <option value="first_order" {{ old('offer_type', $offer->offer_type) == 'first_order' ? 'selected' : '' }}>First Order Offer</option>
                                    <option value="flash_sale" {{ old('offer_type', $offer->offer_type) == 'flash_sale' ? 'selected' : '' }}>Flash Sale</option>
                                    <option value="seasonal" {{ old('offer_type', $offer->offer_type) == 'seasonal' ? 'selected' : '' }}>Seasonal Sale</option>
                                </select>
                                @error('offer_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3" id="product_target_container" style="display: none;">
                                <label for="product_id" class="form-label">Target Product <span class="text-danger">*</span></label>
                                <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id">
                                    <option value="" disabled selected>Select a Product...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id', $offer->product_id) == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3" id="category_target_container" style="display: none;">
                                <label for="category_id" class="form-label">Target Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                    <option value="" disabled selected>Select a Category...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $offer->category_id) == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="discount_value" class="form-label">Discount Percentage (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control @error('discount_value') is-invalid @enderror" id="discount_value" name="discount_value" value="{{ old('discount_value', $offer->discount_value) }}" required>
                                @error('discount_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="start_date" class="form-label">Start Date (Optional)</label>
                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $offer->start_date ? $offer->start_date->format('Y-m-d\TH:i') : '') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="end_date" class="form-label">End Date (Optional)</label>
                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $offer->end_date ? $offer->end_date->format('Y-m-d\TH:i') : '') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('admin.offers.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleTargetFields() {
        const type = document.getElementById('offer_type').value;
        const productContainer = document.getElementById('product_target_container');
        const categoryContainer = document.getElementById('category_target_container');
        
        productContainer.style.display = 'none';
        categoryContainer.style.display = 'none';

        if (type === 'product') {
            productContainer.style.display = 'block';
        } else if (type === 'category') {
            categoryContainer.style.display = 'block';
        }
    }

    // Run on load to set initial state
    document.addEventListener('DOMContentLoaded', function() {
        toggleTargetFields();
    });
</script>
@endsection
