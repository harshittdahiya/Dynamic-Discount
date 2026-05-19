@extends('layouts.frontend')

@section('content')
<div class="container mb-5">
    <div class="row">
        <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top categories-sidebar">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Categories</h5>
                    <div class="list-group list-group-flush border-0">
                        <a href="{{ route('frontend.products.index') }}" class="list-group-item list-group-item-action border-0 px-0 {{ !request('category') ? 'fw-bold text-primary' : 'text-muted' }}">
                            All Products
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('frontend.products.index', ['category' => $category->category_slug]) }}" class="list-group-item list-group-item-action border-0 px-0 {{ request('category') == $category->category_slug ? 'fw-bold text-primary' : 'text-muted' }}">
                                {{ $category->category_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    @if(request('search'))
                        Search results for "{{ request('search') }}"
                    @elseif(request('category'))
                        {{ \App\Models\Category::where('category_slug', request('category'))->first()->category_name ?? 'Products' }}
                    @else
                        All Products
                    @endif
                </h3>
                <span class="text-muted">{{ $products->total() }} results</span>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 border-0 shadow-sm product-card transition-all rounded-3">
                            <div class="product-image bg-light d-flex align-items-center justify-content-center" style="height:250px; overflow:hidden;">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->product_name }}" style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center text-muted w-100 h-100">
                                        <i class="bi bi-image" style="font-size: 2.5rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <span class="text-muted small mb-1">{{ $product->category->category_name ?? 'Uncategorized' }}</span>
                                <h5 class="card-title fw-bold mb-1"><a href="{{ route('frontend.products.show', $product->product_slug) }}" class="text-dark text-decoration-none d-block product-title">{{ $product->product_name }}</a></h5>
                                <div class="mt-auto pt-3 d-flex justify-content-between align-items-center">
                                    <span class="fs-5 fw-bold text-primary">₹{{ number_format($product->price, 2) }}</span>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-circle shadow-sm" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5 bg-white rounded-4 shadow-sm border-0">
                            <i class="bi bi-box-seam text-muted mb-3 d-block" style="font-size: 4rem;"></i>
                            <h4 class="fw-bold text-dark">No Products Found</h4>
                            <p class="text-muted">Try removing your filters or check back later.</p>
                            <a href="{{ route('frontend.products.index') }}" class="btn btn-primary mt-2">Clear Filters</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<style>
        /* Ensure navbar stays above sticky sidebar */
        .navbar { z-index: 1030; }

        /* Categories sidebar sticky offset and stacking */
        .categories-sidebar.sticky-top { top: 80px; z-index: 1020; }

    .transition-all { transition: all 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .product-title { display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .product-image img { display:block; }
    /* Fix categories card sticky behavior on small screens and list appearance */
    @media (max-width: 991px) {
        .sticky-top { position: static !important; top: auto !important; }
    }

    .list-group .list-group-item {
        padding-left: 0;
        padding-right: 0;
        border: none;
        padding-top: .35rem;
        padding-bottom: .35rem;
    }

    /* Ensure pagination buttons appear correctly */
    .pagination { margin: 0; }
</style>
@endsection
