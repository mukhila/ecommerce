@extends('layouts.master')

@section('title', $category->name)

@section('content')

<!-- breadcrumb start -->
<div class="breadcrumb-section">
    <div class="container">
        <h2>{{ $category->name }}</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- breadcrumb end -->

<!-- Category Slider Start -->
<section class="category-slider-section">
    <div class="container">
        <div class="product-category-slider no-arrow">
            @foreach($allCategories as $cat)
                <div>
                    <a href="{{ route('category.show', $cat->slug) }}" class="category-box {{ $cat->id == $category->id ? 'active' : '' }}">
                        <img src="{{ asset('frontassets/images/category/' . ($loop->iteration) . '.png') }}" class="img-fluid" alt="{{ $cat->name }}">
                        <h5>{{ $cat->name }}</h5>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Category Slider End -->

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
                            <div class="accordion collection-accordion" id="accordionPanelsStayOpenExample">

                                {{-- Categories Filter --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button pt-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne"
                                            aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                            Categories
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <ul class="collection-listing">
                                                @foreach($allCategories as $cat)
                                                    <li>
                                                        <a href="{{ route('category.show', $cat->slug) }}"
                                                           class="{{ $cat->id == $category->id ? 'text-primary fw-bold' : '' }}">
                                                            {{ $cat->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Dynamic Attribute Filters --}}
                                @foreach($attributesWithValues as $index => $attribute)
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapse{{ $index + 2 }}" aria-expanded="false"
                                            aria-controls="panelsStayOpen-collapse{{ $index + 2 }}">
                                            {{ $attribute->name }}
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapse{{ $index + 2 }}" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <ul class="collection-listing">
                                                @foreach($attribute->values as $value)
                                                    <li>
                                                        <div class="form-check">
                                                            <input class="form-check-input attribute-filter"
                                                                   type="checkbox"
                                                                   value="{{ $value->id }}"
                                                                   id="attr-{{ $value->id }}"
                                                                   {{ in_array($value->id, request('attributes', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="attr-{{ $value->id }}">
                                                                {{ $value->value }}
                                                            </label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Grid --}}
                <div class="collection-content col-xl-9 col-lg-8">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                {{-- Top Filter Bar --}}
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn d-flex justify-content-between align-items-center">
                                                    <span class="filter-show">
                                                        Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} Products
                                                    </span>
                                                    <div class="select-options">
                                                        <select class="form-select" id="sortBy">
                                                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                                            <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                                                            <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                                                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Products Grid --}}
                                    <div class="product-wrapper-grid">
                                        <div class="row margin-res g-3 g-md-4">
                                            @forelse($products as $product)
                                                <div class="col-xl-3 col-lg-4 col-6 col-grid-box">
                                                    <x-product-card :product="$product" />
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="text-center py-5">
                                                        <i class="ri-shopping-bag-line" style="font-size: 48px; color: #999;"></i>
                                                        <h4 class="mt-3">No products found in this category</h4>
                                                        <p class="text-muted">Try browsing other categories</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Handle attribute filter changes
        const attributeFilters = document.querySelectorAll('.attribute-filter');
        const sortBySelect = document.getElementById('sortBy');

        // Function to apply filters
        function applyFilters() {
            const selectedAttributes = [];
            attributeFilters.forEach(filter => {
                if (filter.checked) {
                    selectedAttributes.push(filter.value);
                }
            });

            const sortValue = sortBySelect.value;
            const currentUrl = new URL(window.location.href);

            // Clear existing attribute parameters
            currentUrl.searchParams.delete('attributes[]');

            // Add selected attributes
            if (selectedAttributes.length > 0) {
                selectedAttributes.forEach(attrId => {
                    currentUrl.searchParams.append('attributes[]', attrId);
                });
            }

            // Add sort parameter
            if (sortValue && sortValue !== 'latest') {
                currentUrl.searchParams.set('sort', sortValue);
            } else {
                currentUrl.searchParams.delete('sort');
            }

            // Remove page parameter to start from page 1
            currentUrl.searchParams.delete('page');

            // Redirect to new URL
            window.location.href = currentUrl.toString();
        }

        // Attach event listeners
        attributeFilters.forEach(filter => {
            filter.addEventListener('change', applyFilters);
        });

        sortBySelect.addEventListener('change', applyFilters);
    });
</script>
@endpush
