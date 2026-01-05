@extends('layouts.master')

@section('title', 'Edit Review')

@section('content')
<div class="breadcrumb-section">
    <div class="container">
        <h2>Edit Review</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('product.show', $review->product->slug) }}">{{ $review->product->name }}</a></li>
                <li class="breadcrumb-item active">Edit Review</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-b-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Edit Your Review</h4>
                    </div>
                    <div class="card-body">
                        {{-- Product Info --}}
                        <div class="product-info d-flex align-items-center mb-4 p-3 bg-light rounded">
                            @if($review->product->images->count() > 0)
                                <img src="{{ asset('uploads/' . $review->product->images->first()->image_path) }}"
                                     alt="{{ $review->product->name }}"
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     class="me-3 rounded">
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $review->product->name }}</h5>
                                <p class="mb-0 text-muted">Original review date: {{ $review->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <form action="{{ route('review.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Rating --}}
                            <div class="mb-4">
                                <label class="form-label">Your Rating <span class="text-danger">*</span></label>
                                <div class="star-rating-input">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                               {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}">
                                            <i class="ri-star-fill"></i>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Title --}}
                            <div class="mb-3">
                                <label for="title" class="form-label">Review Title <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title', $review->title) }}"
                                       maxlength="100"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Review Text --}}
                            <div class="mb-3">
                                <label for="review_text" class="form-label">Your Review <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('review_text') is-invalid @enderror"
                                          id="review_text"
                                          name="review_text"
                                          rows="6"
                                          minlength="10"
                                          maxlength="2000"
                                          required>{{ old('review_text', $review->review_text) }}</textarea>
                                <small class="text-muted">
                                    <span id="charCount">0</span>/2000 characters
                                </small>
                                @error('review_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Existing Images --}}
                            @if($review->images->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label">Current Images</label>
                                    <div class="row g-2">
                                        @foreach($review->images as $image)
                                            <div class="col-auto">
                                                <div class="position-relative">
                                                    <img src="{{ $image->url }}"
                                                         class="img-thumbnail"
                                                         style="width: 100px; height: 100px; object-fit: cover;">
                                                    <div class="form-check position-absolute bottom-0 start-0 bg-white p-1">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="remove_images[]" value="{{ $image->id }}"
                                                               id="remove{{ $image->id }}">
                                                        <label class="form-check-label small" for="remove{{ $image->id }}">
                                                            Remove
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Add New Images --}}
                            <div class="mb-4">
                                <label class="form-label">Add More Photos (Optional)</label>
                                <input type="file"
                                       class="form-control @error('images.*') is-invalid @enderror"
                                       name="images[]"
                                       multiple
                                       accept="image/jpeg,image/png,image/jpg"
                                       id="reviewImages">
                                <small class="text-muted">Maximum 5 images total</small>
                                @error('images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <div id="imagePreview" class="row g-2 mt-2"></div>
                            </div>

                            {{-- Alert --}}
                            <div class="alert alert-info">
                                <i class="ri-information-line"></i>
                                After editing, your review will need to be re-approved by admin.
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-solid hover-solid btn-animation">
                                    Update Review
                                </button>
                                <a href="{{ route('product.show', $review->product->slug) }}" class="btn btn-outline">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.star-rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.star-rating-input input {
    display: none;
}

.star-rating-input label {
    cursor: pointer;
    font-size: 32px;
    color: #ddd;
    transition: color 0.2s;
}

.star-rating-input label:hover,
.star-rating-input label:hover ~ label,
.star-rating-input input:checked ~ label {
    color: #ffc107;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reviewText = document.getElementById('review_text');
    const charCount = document.getElementById('charCount');

    reviewText.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    charCount.textContent = reviewText.value.length;

    const imageInput = document.getElementById('reviewImages');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function() {
        imagePreview.innerHTML = '';
        const files = Array.from(this.files).slice(0, 5);

        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-auto';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}"
                             class="img-thumbnail"
                             style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                `;
                imagePreview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });
});
</script>
@endpush
