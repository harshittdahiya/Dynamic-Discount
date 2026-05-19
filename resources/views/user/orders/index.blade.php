@extends('layouts.frontend')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold"><i class="bi bi-box-seam text-primary me-2"></i>My Orders</h2>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Order ID</th>
                            <th>Date</th>
                            <th>Total Items</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                <td>{{ $order->items->count() }}</td>
                                <td>₹{{ number_format($order->final_amount, 2) }}</td>
                                <td>
                                    @if($order->order_status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($order->order_status == 'processing')
                                        <span class="badge bg-info text-dark">Processing</span>
                                    @elseif($order->order_status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-box-seam text-muted mb-3 d-block" style="font-size: 3rem;"></i>
                                    <h5 class="fw-bold">No Orders Found</h5>
                                    <p class="text-muted">You haven't placed any orders yet.</p>
                                    <a href="{{ route('frontend.products.index') }}" class="btn btn-primary mt-2">Start Shopping</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top-0 pt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
