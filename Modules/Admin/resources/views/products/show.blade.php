@extends('admin::layouts.main')

@push('styles')
<style>
    .product-main-img {
        width: 100%;
        height: 380px;
        object-fit: contain;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px;
    }
    .product-thumb {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color .2s;
    }
    .product-thumb:hover,
    .product-thumb.active {
        border-color: #556ee6;
    }
    .product-thumb.primary-thumb {
        border-color: #556ee6;
    }
    .price-tag {
        font-size: 1.8rem;
        font-weight: 700;
        color: #556ee6;
    }
    .price-original {
        font-size: 1.1rem;
        color: #adb5bd;
        text-decoration: line-through;
    }
    .discount-badge {
        font-size: .75rem;
        vertical-align: middle;
    }
    .detail-label {
        font-size: .75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #74788d;
        margin-bottom: 2px;
    }
    .detail-value {
        font-size: .95rem;
        font-weight: 500;
        color: #343a40;
    }
    .description-body {
        font-size: .93rem;
        color: #495057;
        line-height: 1.7;
    }
    .description-body p { margin-bottom: .6rem; }
    .description-body ul, .description-body ol { padding-left: 1.4rem; }
    .description-body h1,.description-body h2,.description-body h3,
    .description-body h4,.description-body h5,.description-body h6 {
        margin-top: .8rem; margin-bottom: .4rem;
    }
    .attr-chip {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: .82rem;
        font-weight: 500;
        background: #eff2ff;
        color: #556ee6;
        border: 1px solid #c5cef8;
    }
    .section-divider {
        border-top: 1px solid #f0f0f0;
        margin: 1.4rem 0;
    }
    .no-image-box {
        height: 380px;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-xl-12">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Product Details</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size:.83rem;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($product->name, 40) }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm px-3">
                    <i class="mdi mdi-pencil me-1"></i> Edit
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="mdi mdi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="row g-4">

                    {{-- LEFT: Images --}}
                    <div class="col-md-5">
                        @php
                            $primaryImage = $product->images->where('is_primary', true)->first()
                                         ?? $product->images->first();
                        @endphp

                        @if($primaryImage)
                            <img id="main-product-img"
                                 src="{{ asset('uploads/'.$primaryImage->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="product-main-img">
                        @else
                            <div class="no-image-box">
                                <i class="mdi mdi-image-off" style="font-size:3rem;"></i>
                                <p class="mt-2 mb-0">No Image Available</p>
                            </div>
                        @endif

                        @if($product->images->count() > 1)
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            @foreach($product->images as $image)
                                <img src="{{ asset('uploads/'.$image->image_path) }}"
                                     alt=""
                                     class="product-thumb {{ $image->is_primary ? 'primary-thumb active' : '' }}"
                                     onclick="switchImage(this, '{{ asset('uploads/'.$image->image_path) }}')">
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- RIGHT: Info --}}
                    <div class="col-md-7">

                        {{-- Category & Badges --}}
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                            <span class="text-muted" style="font-size:.82rem; text-transform:uppercase; letter-spacing:.05em;">
                                {{ $product->category ? $product->category->name : 'Uncategorized' }}
                            </span>
                            <span class="badge {{ $product->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($product->is_featured)
                                <span class="badge bg-warning-subtle text-warning">Featured</span>
                            @endif
                        </div>

                        {{-- Name --}}
                        <h3 class="mb-3 fw-semibold">{{ $product->name }}</h3>

                        {{-- Price --}}
                        <div class="mb-3 d-flex align-items-baseline gap-2 flex-wrap">
                            @if($product->sale_price)
                                <span class="price-tag">₹{{ number_format($product->sale_price, 2) }}</span>
                                <span class="price-original">₹{{ number_format($product->price, 2) }}</span>
                                @php
                                    $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                @endphp
                                <span class="badge bg-danger discount-badge">{{ $discount }}% OFF</span>
                            @else
                                <span class="price-tag">₹{{ number_format($product->price, 2) }}</span>
                            @endif
                            <small class="text-muted">+ {{ $product->gst_percentage }}% GST</small>
                        </div>

                        <div class="section-divider"></div>

                        {{-- Quick Stats --}}
                        <div class="row g-3 mb-3">
                            <div class="col-6 col-lg-3">
                                <div class="detail-label">Stock</div>
                                @if($product->stock > 10)
                                    <div class="detail-value text-success"><i class="mdi mdi-check-circle me-1"></i>{{ $product->stock }}</div>
                                @elseif($product->stock > 0)
                                    <div class="detail-value text-warning"><i class="mdi mdi-alert me-1"></i>{{ $product->stock }}</div>
                                @else
                                    <div class="detail-value text-danger"><i class="mdi mdi-close-circle me-1"></i>Out</div>
                                @endif
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="detail-label">GST Rate</div>
                                <div class="detail-value">{{ $product->gst_percentage }}%</div>
                            </div>
                            @if($product->fabric_type)
                            <div class="col-6 col-lg-3">
                                <div class="detail-label">Fabric</div>
                                <div class="detail-value">{{ $product->fabric_type }}</div>
                            </div>
                            @endif
                            <div class="col-6 col-lg-3">
                                <div class="detail-label">SKU / Slug</div>
                                <div class="detail-value" style="word-break:break-all; font-size:.82rem;">{{ $product->slug }}</div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <div class="detail-label mb-2">Description</div>
                            @if($product->description)
                                <div class="description-body">
                                    {!! $product->description !!}
                                </div>
                            @else
                                <p class="text-muted fst-italic mb-0">No description provided.</p>
                            @endif
                        </div>

                        {{-- Attributes --}}
                        @if($product->attributes->isNotEmpty())
                        <div class="section-divider"></div>
                        <div class="detail-label mb-2">Attributes</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->attributes as $prodAttr)
                                <div>
                                    <span style="font-size:.78rem; color:#74788d;">{{ $prodAttr->attribute->name }}:</span>
                                    <span class="attr-chip ms-1">{{ $prodAttr->attributeValue->value }}</span>
                                    @if($prodAttr->stock !== null)
                                        <span class="text-muted" style="font-size:.78rem;"> (Qty: {{ $prodAttr->stock }})</span>
                                    @endif
                                    @if($prodAttr->price !== null)
                                        <span class="text-muted" style="font-size:.78rem;"> ₹{{ number_format($prodAttr->price, 2) }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- Meta Info Footer --}}
        <div class="card shadow-sm mt-3">
            <div class="card-body py-3 px-4">
                <div class="row g-2 text-muted" style="font-size:.82rem;">
                    <div class="col-auto me-4">
                        <i class="mdi mdi-calendar-plus me-1"></i>
                        <strong>Created:</strong> {{ $product->created_at->format('d M Y, h:i A') }}
                    </div>
                    <div class="col-auto">
                        <i class="mdi mdi-calendar-edit me-1"></i>
                        <strong>Updated:</strong> {{ $product->updated_at->format('d M Y, h:i A') }}
                    </div>
                    <div class="col-auto ms-auto">
                        <i class="mdi mdi-identifier me-1"></i>
                        <strong>ID:</strong> {{ $product->id }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchImage(thumb, src) {
        document.getElementById('main-product-img').src = src;
        document.querySelectorAll('.product-thumb').forEach(function(t) {
            t.classList.remove('active');
        });
        thumb.classList.add('active');
    }
</script>
@endpush
