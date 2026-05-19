@extends('layouts.frontend')

@section('content')
<div class="container my-5 py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <i class="bi bi-check-circle-fill text-success mb-4" style="font-size: 5rem;"></i>
                <h1 class="fw-bold mb-3">Order Placed Successfully!</h1>
                <p class="text-muted fs-5 mb-4">Thank you for your purchase. We've received your order and are getting it ready for shipment.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary px-4 py-2 fw-bold">View My Orders</a>
                    <a href="{{ route('frontend.products.index') }}" class="btn btn-primary px-4 py-2 fw-bold">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
