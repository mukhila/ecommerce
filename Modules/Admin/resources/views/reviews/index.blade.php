@extends('admin::layouts.main')

@section('title', 'Reviews Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Reviews Management</h4>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Reviews</h5>
                    <h2 class="mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Pending</h5>
                    <h2 class="mb-0 text-warning">{{ $stats['pending'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Approved</h5>
                    <h2 class="mb-0 text-success">{{ $stats['approved'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Avg Rating</h5>
                    <h2 class="mb-0">{{ number_format($stats['average_rating'], 1) }} <i class="ri-star-fill text-warning"></i></h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Reviews Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">All Reviews</h4>
                        </div>
                        <div class="col-auto">
                            <form action="{{ route('admin.reviews.index') }}" method="GET" class="d-flex gap-2">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <input type="text" name="search" class="form-control form-control-sm"
                                       placeholder="Search reviews..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Bulk Actions --}}
                    <form action="{{ route('admin.reviews.bulk-action') }}" method="POST" id="bulkActionForm">
                        @csrf
                        <div class="d-flex gap-2 mb-3">
                            <select name="action" class="form-select form-select-sm w-auto" required>
                                <option value="">Bulk Actions</option>
                                <option value="approve">Approve</option>
                                <option value="reject">Reject</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Product</th>
                                        <th>Reviewer</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reviews as $review)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="review_ids[]" value="{{ $review->id }}" class="review-checkbox">
                                            </td>
                                            <td>
                                                {{ Str::limit($review->product->name, 30) }}
                                            </td>
                                            <td>
                                                {{ $review->user->name }}<br>
                                                <small class="text-muted">{{ $review->user->email }}</small>
                                                @if($review->is_verified_purchase)
                                                    <br><small class="text-success">
                                                        <i class="ri-checkbox-circle-line"></i> Verified
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="ri-star-{{ $i <= $review->star_rating ? 'fill' : 'line' }} text-warning"></i>
                                                    @endfor
                                                </div>
                                                <small>{{ $review->formatted_rating }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ Str::limit($review->title, 40) }}</strong><br>
                                                <small class="text-muted">{{ Str::limit($review->review_text, 60) }}</small>
                                                @if($review->images->count() > 0)
                                                    <br><small class="text-info">
                                                        <i class="ri-image-line"></i> {{ $review->images->count() }} images
                                                    </small>
                                                @endif
                                            </td>
                                            <td>{{ $review->created_at->format('d M Y') }}</td>
                                            <td>
                                                <form action="{{ route('admin.reviews.update-status', $review) }}" method="POST">
                                                    @csrf
                                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                        <option value="pending" {{ $review->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="approved" {{ $review->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="rejected" {{ $review->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.reviews.show', $review) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="ri-eye-line"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <p class="text-muted mb-0">No reviews found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    @if($reviews->hasPages())
                        <div class="mt-3">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkboxes
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.review-checkbox');

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Bulk action confirmation
    document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
        const selectedCount = document.querySelectorAll('.review-checkbox:checked').length;

        if (selectedCount === 0) {
            e.preventDefault();
            alert('Please select at least one review');
            return false;
        }

        const action = this.querySelector('[name="action"]').value;
        if (!action) {
            e.preventDefault();
            alert('Please select an action');
            return false;
        }

        if (action === 'delete') {
            if (!confirm(`Are you sure you want to delete ${selectedCount} review(s)?`)) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
@endpush
