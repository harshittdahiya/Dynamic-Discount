@extends('layouts.frontend')

@section('content')
<div class="container mb-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.products.index') }}" class="text-decoration-none text-muted">Products</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('frontend.products.index', ['category' => $product->category->category_slug]) }}" class="text-decoration-none text-muted">{{ $product->category->category_name }}</a></li>
            @endif
            <li class="breadcrumb-item active text-dark" aria-current="page">{{ $product->product_name }}</li>
        </ol>
    </nav>

    <!-- Product Details -->
    <div class="row bg-white rounded-4 shadow-sm p-4 p-md-5 mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <div style="height: 500px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 1.5rem; overflow: hidden;">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" class="img-fluid rounded-4" alt="{{ $product->product_name }}" style="object-fit: contain; max-height: 100%; max-width: 100%; padding: 1rem;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center text-muted w-100 h-100">
                        <i class="bi bi-image" style="font-size: 5rem;"></i>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-6 ps-md-5 d-flex flex-column">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-2 align-self-start">{{ $product->category->category_name ?? 'Uncategorized' }}</span>
            <h2 class="fw-bold mb-3">{{ $product->product_name }}</h2>
            
            <div class="d-flex align-items-center mb-4">
                <h3 class="fw-bold text-primary mb-0 me-3">₹{{ number_format($product->price, 2) }}</h3>
                @if($product->stock > 0)
                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i> In Stock ({{ $product->stock }})</span>
                @else
                    <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle me-1"></i> Out of Stock</span>
                @endif
            </div>

            <p class="text-muted mb-4 lh-lg">{{ $product->description ?? 'No description available for this product.' }}</p>

            <div class="mt-auto pt-4 border-top">
                <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="input-group me-3" style="width: 130px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('qty').stepDown()">-</button>
                        <input type="number" class="form-control text-center bg-white" id="qty" name="quantity" value="1" min="1" max="{{ $product->stock }}" {{ $product->stock < 1 ? 'readonly' : '' }}>
                        <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('qty').stepUp()">+</button>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg px-4 fw-bold flex-grow-1" {{ $product->stock < 1 ? 'disabled' : '' }}>
                        <i class="bi bi-cart-plus me-2"></i> Add to Cart
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="row">
            <div class="col-12 mb-4">
                <h4 class="fw-bold">You might also like</h4>
            </div>
            @foreach($relatedProducts as $related)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm product-card transition-all rounded-3">
                        <div style="height: 250px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            @if($related->image_url)
                                <img src="{{ $related->image_url }}" class="card-img-top" alt="{{ $related->product_name }}" style="object-fit: contain; max-height: 100%; max-width: 100%; padding: 0.5rem;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center text-muted w-100 h-100">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <span class="text-muted small mb-1">{{ $related->category->category_name ?? 'Uncategorized' }}</span>
                            <h5 class="card-title fw-bold text-truncate"><a href="{{ route('frontend.products.show', $related->product_slug) }}" class="text-dark text-decoration-none">{{ $related->product_name }}</a></h5>
                            <div class="mt-auto pt-3 d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-primary">₹{{ number_format($related->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endsection
