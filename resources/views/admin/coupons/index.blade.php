@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-primary">Manage Discount Coupons</h5>
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm">
                        + Add New Coupon
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('admin.coupons.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search coupon code..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Search</button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Limits</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>
                                            <span class="badge bg-dark fs-6">{{ $coupon->coupon_code }}</span>
                                        </td>
                                        <td>
                                            @if($coupon->discount_type == 'percentage')
                                                <span class="fw-bold">{{ number_format($coupon->discount_value, 0) }}% Off</span>
                                                @if($coupon->max_discount)
                                                    <br><small class="text-muted">Up to ₹{{ number_format($coupon->max_discount, 2) }}</small>
                                                @endif
                                            @else
                                                <span class="fw-bold">₹{{ number_format($coupon->discount_value, 2) }} Off</span>
                                            @endif
                                            @if($coupon->min_purchase)
                                                <br><small class="text-muted">Min: ₹{{ number_format($coupon->min_purchase, 2) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="d-block text-muted">
                                                Total: {{ $coupon->usage_limit ?? 'Unlimited' }}
                                            </small>
                                            <small class="d-block text-muted">
                                                Per User: {{ $coupon->per_user_limit ?? 'Unlimited' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($coupon->expiry_date)
                                                @if($coupon->expiry_date->isPast())
                                                    <span class="text-danger">Expired on {{ $coupon->expiry_date->format('M d, Y') }}</span>
                                                @else
                                                    <span>{{ $coupon->expiry_date->format('M d, Y') }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No expiry</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.coupons.toggleStatus', $coupon->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-{{ $coupon->status == 'active' ? 'success' : 'secondary' }} rounded-pill px-3">
                                                    {{ ucfirst($coupon->status) }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            
                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No coupons found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-3">
                        {{ $coupons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
