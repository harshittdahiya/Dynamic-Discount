@extends('layouts.frontend')

@section('content')
<div class="container mb-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold">Active Offers & Promotions <i class="bi bi-stars text-warning"></i></h2>
            <p class="text-muted">Don't miss out on these limited-time deals!</p>
        </div>
    </div>

    <div class="row">
        @forelse($offers as $offer)
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="row g-0 h-100">
                        <div class="col-4 bg-primary text-white d-flex flex-column align-items-center justify-content-center p-4 text-center">
                            <i class="bi bi-tag-fill mb-2" style="font-size: 2.5rem;"></i>
                            <h3 class="fw-bold mb-0">{{ number_format($offer->discount_value, 0) }}%</h3>
                            <span class="fs-5">OFF</span>
                        </div>
                        <div class="col-8">
                            <div class="card-body h-100 d-flex flex-column p-4">
                                <span class="badge bg-light text-primary mb-2 align-self-start text-capitalize">{{ str_replace('_', ' ', $offer->offer_type) }}</span>
                                <h4 class="card-title fw-bold mb-3">{{ $offer->offer_title }}</h4>
                                
                                @if($offer->offer_type === 'product' && $offer->product)
                                    <div class="d-flex align-items-center mb-2">
                                        @if($offer->product->image_url)
                                            <img src="{{ $offer->product->image_url }}" alt="{{ $offer->product->product_name }}" class="rounded-3 me-2 object-fit-cover" style="width: 42px; height: 42px;">
                                        @else
                                            <div class="bg-light rounded-3 me-2 d-flex align-items-center justify-content-center text-muted" style="width: 42px; height: 42px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                        <p class="text-muted mb-0"><i class="bi bi-box me-2"></i> On: <strong>{{ $offer->product->product_name }}</strong></p>
                                    </div>
                                    <a href="{{ route('frontend.products.show', $offer->product->product_slug) }}" class="btn btn-sm btn-outline-primary align-self-start mt-2">Shop Now</a>
                                @elseif($offer->offer_type === 'category' && $offer->category)
                                    <p class="text-muted mb-2"><i class="bi bi-tags me-2"></i> Category: <strong>{{ $offer->category->category_name }}</strong></p>
                                    <a href="{{ route('frontend.products.index', ['category' => $offer->category->category_slug]) }}" class="btn btn-sm btn-outline-primary align-self-start mt-2">Shop Category</a>
                                @else
                                    <p class="text-muted mb-2">Sitewide or Special Condition</p>
                                    <a href="{{ route('frontend.products.index') }}" class="btn btn-sm btn-outline-primary align-self-start mt-2">Shop All</a>
                                @endif

                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        @if($offer->end_date)
                                            <i class="bi bi-clock-history text-danger me-1"></i> Ends {{ $offer->end_date->format('M d, Y') }}
                                        @else
                                            <i class="bi bi-infinity text-success me-1"></i> Ongoing
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border-0">
                    <i class="bi bi-stars text-muted mb-3 d-block" style="font-size: 4rem;"></i>
                    <h4 class="fw-bold text-dark">No Active Offers</h4>
                    <p class="text-muted">We don't have any special promotions running at this exact moment. Check back soon!</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $offers->links() }}
    </div>
</div>
@endsection
