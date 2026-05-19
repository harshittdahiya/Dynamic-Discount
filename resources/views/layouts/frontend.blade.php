<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Dynamic Discount System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        :root {
            --brand-ink: #10233f;
            --brand-ocean: #0f62fe;
            --brand-mint: #11b39c;
            --brand-sun: #ffc145;
            --brand-cloud: #f4f9ff;
            --brand-card: #ffffff;
            --brand-border: rgba(16, 35, 63, 0.09);
            --brand-shadow: 0 14px 45px rgba(16, 35, 63, 0.12);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--brand-ink);
            background:
                radial-gradient(circle at 14% 12%, rgba(17, 179, 156, 0.13), transparent 28%),
                radial-gradient(circle at 84% 10%, rgba(15, 98, 254, 0.12), transparent 26%),
                radial-gradient(circle at 78% 86%, rgba(255, 193, 69, 0.1), transparent 22%),
                linear-gradient(160deg, #f8fcff 0%, #ecf5ff 45%, #f8fbff 100%);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            z-index: -1;
            border-radius: 999px;
            filter: blur(24px);
            pointer-events: none;
        }

        body::before {
            width: 340px;
            height: 340px;
            top: -120px;
            left: -90px;
            background: rgba(15, 98, 254, 0.22);
        }

        body::after {
            width: 320px;
            height: 320px;
            right: -80px;
            bottom: -120px;
            background: rgba(17, 179, 156, 0.2);
        }

        h1, h2, h3, h4, h5 {
            font-family: 'DM Serif Display', serif;
            letter-spacing: 0.2px;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(15, 98, 254, 0.11);
        }

        .navbar-brand {
            font-family: 'DM Serif Display', serif;
            font-size: 1.65rem;
            letter-spacing: 0.5px;
        }

        .nav-link {
            position: relative;
            transition: color 0.25s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0.75rem;
            right: 0.75rem;
            bottom: 0.35rem;
            height: 2px;
            background: linear-gradient(90deg, var(--brand-mint), var(--brand-ocean));
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.25s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            transform: scaleX(1);
        }

        .search-bar-form {
            min-width: min(100%, 420px);
        }

        .search-bar-group {
            box-shadow: 0 8px 20px rgba(16, 35, 63, 0.08);
            border-radius: 999px;
            overflow: hidden;
        }

        .search-bar-icon,
        .search-bar-input {
            border-color: rgba(16, 35, 63, 0.1) !important;
        }

        .search-bar-input {
            box-shadow: none !important;
        }

        .search-bar-input::placeholder {
            color: #94a3b8;
        }

        .search-suggestions {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid rgba(16, 35, 63, 0.1);
            border-radius: 18px;
            overflow: hidden;
            z-index: 1080;
        }

        .search-suggestion-item {
            display: flex;
            gap: 0.85rem;
            align-items: center;
            padding: 0.75rem 0.9rem;
            text-decoration: none;
            color: var(--brand-ink);
            border-bottom: 1px solid rgba(16, 35, 63, 0.06);
        }

        .search-suggestion-item:last-child {
            border-bottom: 0;
        }

        .search-suggestion-item:hover {
            background: #f5f9ff;
        }

        .search-suggestion-thumb {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #eff6ff, #e2ebff);
            display: grid;
            place-items: center;
            color: #6a7fa8;
            flex: 0 0 auto;
            overflow: hidden;
        }

        .search-suggestion-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .search-suggestion-name {
            font-weight: 700;
            line-height: 1.2;
        }

        .search-suggestion-meta {
            font-size: 0.8rem;
            color: #6b7d95;
            line-height: 1.2;
        }

        .cart-badge {
            position: absolute;
            top: 0;
            right: -5px;
            font-size: 0.65rem;
            animation: pulseBadge 1.8s infinite;
        }

        @keyframes pulseBadge {
            0% { transform: scale(1); }
            50% { transform: scale(1.16); }
            100% { transform: scale(1); }
        }

        main {
            flex: 1;
        }

        .card {
            border: 1px solid var(--brand-border);
            background: var(--brand-card);
            box-shadow: 0 8px 26px rgba(16, 35, 63, 0.07);
            transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--brand-shadow);
            border-color: rgba(15, 98, 254, 0.22);
        }

        .btn {
            border-radius: 999px;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(120deg, var(--brand-ocean), #2f7dff 55%, #3fa0ff);
            border: none;
            box-shadow: 0 10px 24px rgba(15, 98, 254, 0.27);
        }

        .btn-primary:hover {
            box-shadow: 0 14px 28px rgba(15, 98, 254, 0.34);
        }

        .btn-outline-primary {
            color: var(--brand-ocean);
            border-color: rgba(15, 98, 254, 0.45);
            background: rgba(255, 255, 255, 0.72);
        }

        .btn-outline-primary:hover {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(120deg, var(--brand-mint), var(--brand-ocean));
            box-shadow: 0 12px 26px rgba(15, 98, 254, 0.24);
        }

        .section-title {
            color: var(--brand-ink);
            position: relative;
            display: inline-block;
            padding-right: 14px;
        }

        .section-title::after {
            content: '';
            display: block;
            margin-top: 6px;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--brand-mint), var(--brand-ocean));
            border-radius: 30px;
        }

        .alert {
            border: 1px solid;
            border-radius: 16px;
            backdrop-filter: blur(3px);
        }

        .alert-success {
            background-color: rgba(17, 179, 156, 0.11);
            color: #0a5d50;
            border-color: rgba(17, 179, 156, 0.35);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #7a1515;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .footer {
            background:
                radial-gradient(circle at 85% 18%, rgba(17, 179, 156, 0.2), transparent 30%),
                linear-gradient(135deg, #07172b 0%, #0b2441 52%, #112f50 100%);
            color: #deecff;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 36px 36px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), transparent);
            pointer-events: none;
        }

        .footer > * {
            position: relative;
            z-index: 1;
        }

        .footer a {
            transition: color 0.25s ease, transform 0.25s ease;
        }

        .footer a:hover {
            color: #7ad6ff !important;
            transform: translateX(3px);
        }

        .text-gray-300 {
            color: #cbddf4 !important;
        }

        .text-gray-400 {
            color: #95b3d7 !important;
        }

        .border-gray-700 {
            border-color: rgba(203, 221, 244, 0.25) !important;
        }

        .reveal {
            opacity: 0;
            transform: translateY(26px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.45rem;
            }

            .search-bar-form {
                min-width: 100%;
            }

            .search-suggestions {
                position: static;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top py-3">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('frontend.home') }}">
                <i class="bi bi-tag-fill me-1"></i> DiscountStore
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active text-primary' : '' }}" href="{{ route('frontend.home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.products.*') ? 'active text-primary' : '' }}" href="{{ route('frontend.products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.offers') ? 'active text-primary' : '' }}" href="{{ route('frontend.offers') }}">Offers</a>
                    </li>
                </ul>

                <form class="d-flex flex-grow-1 mx-lg-4 my-3 my-lg-0 search-bar-form position-relative" action="{{ route('frontend.products.index') }}" method="GET" role="search" data-search-suggest-form data-search-suggest-url="{{ route('frontend.products.suggestions') }}">
                    <div class="input-group search-bar-group">
                        <span class="input-group-text bg-white border-end-0 search-bar-icon"><i class="bi bi-search"></i></span>
                        <input
                            type="search"
                            name="search"
                            class="form-control border-start-0 search-bar-input"
                            data-search-suggest-input
                            placeholder="Search products..."
                            value="{{ request('search') }}"
                            aria-label="Search products"
                        >
                        <button class="btn btn-primary px-3" type="submit">Search</button>
                    </div>
                    <div class="search-suggestions shadow-sm" data-search-suggest-panel hidden></div>
                </form>
                
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                            @php $cartCount = count(session()->get('cart', [])); @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute translate-middle badge rounded-pill bg-danger cart-badge">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}" data-auth-toggle="login">{{ __('Login') }}</a>
                            </li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="btn btn-outline-primary btn-sm ms-2" href="{{ route('register') }}" data-auth-toggle="register">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-person-circle me-2"></i>{{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.index') }}">
                                            <i class="bi bi-box-seam me-2"></i>My Orders
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4" id="pageMain">
        @if (session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer mt-auto py-5 bg-linear-to-r from-slate-900 via-slate-800 to-slate-900 border-top border-primary border-opacity-25">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3 fw-bold">
                        <i class="bi bi-tag-fill me-2 text-primary"></i>DiscountStore
                    </h5>
                    <p class="text-gray-300 lh-lg">Your ultimate destination for the best deals, seasonal offers, and verified discount coupons on your favorite products.</p>
                    <div class="mt-3">
                        <a href="#" class="text-gray-300 text-decoration-none me-3 fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-gray-300 text-decoration-none me-3 fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-gray-300 text-decoration-none me-3 fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-gray-300 text-decoration-none fs-5"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3 fw-bold">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('frontend.home') }}" class="text-gray-300 text-decoration-none transition-all hover:text-primary"><i class="bi bi-chevron-right me-1"></i>Home</a></li>
                        <li class="mb-2"><a href="{{ route('frontend.products.index') }}" class="text-gray-300 text-decoration-none transition-all hover:text-primary"><i class="bi bi-chevron-right me-1"></i>Shop All Products</a></li>
                        <li class="mb-2"><a href="{{ route('frontend.offers') }}" class="text-gray-300 text-decoration-none transition-all hover:text-primary"><i class="bi bi-chevron-right me-1"></i>Active Offers</a></li>
                    </ul>
                    <a href="{{ route('frontend.contact') }}" class="btn btn-outline-primary btn-sm mt-2">Contact Us</a>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3 fw-bold">Newsletter</h5>
                    <p class="text-gray-300 mb-3">Subscribe for exclusive deals and insider coupons delivered weekly.</p>
                    <div class="input-group input-group-sm">
                        <input type="email" class="form-control border-0 py-2" placeholder="Enter your email" aria-label="Email address">
                        <button class="btn btn-primary px-3" type="button"><i class="bi bi-envelope me-1"></i>Subscribe</button>
                    </div>
                    <small class="text-gray-400 d-block mt-2">✓ No spam, unsubscribe anytime</small>
                </div>
            </div>
            <div class="border-top border-gray-700 pt-4 mt-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <small class="text-gray-300">&copy; {{ date('Y') }} DiscountStore. All rights reserved. | <a href="#" class="text-primary text-decoration-none">Privacy Policy</a> | <a href="#" class="text-primary text-decoration-none">Terms of Service</a></small>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                        <small class="text-gray-400">Made with <i class="bi bi-heart-fill text-danger"></i> for great deals</small>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
