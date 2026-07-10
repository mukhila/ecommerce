@extends('layouts.master')

@section('title', $query ? 'Search results for "' . e($query) . '" | Jango Kidswear' : 'Search | Jango Kidswear')

@section('content')

<!-- breadcrumb start -->
<div class="breadcrumb-section">
    <div class="container">
        <h2>Search</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Search</li>
            </ol>
        </nav>
    </div>
</div>
<!-- breadcrumb end -->

<section class="section-b-space">
    <div class="container">

        {{-- Search form --}}
        <form action="{{ route('search.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text"
                       name="q"
                       class="form-control"
                       placeholder="Search for kids dresses, tops, party wear…"
                       value="{{ e($query) }}"
                       autofocus>
                <button class="btn btn-solid" type="submit">
                    <i class="ri-search-line me-1"></i> Search
                </button>
            </div>
        </form>

        @if($query !== '' && $products !== null)
            @if($products->total() > 0)
                <p class="text-muted mb-3">
                    {{ $products->total() }} result{{ $products->total() !== 1 ? 's' : '' }} for
                    <strong>"{{ e($query) }}"</strong>
                </p>

                <div class="product-wrapper-grid">
                    <div class="row g-3 g-md-4">
                        @foreach($products as $product)
                            <div class="col-xl-3 col-lg-4 col-6 col-grid-box">
                                <x-product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($products->hasPages())
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="ri-search-line" style="font-size:48px;color:#999;"></i>
                    <h4 class="mt-3">No products found for "{{ e($query) }}"</h4>
                    <p class="text-muted">Try different keywords or browse our categories.</p>
                    <a href="{{ route('category.index') }}" class="btn btn-solid mt-2">Browse Categories</a>
                </div>
            @endif
        @elseif($query === '')
            <div class="text-center py-5 text-muted">
                <i class="ri-search-line" style="font-size:48px;"></i>
                <p class="mt-3">Enter a keyword above to start searching.</p>
            </div>
        @endif

    </div>
</section>

@endsection
