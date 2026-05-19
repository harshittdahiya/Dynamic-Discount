@extends('layouts.frontend')

@section('content')
@php
    $primaryBanner = $banners->first();
    $heroProducts = $featuredProducts->take(3);
    $spotlightProduct = $featuredProducts->first();
    $featuredGrid = $featuredProducts->take(8);
    $couponHighlights = $coupons->take(4);
@endphp

<div class="storefront-home">
    <section class="home-hero reveal">
        <div class="container">
            <div class="home-hero-grid">
                <div class="home-hero-copy">
                    <span class="eyebrow-label">
                        <i class="bi bi-stars"></i>
                        Dynamic discounts, curated daily
                    </span>
                    <h1>{{ $primaryBanner->title ?? 'Smarter Shopping Starts Here' }}</h1>
                    <p>
                        {{ $primaryBanner->subtitle ?? 'Discover premium products, active offers, and verified coupons designed to make every checkout feel intentional.' }}
                    </p>
                    <div class="home-hero-actions">
                        <a href="{{ route('frontend.products.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-bag-check me-2"></i>Shop Products
                        </a>
                        <a href="{{ route('frontend.offers') }}" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-lightning-charge me-2"></i>View Offers
                        </a>
                    </div>
                </div>

                <div class="home-hero-showcase" aria-label="Featured products">
                    @if($primaryBanner && $primaryBanner->banner_image)
                        <img class="hero-banner-image" src="{{ asset('storage/' . $primaryBanner->banner_image) }}" alt="{{ $primaryBanner->title }}">
                    @else
                        <div class="hero-product-stack">
                            @forelse($heroProducts as $index => $product)
                                <a href="{{ route('frontend.products.show', $product->product_slug) }}" class="hero-product-tile hero-product-tile-{{ $index + 1 }}">
                                    <div class="hero-product-image">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}">
                                        @else
                                            <i class="bi bi-box-seam"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span>{{ $product->category->category_name ?? 'Featured' }}</span>
                                        <strong>{{ $product->product_name }}</strong>
                                        <em>₹{{ number_format($product->price, 2) }}</em>
                                    </div>
                                </a>
                            @empty
                                <div class="hero-empty-state">
                                    <i class="bi bi-bag-heart"></i>
                                    <span>New arrivals will appear here soon.</span>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="container home-value-bar reveal" aria-label="Store benefits">
        <div class="value-item">
            <span><i class="bi bi-patch-check"></i></span>
            <div>
                <strong>Verified Deals</strong>
                <small>Only active offers make the shelf.</small>
            </div>
        </div>
        <div class="value-item">
            <span><i class="bi bi-ticket-perforated"></i></span>
            <div>
                <strong>{{ $coupons->count() }} Coupons</strong>
                <small>Ready for checkout savings.</small>
            </div>
        </div>
        <div class="value-item">
            <span><i class="bi bi-grid"></i></span>
            <div>
                <strong>{{ $featuredProducts->count() }} Products</strong>
                <small>Fresh picks from the catalog.</small>
            </div>
        </div>
        <div class="value-item">
            <span><i class="bi bi-shield-lock"></i></span>
            <div>
                <strong>Secure Checkout</strong>
                <small>Cart and account flows stay protected.</small>
            </div>
        </div>
    </section>

    @if($offers->count() > 0)
        <section class="container home-section reveal">
            <div class="section-heading-row">
                <div>
                    <span class="section-kicker">Limited-time pricing</span>
                    <h2>Active Offers</h2>
                </div>
                <a href="{{ route('frontend.offers') }}" class="section-action">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="offer-grid">
                @foreach($offers as $offer)
                    <article class="offer-card">
                        <div class="offer-card-top">
                            <span class="offer-type">{{ str_replace('_', ' ', ucfirst($offer->offer_type ?? 'Offer')) }}</span>
                            <span class="offer-discount">
                                {{ number_format($offer->discount_value, 0) }}% off
                            </span>
                        </div>
                        <h3>{{ $offer->offer_title }}</h3>
                        <p>
                            @if($offer->end_date)
                                Valid until {{ $offer->end_date->format('M d, Y') }}
                            @else
                                Available while the promotion is active
                            @endif
                        </p>
                        <a href="{{ route('frontend.offers') }}" class="offer-link">
                            Explore deal <i class="bi bi-chevron-right"></i>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if($featuredGrid->count() > 0)
        <section class="container home-section reveal">
            <div class="section-heading-row">
                <div>
                    <span class="section-kicker">Latest catalog drops</span>
                    <h2>Featured Products</h2>
                </div>
                <a href="{{ route('frontend.products.index') }}" class="section-action">
                    Shop all <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="product-grid">
                @foreach($featuredGrid as $product)
                    <article class="product-card">
                        <a href="{{ route('frontend.products.show', $product->product_slug) }}" class="product-media" aria-label="View {{ $product->product_name }}">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}">
                            @else
                                <span class="product-placeholder"><i class="bi bi-image"></i></span>
                            @endif
                        </a>
                        <div class="product-card-body">
                            <span class="product-category">{{ $product->category->category_name ?? 'Uncategorized' }}</span>
                            <h3>
                                <a href="{{ route('frontend.products.show', $product->product_slug) }}">{{ $product->product_name }}</a>
                            </h3>
                            <div class="product-card-footer">
                                <strong>₹{{ number_format($product->price, 2) }}</strong>
                                <a href="{{ route('cart.add') }}" class="icon-cart-button" aria-label="Add {{ $product->product_name }} to cart" onclick="event.preventDefault(); this.closest('article').querySelector('form').submit();">
                                    <i class="bi bi-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('cart.add') }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                        </form>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if($couponHighlights->count() > 0)
        <section class="container home-section coupon-section reveal">
            <div class="section-heading-row">
                <div>
                    <span class="section-kicker">Checkout boosters</span>
                    <h2>Verified Promo Codes</h2>
                </div>
                <a href="{{ route('frontend.offers') }}" class="section-action">
                    More savings <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="coupon-grid">
                @foreach($couponHighlights as $coupon)
                    <article class="coupon-card">
                        <div>
                            <span class="coupon-label">Code</span>
                            <h3>{{ $coupon->coupon_code }}</h3>
                            <p>
                                @if($coupon->min_purchase)
                                    Min. purchase ₹{{ number_format($coupon->min_purchase, 2) }}
                                @else
                                    No minimum purchase
                                @endif
                            </p>
                        </div>
                        <strong>
                            @if($coupon->discount_type == 'percentage')
                                {{ number_format($coupon->discount_value, 0) }}% OFF
                            @else
                                ₹{{ number_format($coupon->discount_value, 2) }} OFF
                            @endif
                        </strong>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</div>

