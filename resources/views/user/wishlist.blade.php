@extends('layouts.master')

@section('title', 'My Wishlist | Jango Kidswear')

@section('content')
<!-- breadcrumb start -->
<div class="breadcrumb-section">
    <div class="container">
        <h2>My Wishlist</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Wishlist</li>
            </ol>
        </nav>
    </div>
</div>
<!-- breadcrumb end -->

<section class="section-b-space">
    <div class="container">
        @if($wishlistItems->isEmpty())
            <div class="text-center py-5">
                <i class="ri-heart-line" style="font-size:48px;color:#999;"></i>
                <h4 class="mt-3">Your wishlist is empty</h4>
                <p class="text-muted">Save products you love and find them here.</p>
                <a href="{{ route('category.index') }}" class="btn btn-solid mt-2">Browse Products</a>
            </div>
        @else
            <div class="row g-3 g-md-4">
                @foreach($wishlistItems as $item)
                    @if($item->product)
                        <div class="col-xl-3 col-lg-4 col-6 col-grid-box">
                            <div class="product-box">
                                <x-product-card :product="$item->product" />
                                <div class="mt-2 text-center">
                                    <form action="{{ route('wishlist.destroy', $item->product_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="ri-heart-fill me-1"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
