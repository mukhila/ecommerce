@extends('layouts.master')

@section('title', 'Write a Review')

@section('content')
<div class="breadcrumb-section">
    <div class="container">
        <h2>Write a Review</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></li>
                <li class="breadcrumb-item active">Write Review</li>
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
                        <h4 class="mb-0">Review Product</h4>
                    </div>
                    <div class="card-body">
                        {{-- Product Info --}}
                        <div class="product-info d-flex align-items-center mb-4 p-3 bg-light rounded">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('uploads/' . $product->images->first()->image_path) }}"
                                     alt="{{ $product->name }}"
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     class="me-3 rounded">
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $product->name }}</h5>
                                <p class="mb-0 text-muted">Order: {{ $order->order_number }}</p>
                            </div>
                        </div>

                        {{-- Review Form --}}
                        <form action="{{ route('review.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="order_id" value="{{ $order->id }}">

                            {{-- Rating --}}
                            <div class="mb-4">
                                <label class="form-label">Your Rating <span class="text-danger">*</span></label>
                                <div class="star-rating-input">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                               {{ old('rating') == $i ? 'checked' : '' }} required>
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
                                       value="{{ old('title') }}"
                                       placeholder="Summarize your review in one line"
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
                                          placeholder="Share your experience with this product (minimum 10 characters)"
                                          minlength="10"
                                          maxlength="2000"
                                          required>{{ old('review_text') }}</textarea>
                                <small class="text-muted">
                                    <span id="charCount">0</span>/2000 characters
                                </small>
                                @error('review_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Image Upload --}}
                            <div class="mb-4">
                                <label class="form-label">Add Photos (Optional)</label>
                                <input type="file"
                                       class="form-control @error('images.*') is-invalid @enderror"
                                       name="images[]"
                                       multiple
                                       accept="image/jpeg,image/png,image/jpg"
                                       id="reviewImages">
                                <small class="text-muted">You can upload up to 5 images (JPEG, PNG, max 2MB each)</small>
                                @error('images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                {{-- Image Preview --}}
                                <div id="imagePreview" class="row g-2 mt-2"></div>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-solid hover-solid btn-animation">
                                    Submit Review
                                </button>
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline">
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
    // Character counter
    const reviewText = document.getElementById('review_text');
    const charCount = document.getElementById('charCount');

    reviewText.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    // Update initial count
    charCount.textContent = reviewText.value.length;

    // Image preview
    const imageInput = document.getElementById('reviewImages');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function() {
        imagePreview.innerHTML = '';
        const files = Array.from(this.files).slice(0, 5); // Max 5 images

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
                        <button type="button"
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-image"
                                data-index="${index}"
                                style="padding: 2px 6px;">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                `;
                imagePreview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });

    // Remove image from preview
    imagePreview.addEventListener('click', function(e) {
        if (e.target.closest('.remove-image')) {
            const button = e.target.closest('.remove-image');
            button.closest('.col-auto').remove();

            // Reset file input if all images removed
            if (imagePreview.children.length === 0) {
                imageInput.value = '';
            }
        }
    });
});
</script>
@endpush
