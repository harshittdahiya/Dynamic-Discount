@extends('layouts.frontend')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
            <p class="text-muted mb-0">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Orders</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image_url)
                                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="rounded-3 me-3 object-fit-cover" style="width: 50px; height: 50px;">
                                                @else
                                                    <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                                <span class="fw-semibold">{{ $item->product_name }}</span>
                                            </div>
                                        </td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end pe-4 fw-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Shipping & Billing Address</h5>
                    <p class="mb-1"><strong>{{ $order->billing_name }}</strong></p>
                    <p class="mb-1">{{ $order->billing_address }}</p>
                    <p class="mb-1">{{ $order->billing_state }}, {{ $order->billing_zip }}</p>
                    <p class="mb-0">{{ $order->billing_country }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>

                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount @if($order->coupon) ({{ $order->coupon->coupon_code }}) @endif</span>
                            <span class="fw-semibold">-₹{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Total</span>
                        <span class="fs-5 fw-bold text-primary">₹{{ number_format($order->final_amount, 2) }}</span>
                    </div>
                    
                    <div class="p-3 rounded-3 text-center @if($order->order_status == 'pending') bg-warning bg-opacity-10 text-warning-emphasis @elseif($order->order_status == 'processing') bg-info bg-opacity-10 text-info-emphasis @elseif($order->order_status == 'completed') bg-success bg-opacity-10 text-success @else bg-danger bg-opacity-10 text-danger @endif">
                        <span class="fw-bold d-block mb-1">Order Status</span>
                        <span class="text-uppercase tracking-wide">{{ $order->order_status }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
