<div class="review-item border-bottom pb-4 mb-4">
    <div class="review-header d-flex justify-content-between align-items-start mb-3">
        <div class="reviewer-info">
            <div class="d-flex align-items-center mb-2">
                <div class="reviewer-avatar me-3">
                    <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center"
                         style="width: 40px; height: 40px; border-radius: 50%; font-size: 18px;">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                </div>
                <div>
                    <h6 class="mb-0">{{ $review->user->name }}</h6>
                    @if($review->is_verified_purchase)
                        <small class="text-success">
                            <i class="ri-checkbox-circle-line"></i> Verified Purchase
                        </small>
                    @endif
                </div>
            </div>
            <div class="rating mb-2">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="ri-star-{{ $i <= $review->star_rating ? 'fill' : 'line' }} text-warning"></i>
                @endfor
                <span class="ms-2 text-muted">{{ $review->formatted_rating }}</span>
            </div>
        </div>
        <div class="review-date">
            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
        </div>
    </div>

    <div class="review-content">
        <h5 class="review-title mb-2">{{ $review->title }}</h5>
        <p class="review-text mb-3">{{ $review->review_text }}</p>

        {{-- Review Images --}}
        @if($review->images->count() > 0)
            <div class="review-images mb-3">
                <div class="row g-2">
                    @foreach($review->images as $image)
                        <div class="col-auto">
                            <a href="{{ $image->url }}" data-lightbox="review-{{ $review->id }}">
                                <img src="{{ $image->url }}" alt="Review image"
                                     class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Helpful Button --}}
        <div class="review-actions d-flex align-items-center gap-3">
            @auth
                <button class="btn btn-sm btn-outline-secondary helpful-btn"
                        data-review-id="{{ $review->id }}"
                        data-has-voted="{{ $review->hasBeenHelpfulBy(Auth::id()) ? 'true' : 'false' }}">
                    <i class="ri-thumb-up-line"></i>
                    <span class="helpful-text">
                        {{ $review->hasBeenHelpfulBy(Auth::id()) ? 'Helpful' : 'Mark as Helpful' }}
                    </span>
                    (<span class="helpful-count">{{ $review->helpful_count }}</span>)
                </button>
            @else
                <span class="text-muted">
                    <i class="ri-thumb-up-line"></i>
                    Helpful ({{ $review->helpful_count }})
                </span>
            @endauth

            {{-- Edit/Delete for own review --}}
            @auth
                @if($review->user_id === Auth::id())
                    <div class="own-review-actions">
                        <a href="{{ route('review.edit', $review->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-edit-line"></i> Edit
                        </a>
                        <form action="{{ route('review.destroy', $review->id) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="ri-delete-bin-line"></i> Delete
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        {{-- Admin Reply --}}
        @if($review->reply)
            <div class="admin-reply mt-3 p-3 bg-light rounded">
                <div class="d-flex align-items-start">
                    <i class="ri-customer-service-2-line text-primary me-2" style="font-size: 24px;"></i>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Response from {{ config('app.name') }}</h6>
                        <small class="text-muted">{{ $review->reply->created_at->diffForHumans() }}</small>
                        <p class="mb-0 mt-2">{{ $review->reply->reply_text }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Helpful vote functionality
document.addEventListener('DOMContentLoaded', function() {
    const helpfulButtons = document.querySelectorAll('.helpful-btn');

    helpfulButtons.forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.dataset.reviewId;
            toggleHelpful(reviewId, this);
        });
    });
});

function toggleHelpful(reviewId, button) {
    fetch(`/review/${reviewId}/helpful`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.dataset.hasVoted = data.has_voted;
            button.querySelector('.helpful-text').textContent =
                data.has_voted ? 'Helpful' : 'Mark as Helpful';
            button.querySelector('.helpful-count').textContent = data.helpful_count;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endpush
