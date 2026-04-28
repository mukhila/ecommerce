@extends('layouts.master')

@section('title', $category->name)

@push('styles')
<style>
.filter-bar { background: #f8f9fa; border-radius: 8px; padding: 12px 16px; margin-bottom: 24px; }
.subcategory-pills .badge { font-size: 13px; padding: 6px 14px; border-radius: 20px; text-decoration: none; }
.subcategory-pills .badge:hover { opacity: 0.85; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-section">
    <div class="container">
        <h2>{{ $category->name }}</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                @if($category->parent)
                    <li class="breadcrumb-item">
                        <a href="{{ route('category.show', $category->parent->slug) }}">{{ $category->parent->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-b-space ratio_asos">
    <div class="container">

        {{-- Sub-category pills --}}
        @if($category->children->count() > 0)
        <div class="subcategory-pills d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('category.show', $category->slug) }}"
               class="badge {{ !request('sub') ? 'bg-dark' : 'bg-light text-dark border' }}">
                All
            </a>
            @foreach($category->children as $child)
                @if($child->is_active)
                <a href="{{ route('category.show', $child->slug) }}"
                   class="badge bg-light text-dark border">
                    {{ $child->name }}
                </a>
                @endif
            @endforeach
        </div>
        @endif

        {{-- Filter bar --}}
        <div class="filter-bar d-flex align-items-center justify-content-between flex-wrap gap-2">
            <p class="mb-0 text-muted small">
                Showing <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
                of <strong>{{ $products->total() }}</strong> products
            </p>
            <form method="GET" action="{{ route('category.show', $category->slug) }}" class="d-flex align-items-center gap-2">
                <label for="sort" class="mb-0 small text-muted">Sort by:</label>
                <select name="sort" id="sort" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto;">
                    <option value="newest"     {{ $sort === 'newest'     ? 'selected' : '' }}>Newest</option>
                    <option value="price_asc"  {{ $sort === 'price_asc'  ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name_asc"   {{ $sort === 'name_asc'   ? 'selected' : '' }}>Name: A–Z</option>
                </select>
            </form>
        </div>

        {{-- Product Grid --}}
        @if($products->count() > 0)
            <div class="row g-3 g-md-4 row-cols-2 row-cols-md-3 row-cols-xl-4">
                @foreach($products as $product)
                    <div>
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="ri-inbox-line" style="font-size: 64px; color: #ccc;"></i>
                <h4 class="mt-3 text-muted">No products found</h4>
                <p class="text-muted">Check back soon or explore other categories.</p>
                <a href="{{ route('home') }}" class="btn btn-solid mt-2">Back to Home</a>
            </div>
        @endif

    </div>
</section>

@endsection
