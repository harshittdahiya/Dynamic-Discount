@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-primary">Manage Offers</h5>
                    <a href="{{ route('admin.offers.create') }}" class="btn btn-primary btn-sm">
                        + Add New Offer
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
                            <form action="{{ route('admin.offers.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search offer title..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Search</button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Offer Title</th>
                                    <th>Type / Target</th>
                                    <th>Discount</th>
                                    <th>Active Dates</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($offers as $offer)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $offer->offer_title }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark text-capitalize mb-1">{{ str_replace('_', ' ', $offer->offer_type) }}</span>
                                            @if($offer->offer_type === 'product' && $offer->product)
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    @if($offer->product->image_url)
                                                        <img src="{{ $offer->product->image_url }}" alt="{{ $offer->product->product_name }}" class="rounded object-fit-cover" style="width: 32px; height: 32px;">
                                                    @else
                                                        <span class="bg-light rounded d-inline-flex align-items-center justify-content-center text-muted" style="width: 32px; height: 32px;">
                                                            <i class="bi bi-image"></i>
                                                        </span>
                                                    @endif
                                                    <small class="text-muted"><i class="bi bi-box"></i> {{ $offer->product->product_name }}</small>
                                                </div>
                                            @elseif($offer->offer_type === 'category' && $offer->category)
                                                <br><small class="text-muted"><i class="bi bi-tags"></i> {{ $offer->category->category_name }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">{{ number_format($offer->discount_value, 0) }}% Off</span>
                                        </td>
                                        <td>
                                            @if($offer->start_date && $offer->end_date)
                                                <small class="d-block"><strong>Start:</strong> {{ $offer->start_date->format('M d, Y') }}</small>
                                                <small class="d-block"><strong>End:</strong> {{ $offer->end_date->format('M d, Y') }}</small>
                                            @else
                                                <span class="text-muted">Always Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.offers.toggleStatus', $offer->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-{{ $offer->status == 'active' ? 'success' : 'secondary' }} rounded-pill px-3">
                                                    {{ ucfirst($offer->status) }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.offers.edit', $offer->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            
                                            <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this offer?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No offers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-3">
                        {{ $offers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
