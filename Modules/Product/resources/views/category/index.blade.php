@extends('layouts.master')

@section('title', 'All Categories')

@section('content')

<div class="breadcrumb-section">
    <div class="container">
        <h2>All Categories</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Categories</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-b-space">
    <div class="container">

        {{-- Category Grid --}}
        @if($categories->count() > 0)
        <div class="row g-3 g-md-4 mb-5">
            @foreach($categories as $category)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="transition: box-shadow .2s;">
                        <div class="mb-2" style="font-size:40px; color: var(--theme-color, #ff4c3b);">
                            <i class="ri-price-tag-3-line"></i>
                        </div>
                        <h6 class="mb-1 fw-600">{{ $category->name }}</h6>
                        @if($category->children->count() > 0)
                            <small class="text-muted">{{ $category->children->count() }} sub-categories</small>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Latest Products --}}
        @if($products->count() > 0)
        <div class="title1 section-t-space">
            <h4>Latest Arrivals</h4>
            <h2 class="title-inner1">New Products</h2>
        </div>
        <div class="row g-3 g-md-4 row-cols-2 row-cols-md-3 row-cols-xl-4 mt-0">
            @foreach($products as $product)
            <div>
                <x-product-card :product="$product" />
            </div>
            @endforeach
        </div>

        @if($products->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
        @endif
        @endif

    </div>
</section>

@endsection
