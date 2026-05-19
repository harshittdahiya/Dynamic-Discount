@extends('layouts.frontend')

@section('content')
<div class="container mb-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Shopping Cart</h2>
        </div>
    </div>

    @if(count($cart) > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                    <th scope="col" class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $details)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if(!empty($details['image']))
                                                    <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="rounded-3 me-3 object-fit-cover" style="width: 60px; height: 60px;">
                                                @else
                                                    <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>₹{{ number_format($details['price'], 2) }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $details['quantity'] }}</span>
                                        </td>
                                        <td class="fw-bold">₹{{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                        <td class="text-end pe-4">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Order Summary</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold">₹{{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if($coupon)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount ({{ $coupon['code'] }})</span>
                                <span class="fw-semibold">-₹{{ number_format($discount, 2) }}</span>
                            </div>
                        @endif

                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold">Total</span>
                            <span class="fs-5 fw-bold text-primary">₹{{ number_format($total, 2) }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 py-3 fw-bold rounded-3">Proceed to Checkout</a>
                    </div>
                </div>

                <!-- Coupon Form -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-tag-fill text-primary me-2"></i>Apply Promo Code</h6>
                        
                        @if($coupon)
                            <div class="alert alert-success d-flex justify-content-between align-items-center mb-0 px-3 py-2">
                                <span><strong>{{ $coupon['code'] }}</strong> applied.</span>
                                <form action="{{ route('cart.removeCoupon') }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none"><i class="bi bi-x-circle"></i></button>
                                </form>
                            </div>
                        @else
                            @auth
                                <form action="{{ route('cart.applyCoupon') }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="coupon_code" class="form-control" placeholder="Enter code" required>
                                        <button class="btn btn-outline-primary" type="submit">Apply</button>
                                    </div>
                                </form>
                            @else
                                <div class="d-grid gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Log in to apply promo codes</a>
                                </div>
                            @endauth
                        @endif

                        @if($couponStatuses->count() > 0)
                            <hr class="my-3">
                            <div>
                                <p class="fw-semibold mb-2">All Promo Codes</p>
                                <div class="d-flex flex-column gap-2 promo-codes-list {{ $couponStatuses->count() > 2 ? 'promo-codes-scroll pe-1' : '' }}">
                                    @foreach($couponStatuses as $couponStatus)
                                        @php
                                            $listedCoupon = $couponStatus['coupon'];
                                        @endphp
                                        <div class="d-flex justify-content-between align-items-center border rounded-3 px-3 py-2 bg-light-subtle">
                                            <div>
                                                <div class="fw-bold">{{ $listedCoupon->coupon_code }}</div>
                                                <small class="text-muted">
                                                    @if($listedCoupon->discount_type === 'percentage')
                                                        {{ number_format($listedCoupon->discount_value, 0) }}% off
                                                    @else
                                                        ₹{{ number_format($listedCoupon->discount_value, 2) }} off
                                                    @endif
                                                </small>
                                                @if(!$couponStatus['can_apply'])
                                                    <div><small class="text-danger">{{ $couponStatus['reason'] }}</small></div>
                                                @endif
                                            </div>
                                            <form action="{{ route('cart.applyCoupon') }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="coupon_code" value="{{ $listedCoupon->coupon_code }}">
                                                <button type="submit" class="btn btn-sm btn-primary" {{ !$couponStatus['can_apply'] ? 'disabled' : '' }}>
                                                    {{ $couponStatus['can_apply'] ? 'Apply' : 'Not Eligible' }}
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border-0">
                    <i class="bi bi-cart-x text-muted mb-3 d-block" style="font-size: 5rem;"></i>
                    <h3 class="fw-bold text-dark mb-3">Your Cart is Empty</h3>
                    <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ route('frontend.products.index') }}" class="btn btn-primary px-4 py-2">Start Shopping</a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .promo-codes-scroll {
        max-height: 190px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(15, 98, 254, 0.45) rgba(15, 98, 254, 0.08);
    }

    .promo-codes-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .promo-codes-scroll::-webkit-scrollbar-track {
        background: rgba(15, 98, 254, 0.08);
        border-radius: 999px;
    }

    .promo-codes-scroll::-webkit-scrollbar-thumb {
        background: rgba(15, 98, 254, 0.45);
        border-radius: 999px;
    }

    .promo-codes-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(15, 98, 254, 0.62);
    }
</style>
@endsection
