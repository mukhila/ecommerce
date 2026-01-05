@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Product Details: {{ $product->name }}</h4>
                    <div>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary"><i class="mdi mdi-pencil me-1"></i> Edit Product</a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="product-img-box">
                            @if($product->images->where('is_primary', true)->first())
                                <img src="{{ Storage::url($product->images->where('is_primary', true)->first()->image_path) }}" alt="" class="img-fluid mx-auto d-block rounded">
                            @elseif($product->images->isNotEmpty())
                                <img src="{{ Storage::url($product->images->first()->image_path) }}" alt="" class="img-fluid mx-auto d-block rounded">
                            @else
                                <div class="text-center p-5 bg-light rounded">
                                    <i class="mdi mdi-image-off display-4 text-muted"></i>
                                    <p class="mt-3">No Image Available</p>
                                </div>
                            @endif
                        </div>

                        @if($product->images->count() > 1)
                        <div class="d-flex gap-2 mt-3 overflow-auto">
                            @foreach($product->images as $image)
                                <img src="{{ Storage::url($image->image_path) }}" alt="" class="avatar-md rounded border {{ $image->is_primary ? 'border-primary border-2' : '' }}" style="object-fit: cover;">
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="col-md-7">
                        <div class="ps-md-4 mt-4 mt-md-0">
                            <h5 class="font-size-14 text-muted text-uppercase">{{ $product->category ? $product->category->name : 'Uncategorized' }}</h5>
                            <h3 class="mb-3">{{ $product->name }}</h3>
                            
                            <div class="text-muted mb-4">
                                <span class="badge {{ $product->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} font-size-12 me-2">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($product->is_featured)
                                    <span class="badge bg-warning-subtle text-warning font-size-12">Featured</span>
                                @endif
                            </div>

                            <h2 class="mb-4">
                                @if($product->sale_price)
                                    <span class="text-success">{{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-muted text-decoration-line-through font-size-16 ms-2">{{ number_format($product->price, 2) }}</span>
                                @else
                                    {{ number_format($product->price, 2) }}
                                @endif
                            </h2>

                            <p class="text-muted mb-4">{{ $product->description }}</p>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Slug</p>
                                    <h6 class="font-size-14">{{ $product->slug }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Stock Status</p>
                                    @if($product->stock > 0)
                                        <h6 class="text-success font-size-14"><i class="mdi mdi-check-circle me-1"></i> In Stock ({{ $product->stock }})</h6>
                                    @else
                                        <h6 class="text-danger font-size-14"><i class="mdi mdi-close-circle me-1"></i> Out of Stock</h6>
                                    @endif
                                </div>
                            </div>
                            
                            @if($product->attributes->isNotEmpty())
                            <hr class="my-4">
                            <h5 class="font-size-15 mb-3">Attributes</h5>
                            <div class="row">
                                @foreach($product->attributes as $prodAttr)
                                    <div class="col-md-6 mb-3">
                                        <p class="text-muted mb-1">{{ $prodAttr->attribute->name }}</p>
                                        <h6 class="font-size-14 bg-light d-inline-block px-3 py-1 rounded">{{ $prodAttr->attributeValue->value }}</h6>
                                    </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
