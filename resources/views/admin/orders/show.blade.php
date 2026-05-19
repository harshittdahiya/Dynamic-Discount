@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back to Orders</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Items Ordered</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="text-end pe-4">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="rounded me-3 object-fit-cover" style="width: 40px; height: 40px;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                            <span>{{ $item->product_name }}</span>
                                        </div>
                                    </td>
                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 font-weight-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Customer & Shipping Info</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <p class="text-muted mb-1 text-uppercase small font-weight-bold">Account</p>
                        <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                        <p class="mb-0">{{ $order->user->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1 text-uppercase small font-weight-bold">Shipping Address</p>
                        <p class="mb-1"><strong>{{ $order->billing_name }}</strong></p>
                        <p class="mb-1">{{ $order->billing_address }}</p>
                        <p class="mb-1">{{ $order->billing_state }}, {{ $order->billing_zip }}</p>
                        <p class="mb-0">{{ $order->billing_country }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Order Status</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small font-weight-bold">Update Status</label>
                        <select name="order_status" class="form-select @if($order->order_status == 'pending') border-warning @elseif($order->order_status == 'processing') border-info @elseif($order->order_status == 'completed') border-success @else border-danger @endif">
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="font-weight-bold">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>

                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount @if($order->coupon) ({{ $order->coupon->coupon_code }}) @endif</span>
                        <span class="font-weight-bold">-₹{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif

                <hr class="my-3">
                
                <div class="d-flex justify-content-between">
                    <span class="h5 font-weight-bold text-dark">Total</span>
                    <span class="h5 font-weight-bold text-primary">₹{{ number_format($order->final_amount, 2) }}</span>
                </div>
            </div>
        </div>
        
        <div class="text-muted small text-center">
            Placed on: {{ $order->created_at->format('F d, Y \a\t h:i A') }}
        </div>
    </div>
</div>
@endsection
