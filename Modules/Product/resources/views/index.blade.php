@extends('layouts.master')

@section('title', $pageTitle . ' | Jango Kidswear')
@section('meta_description', 'Shop ' . $pageTitle . ' at Jango Kidswear. Browse premium kids fashion at affordable prices.')
@section('canonical', url()->current())

@section('content')

<!-- breadcrumb start -->
<div class="breadcrumb-section">
    <div class="container">
        <h2>{{ $pageTitle }}</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- breadcrumb end -->

<!-- Filter pills -->
<div class="py-3 border-bottom bg-light">
    <div class="container">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <span class="text-muted small me-1">Filter:</span>
            <a href="{{ route('products.index', array_filter(['sort' => $sort !== 'latest' ? $sort : null])) }}"
               class="btn btn-sm {{ !$filter ? 'btn-solid' : 'btn-outline-secondary' }}">All</a>
            <a href="{{ route('products.index', array_filter(['filter' => 'new', 'sort' => $sort !== 'latest' ? $sort : null])) }}"
               class="btn btn-sm {{ $filter === 'new' ? 'btn-solid' : 'btn-outline-secondary' }}">New Arrivals</a>
            <a href="{{ route('products.index', array_filter(['filter' => 'sale', 'sort' => $sort !== 'latest' ? $sort : null])) }}"
               class="btn btn-sm {{ $filter === 'sale' ? 'btn-solid' : 'btn-outline-secondary' }}">Sale</a>
            <a href="{{ route('products.index', array_filter(['filter' => 'featured', 'sort' => $sort !== 'latest' ? $sort : null])) }}"
               class="btn btn-sm {{ $filter === 'featured' ? 'btn-solid' : 'btn-outline-secondary' }}">Featured</a>
        </div>
    </div>
</div>
<!-- Filter pills end -->

<!-- section start -->
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">

                {{-- Left Sidebar Filters --}}
                <div class="col-xl-3 col-lg-4 collection-filter">
                    <div class="collection-filter-block">
                        <button class="collection-mobile-back filter-back">
                            <i class="ri-arrow-left-s-line"></i>
                            <span>back</span>
                        </button>

                        <div class="collection-collapse-block open">
                            <div class="accordion collection-accordion" id="accordionPanels">

                                {{-- Categories --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button pt-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#filter-categories"
                                            aria-expanded="true" aria-controls="filter-categories">
                                            Categories
                                        </button>
                                    </h2>
                                    <div id="filter-categories" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <ul class="collection-listing">
                                                @foreach($categories as $cat)
                                                    <li>
                                                        <a href="{{ route('category.show', $cat->slug) }}">
                                                            {{ $cat->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Price Range --}}
                                @if($priceRange && $priceRange->max_price > 0)
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#filter-price" aria-expanded="true"
                                            aria-controls="filter-price">
                                            Price Range
                                        </button>
                                    </h2>
                                    <div id="filter-price" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <div class="d-flex gap-2 align-items-center mb-2">
                                                <input type="number" id="price-min" class="form-control form-control-sm"
                                                    placeholder="Min" min="0"
                                                    value="{{ $minPrice ?? '' }}"
                                                    style="width:80px;">
                                                <span>—</span>
                                                <input type="number" id="price-max" class="form-control form-control-sm"
                                                    placeholder="Max" min="0"
                                                    value="{{ $maxPrice ?? '' }}"
                                                    style="width:80px;">
                                                <button id="price-filter-apply" class="btn btn-sm btn-solid">Go</button>
                                            </div>
                                            <small class="text-muted">
                                                ₹{{ number_format($priceRange->min_price, 0) }} –
                                                ₹{{ number_format($priceRange->max_price, 0) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Grid --}}
                <div class="collection-content col-xl-9 col-lg-8">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="collection-product-wrapper">

                                    {{-- Top bar: count + sort --}}
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn d-flex justify-content-between align-items-center">
                                                    <span class="filter-show">
                                                        @if($products->total() > 0)
                                                            Showing {{ $products->firstItem() }} – {{ $products->lastItem() }}
                                                            of {{ $products->total() }} Products
                                                        @else
                                                            No products found
                                                        @endif
                                                    </span>
                                                    <div class="select-options">
                                                        <select class="form-select" id="sortBy">
                                                            <option value="latest"     {{ $sort === 'latest'     ? 'selected' : '' }}>Latest</option>
                                                            <option value="price-low"  {{ $sort === 'price-low'  ? 'selected' : '' }}>Price: Low to High</option>
                                                            <option value="price-high" {{ $sort === 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                                                            <option value="name"       {{ $sort === 'name'       ? 'selected' : '' }}>Name: A to Z</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Products --}}
                                    <div class="product-wrapper-grid">
                                        <div class="row margin-res g-3 g-md-4">
                                            @forelse($products as $product)
                                                <div class="col-xl-3 col-lg-4 col-6 col-grid-box">
                                                    <x-product-card :product="$product" />
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="text-center py-5">
                                                        <i class="ri-shopping-bag-line" style="font-size:48px;color:#999;"></i>
                                                        <h4 class="mt-3">No products found</h4>
                                                        <p class="text-muted">Try adjusting your filters or browse all products.</p>
                                                        <a href="{{ route('products.index') }}" class="btn btn-solid mt-2">View All Products</a>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    {{-- Pagination --}}
                                    @if($products->hasPages())
                                        <div class="product-pagination">
                                            <div class="theme-paggination-block">
                                                <div class="row">
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <nav aria-label="Page navigation">
                                                            {{ $products->links() }}
                                                        </nav>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- section end -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sortBySelect = document.getElementById('sortBy');
    sortBySelect.addEventListener('change', function () {
        const url = new URL(window.location.href);
        if (this.value !== 'latest') {
            url.searchParams.set('sort', this.value);
        } else {
            url.searchParams.delete('sort');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });

    const priceApplyBtn = document.getElementById('price-filter-apply');
    if (priceApplyBtn) {
        priceApplyBtn.addEventListener('click', function () {
            const url = new URL(window.location.href);
            const minVal = document.getElementById('price-min').value;
            const maxVal = document.getElementById('price-max').value;
            minVal !== '' ? url.searchParams.set('min_price', minVal) : url.searchParams.delete('min_price');
            maxVal !== '' ? url.searchParams.set('max_price', maxVal) : url.searchParams.delete('max_price');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    }
});
</script>
@endpush
