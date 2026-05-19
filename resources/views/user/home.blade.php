@extends('layouts.user')

@section('content')
<div class="container">
    @if(isset($banners) && $banners->count() > 0)
        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <div id="bannerCarousel" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        @foreach($banners as $index => $banner)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $banner->banner_image) }}" class="d-block w-100" alt="{{ $banner->title ?? 'Banner' }}" style="height: 300px; object-fit: cover;">
                                @if($banner->title || $banner->subtitle)
                                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded px-3 py-2" style="bottom: 20px;">
                                        @if($banner->title)
                                            <h5 class="mb-1 text-white">{{ $banner->title }}</h5>
                                        @endif
                                        @if($banner->subtitle)
                                            <p class="mb-0 text-white">{{ $banner->subtitle }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if($banners->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h4 class="mb-0 text-primary">{{ __('Welcome to Your Dashboard') }}</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="lead">Hello <strong>{{ Auth::user()->name }}</strong>, here you can manage your discount coupons and view available offers.</p>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-primary">
                                <div class="card-body">
                                    <h5 class="card-title text-primary"><i class="bi bi-tag-fill me-2"></i>Available Offers</h5>
                                    <p class="card-text">Browse through our latest discount coupons tailored just for you.</p>
                                    <a href="#" class="btn btn-outline-primary">View Offers</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-success">
                                <div class="card-body">
                                    <h5 class="card-title text-success"><i class="bi bi-wallet2 me-2"></i>My Coupons</h5>
                                    <p class="card-text">View the coupons you've saved and track your savings.</p>
                                    <a href="#" class="btn btn-outline-success">View My Coupons</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
