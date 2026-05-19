@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-primary">Manage Banners</h5>
                    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
                        + Add New Banner
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Active Dates</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($banners as $banner)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('storage/' . $banner->banner_image) }}" alt="Banner" class="img-thumbnail" style="height: 60px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $banner->title ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $banner->subtitle }}</small>
                                        </td>
                                        <td>
                                            @if($banner->start_date || $banner->end_date)
                                                <small>
                                                    <div>Start: <span class="fw-bold">{{ $banner->start_date ? \Carbon\Carbon::parse($banner->start_date)->format('M d, Y') : 'Always' }}</span></div>
                                                    <div>End: <span class="fw-bold">{{ $banner->end_date ? \Carbon\Carbon::parse($banner->end_date)->format('M d, Y') : 'Always' }}</span></div>
                                                </small>
                                            @else
                                                <span class="badge bg-secondary">Always Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.banners.toggleStatus', $banner->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-{{ $banner->status == 'active' ? 'success' : 'secondary' }} rounded-pill px-3">
                                                    {{ ucfirst($banner->status) }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            
                                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No banners found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-3">
                        {{ $banners->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
