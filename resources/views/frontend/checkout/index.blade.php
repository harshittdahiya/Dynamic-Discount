@extends('layouts.frontend')

@section('content')
<div class="container mb-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold">Secure Checkout <i class="bi bi-lock-fill text-success"></i></h2>
            <p class="text-muted">Review your order and enter shipping details to complete your purchase.</p>
        </div>
    </div>

    <div class="row">
        <!-- Billing Details Form (Mockup) -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4 border-bottom pb-3">Billing & Shipping Details</h4>
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="firstName" class="form-label">First name</label>
                                <input type="text" name="billing_name" class="form-control bg-light" id="firstName" value="{{ Auth::user()->name }}" required>
                            </div>

                            <div class="col-sm-6">
                                <label for="lastName" class="form-label">Last name</label>
                                <input type="text" class="form-control bg-light" id="lastName" required>
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control bg-light" id="email" value="{{ Auth::user()->email }}" readonly>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="billing_address" class="form-control bg-light" id="address" placeholder="1234 Main St" required>
                            </div>

                            <div class="col-md-5">
                                <label for="country" class="form-label">Country</label>
                                <select name="billing_country" class="form-select bg-light" id="country" required>
                                    <option value="">Choose...</option>
                                    <option value="United States">United States</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="state" class="form-label">State</label>
                                <select name="billing_state" class="form-select bg-light" id="state" required>
                                    <option value="">Choose...</option>
                                    <option value="California">California</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="zip" class="form-label">Zip</label>
                                <input type="text" name="billing_zip" class="form-control bg-light" id="zip" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h4 class="fw-bold mb-3">Payment Method</h4>
                        <div class="my-3">
                            <div class="form-check">
                                <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked required>
                                <label class="form-check-label" for="credit">Credit card</label>
                            </div>
                            <div class="form-check">
                                <input id="paypal" name="paymentMethod" type="radio" class="form-check-input" required>
                                <label class="form-check-label" for="paypal">PayPal</label>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cc-name" class="form-label">Name on card</label>
                                <input type="text" class="form-control bg-light" id="cc-name" required>
                                <small class="text-muted">Full name as displayed on card</small>
                            </div>

                            <div class="col-md-6">
                                <label for="cc-number" class="form-label">Credit card number</label>
                                <input type="text" class="form-control bg-light" id="cc-number" required>
                            </div>

                            <div class="col-md-3">
                                <label for="cc-expiration" class="form-label">Expiration</label>
                                <input type="text" class="form-control bg-light" id="cc-expiration" required>
                            </div>

                            <div class="col-md-3">
                                <label for="cc-cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control bg-light" id="cc-cvv" required>
                            </div>
                        </div>

                        <hr class="my-4 border-2">

                        <button class="btn btn-success w-100 py-3 fs-5 fw-bold rounded-3 shadow" type="submit">
                            <i class="bi bi-shield-lock me-2"></i> Complete Order - ₹{{ number_format($total, 2) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary Final -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-header bg-primary text-white p-3 rounded-top-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-cart-check me-2"></i>Final Summary</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($cart as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center lh-sm p-3">
                                <div class="d-flex align-items-center me-3">
                                    @if(!empty($item['image']))
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="rounded-3 me-3 object-fit-cover" style="width: 48px; height: 48px;">
                                    @else
                                        <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="my-0">{{ $item['name'] }}</h6>
                                        <small class="text-muted">Qty: {{ $item['quantity'] }}</small>
                                    </div>
                                </div>
                                <span class="text-muted">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </li>
                        @endforeach
                        
                        <li class="list-group-item d-flex justify-content-between bg-light p-3">
                            <span class="text-muted">Subtotal</span>
                            <strong>₹{{ number_format($subtotal, 2) }}</strong>
                        </li>

                        @if(Session::has('coupon'))
                            @php $couponData = Session::get('coupon'); @endphp
                            <li class="list-group-item d-flex justify-content-between bg-success bg-opacity-10 text-success p-3">
                                <div class="text-success">
                                    <h6 class="my-0 fw-bold">Promo code</h6>
                                    <small>{{ $couponData['code'] }}</small>
                                </div>
                                <span class="fw-bold">−₹{{ number_format($discount, 2) }}</span>
                            </li>
                        @endif

                        <li class="list-group-item d-flex justify-content-between p-3 border-top border-2">
                            <span class="fs-5 fw-bold">Total (₹)</span>
                            <span class="fs-4 fw-bold text-primary">₹{{ number_format($total, 2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
