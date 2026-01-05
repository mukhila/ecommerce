@extends('admin::layouts.main')

@section('title', 'Review Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Review Details</h4>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to Reviews
                </a>
            </div>
        </div>
    </div>

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

    <div class="row">
        {{-- Main Review Details --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Review Information</h5>
                    <span class="badge bg-{{ $review->status_badge }}">
                        {{ ucfirst($review->status) }}
                    </span>
                </div>
                <div class="card-body">
                    {{-- Product Info --}}
                    <div class="product-info mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            @if($review->product->images->count() > 0)
                                <img src="{{ asset('uploads/' . $review->product->images->first()->image_path) }}"
                                     alt="{{ $review->product->name }}"
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     class="me-3 rounded">
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $review->product->name }}</h5>
                                <a href="{{ route('product.show', $review->product->slug) }}"
                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                    View Product
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Rating --}}
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="ri-star-{{ $i <= $review->star_rating ? 'fill' : 'line' }} text-warning" style="font-size: 24px;"></i>
                            @endfor
                            <span class="ms-2">{{ $review->formatted_rating }}/5.0</span>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <h5>{{ $review->title }}</h5>
                    </div>

                    {{-- Review Text --}}
                    <div class="mb-3">
                        <label class="form-label">Review</label>
                        <p>{{ $review->review_text }}</p>
                    </div>

                    {{-- Images --}}
                    @if($review->images->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Attached Images</label>
                            <div class="row g-2">
                                @foreach($review->images as $image)
                                    <div class="col-auto">
                                        <a href="{{ $image->url }}" target="_blank">
                                            <img src="{{ $image->url }}"
                                                 alt="Review image"
                                                 class="img-thumbnail"
                                                 style="width: 120px; height: 120px; object-fit: cover;">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Helpful Count --}}
                    <div class="mb-3">
                        <label class="form-label">Helpful Votes</label>
                        <p><i class="ri-thumb-up-line"></i> {{ $review->helpful_count }} users found this helpful</p>
                    </div>

                    {{-- Admin Reply Section --}}
                    <div class="mt-4 pt-4 border-top">
                        <h5 class="mb-3">Admin Reply</h5>

                        @if($review->reply)
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-2">{{ $review->reply->reply_text }}</p>
                                        <small class="text-muted">
                                            Replied by {{ $review->reply->admin->name }}
                                            on {{ $review->reply->created_at->format('d M Y H:i') }}
                                        </small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal" data-bs-target="#replyModal">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        @else
                            <button type="button" class="btn btn-primary"
                                    data-bs-toggle="modal" data-bs-target="#replyModal">
                                <i class="ri-reply-line"></i> Reply to Review
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Reviewer Info --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Reviewer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <p>{{ $review->user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <p>{{ $review->user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Verified Purchase</label>
                        <p>
                            @if($review->is_verified_purchase)
                                <span class="badge bg-success">
                                    <i class="ri-checkbox-circle-line"></i> Verified
                                </span>
                            @else
                                <span class="badge bg-secondary">Not Verified</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order Number</label>
                        <p>
                            <a href="{{ route('admin.orders.show', $review->order) }}">
                                {{ $review->order->order_number }}
                            </a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Review Date</label>
                        <p>{{ $review->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Actions Card --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($review->status !== 'approved')
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="ri-check-line"></i> Approve Review
                                </button>
                            </form>
                        @endif

                        @if($review->status !== 'rejected')
                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="ri-close-line"></i> Reject Review
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.reviews.destroy', $review) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this review?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="ri-delete-bin-line"></i> Delete Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reply Modal --}}
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.reviews.reply', $review) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ $review->reply ? 'Edit' : 'Add' }} Reply</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reply_text" class="form-label">Reply Text</label>
                        <textarea class="form-control"
                                  id="reply_text"
                                  name="reply_text"
                                  rows="5"
                                  required>{{ $review->reply->reply_text ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        {{ $review->reply ? 'Update' : 'Post' }} Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
