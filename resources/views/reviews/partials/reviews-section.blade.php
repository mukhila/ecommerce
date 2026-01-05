<div class="reviews-section">
    {{-- Rating Summary --}}
    <div class="rating-summary mb-4">
        <div class="row">
            <div class="col-lg-4">
                <div class="average-rating text-center">
                    <h1 class="display-4 mb-2">{{ number_format($product->average_rating, 1) }}</h1>
                    <div class="stars mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="ri-star-{{ $i <= round($product->average_rating) ? 'fill' : 'line' }} text-warning"></i>
                        @endfor
                    </div>
                    <p class="text-muted">Based on {{ $product->review_count }} reviews</p>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="rating-breakdown">
                    @php $reviewSummary = $product->review_summary; @endphp
                    @foreach([5,4,3,2,1] as $star)
                        @php
                            $count = $reviewSummary[$star] ?? 0;
                            $percentage = $product->review_count > 0 ? ($count / $product->review_count) * 100 : 0;
                        @endphp
                        <div class="rating-row d-flex align-items-center mb-2">
                            <span class="star-label" style="width: 80px;">{{ $star }} Stars</span>
                            <div class="progress flex-grow-1 mx-3" style="height: 10px;">
                                <div class="progress-bar bg-warning" role="progressbar"
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="count text-muted" style="width: 50px;">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Write Review Button --}}
    @auth
        @php
            // Check if user can review (has delivered order with this product and hasn't reviewed it)
            $deliveredOrder = \App\Models\Order::where('user_id', Auth::id())
                ->where('status', 'delivered')
                ->whereHas('items', function($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->whereDoesntHave('items.product.reviews', function($q) {
                    $q->where('user_id', Auth::id());
                })
                ->first();
        @endphp

        @if($deliveredOrder)
            <div class="write-review-section mb-4">
                <a href="{{ route('review.create', ['product_id' => $product->id, 'order_id' => $deliveredOrder->id]) }}"
                   class="btn btn-solid hover-solid btn-animation">
                    <i class="ri-edit-line"></i> Write a Review
                </a>
            </div>
        @endif
    @endauth

    {{-- Reviews List --}}
    <div id="reviewsList">
        @forelse($product->approvedReviews as $review)
            @include('reviews.partials.review-item', ['review' => $review])
        @empty
            <div class="no-reviews text-center py-5">
                <i class="ri-chat-3-line" style="font-size: 48px; color: #ccc;"></i>
                <p class="text-muted mt-3">No reviews yet. Be the first to review this product!</p>
            </div>
        @endforelse
    </div>
</div>