<style>
    .storefront-home {
        margin-top: -1.5rem;
        padding-bottom: 3.5rem;
        color: #172033;
    }

    .home-hero {
        background:
            linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%);
        border-bottom: 1px solid rgba(23, 32, 51, 0.08);
        padding: 4rem 0 3rem;
    }

    .home-hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.02fr) minmax(320px, 0.98fr);
        gap: 3rem;
        align-items: center;
    }

    .eyebrow-label,
    .section-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        color: #2563eb;
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .home-hero-copy h1 {
        margin: 0.9rem 0 1rem;
        max-width: 720px;
        color: #111827;
        font-family: 'Outfit', sans-serif;
        font-size: 4.35rem;
        font-weight: 800;
        letter-spacing: 0;
        line-height: 0.98;
    }

    .home-hero-copy p {
        max-width: 610px;
        color: #5f6b7a;
        font-size: 1.08rem;
        line-height: 1.75;
        margin-bottom: 1.6rem;
    }

    .home-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .home-hero-actions .btn {
        border-radius: 8px;
        padding-inline: 1.1rem;
    }

    .home-hero-showcase {
        min-height: 410px;
        display: grid;
        align-items: center;
    }

    .hero-banner-image {
        width: 100%;
        height: 410px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid rgba(23, 32, 51, 0.08);
        box-shadow: 0 24px 70px rgba(23, 32, 51, 0.12);
    }

    .hero-product-stack {
        position: relative;
        min-height: 410px;
    }

    .hero-product-tile {
        position: absolute;
        display: grid;
        grid-template-columns: 132px 1fr;
        gap: 1rem;
        align-items: center;
        width: min(92%, 460px);
        min-height: 142px;
        padding: 0.9rem;
        border: 1px solid rgba(23, 32, 51, 0.09);
        border-radius: 8px;
        color: #172033;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 20px 60px rgba(23, 32, 51, 0.12);
        text-decoration: none;
        transition: transform 0.24s ease, box-shadow 0.24s ease, border-color 0.24s ease;
    }

    .hero-product-tile:hover {
        color: #172033;
        transform: translateY(-4px);
        border-color: rgba(37, 99, 235, 0.25);
        box-shadow: 0 26px 72px rgba(37, 99, 235, 0.16);
    }

    .hero-product-tile-1 {
        top: 0;
        right: 0;
        z-index: 3;
    }

    .hero-product-tile-2 {
        top: 132px;
        left: 0;
        z-index: 2;
    }

    .hero-product-tile-3 {
        right: 38px;
        bottom: 0;
        z-index: 1;
    }

    .hero-product-image {
        height: 112px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        overflow: hidden;
        background: #f3f6fb;
        color: #8a97a8;
        font-size: 2rem;
    }

    .hero-product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 0.5rem;
    }

    .hero-product-tile span,
    .product-category,
    .coupon-label,
    .offer-type {
        display: block;
        color: #64748b;
        font-size: 0.76rem;
        font-weight: 700;
        line-height: 1.2;
        text-transform: uppercase;
    }

    .hero-product-tile strong {
        display: block;
        margin: 0.35rem 0;
        font-size: 1rem;
        line-height: 1.25;
    }

    .hero-product-tile em {
        color: #2563eb;
        font-style: normal;
        font-weight: 800;
    }

    .hero-empty-state {
        min-height: 360px;
        border: 1px dashed rgba(23, 32, 51, 0.18);
        border-radius: 8px;
        display: grid;
        place-items: center;
        gap: 0.5rem;
        color: #64748b;
        background: #ffffff;
    }

    .hero-empty-state i {
        color: #2563eb;
        font-size: 2.35rem;
    }

    .home-value-bar {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1px;
        margin-top: 1.5rem;
        border: 1px solid rgba(23, 32, 51, 0.08);
        border-radius: 8px;
        overflow: hidden;
        background: rgba(23, 32, 51, 0.08);
        box-shadow: 0 16px 42px rgba(23, 32, 51, 0.07);
    }

    .value-item {
        display: flex;
        gap: 0.9rem;
        align-items: center;
        padding: 1rem;
        background: #ffffff;
    }

    .value-item span {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        color: #0f766e;
        background: #e8f7f4;
    }

    .value-item strong {
        display: block;
        color: #111827;
        line-height: 1.2;
    }

    .value-item small {
        display: block;
        color: #64748b;
        line-height: 1.35;
        margin-top: 0.2rem;
    }

    .home-section {
        margin-top: 3.25rem;
    }

    .section-heading-row {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .section-heading-row h2 {
        margin: 0.25rem 0 0;
        color: #111827;
        font-family: 'Outfit', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 0;
    }

    .section-action,
    .offer-link {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #2563eb;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
    }

    .section-action:hover,
    .offer-link:hover {
        color: #1d4ed8;
    }

    .offer-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .offer-card,
    .product-card,
    .coupon-card {
        border: 1px solid rgba(23, 32, 51, 0.08);
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 12px 30px rgba(23, 32, 51, 0.06);
    }

    .offer-card {
        display: flex;
        flex-direction: column;
        min-height: 220px;
        padding: 1.2rem;
    }

    .offer-card-top {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        align-items: center;
        margin-bottom: 1.35rem;
    }

    .offer-discount {
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
        color: #7c2d12;
        background: #ffedd5;
        font-size: 0.78rem;
        font-weight: 800;
        white-space: nowrap;
        text-transform: uppercase;
    }

    .offer-card h3 {
        margin: 0 0 0.65rem;
        color: #111827;
        font-family: 'Outfit', sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: 0;
        line-height: 1.25;
    }

    .offer-card p {
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 1.2rem;
    }

    .offer-link {
        margin-top: auto;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }

    .product-card {
        overflow: hidden;
        transition: transform 0.24s ease, box-shadow 0.24s ease, border-color 0.24s ease;
    }

    .product-card:hover {
        transform: translateY(-4px);
        border-color: rgba(37, 99, 235, 0.2);
        box-shadow: 0 18px 44px rgba(23, 32, 51, 0.1);
    }

    .product-media {
        display: grid;
        place-items: center;
        height: 210px;
        padding: 1rem;
        background: #f7f9fc;
        text-decoration: none;
    }

    .product-media img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.24s ease;
    }

    .product-card:hover .product-media img {
        transform: scale(1.03);
    }

    .product-placeholder {
        width: 96px;
        height: 96px;
        display: grid;
        place-items: center;
        border-radius: 8px;
        color: #94a3b8;
        background: #eef2f7;
        font-size: 2rem;
    }

    .product-card-body {
        padding: 1rem;
    }

    .product-card h3 {
        min-height: 2.7em;
        margin: 0.35rem 0 0.9rem;
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 800;
        line-height: 1.35;
        letter-spacing: 0;
    }

    .product-card h3 a {
        color: #111827;
        text-decoration: none;
    }

    .product-card h3 a:hover {
        color: #2563eb;
    }

    .product-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .product-card-footer strong {
        color: #111827;
        font-size: 1.06rem;
    }

    .icon-cart-button {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        color: #ffffff;
        background: #111827;
        text-decoration: none;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .icon-cart-button:hover {
        color: #ffffff;
        background: #2563eb;
        transform: translateY(-2px);
    }

    .coupon-section {
        padding: 1.5rem;
        border: 1px solid rgba(23, 32, 51, 0.08);
        border-radius: 8px;
        background: #f8fafc;
    }

    .coupon-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }

    .coupon-card {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        min-height: 150px;
        padding: 1rem;
    }

    .coupon-card h3 {
        margin: 0.35rem 0;
        color: #111827;
        font-family: 'Outfit', sans-serif;
        font-size: 1.05rem;
        font-weight: 900;
        letter-spacing: 0;
        word-break: break-word;
    }

    .coupon-card p {
        color: #64748b;
        line-height: 1.45;
        margin: 0;
    }

    .coupon-card strong {
        align-self: flex-start;
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
        color: #065f46;
        background: #dff7ed;
        font-size: 0.8rem;
        white-space: nowrap;
    }

    @media (max-width: 1199px) {
        .home-hero-copy h1 {
            font-size: 3.55rem;
        }

        .product-grid,
        .coupon-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 991px) {
        .home-hero {
            padding: 3rem 0 2.5rem;
        }

        .home-hero-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .home-hero-copy h1 {
            font-size: 3rem;
        }

        .home-hero-showcase,
        .hero-product-stack {
            min-height: 360px;
        }

        .hero-product-tile {
            position: relative;
            top: auto;
            right: auto;
            left: auto;
            bottom: auto;
            width: 100%;
            margin-bottom: 0.75rem;
        }

        .home-value-bar,
        .offer-grid,
        .product-grid,
        .coupon-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .storefront-home {
            margin-top: -1rem;
            padding-bottom: 2.5rem;
        }

        .home-hero {
            padding: 2.5rem 0 2rem;
        }

        .home-hero-copy h1 {
            font-size: 2.35rem;
            line-height: 1.05;
        }

        .home-hero-copy p {
            font-size: 1rem;
        }

        .home-hero-actions .btn {
            width: 100%;
        }

        .home-hero-showcase,
        .hero-product-stack {
            min-height: auto;
        }

        .hero-product-tile {
            grid-template-columns: 96px 1fr;
            min-height: 116px;
        }

        .hero-product-image {
            height: 88px;
        }

        .hero-banner-image {
            height: 300px;
        }

        .home-value-bar,
        .offer-grid,
        .product-grid,
        .coupon-grid {
            grid-template-columns: 1fr;
        }

        .section-heading-row {
            align-items: flex-start;
            flex-direction: column;
        }

        .section-heading-row h2 {
            font-size: 1.65rem;
        }

        .product-media {
            height: 230px;
        }

        .coupon-section {
            padding: 1rem;
        }
    }
</style>
@endsection
