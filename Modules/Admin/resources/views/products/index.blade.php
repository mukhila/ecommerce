@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h4 class="card-title">Products</h4>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="mdi mdi-plus-circle me-1"></i> Create Product
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->images->isNotEmpty())
                                        <img src="{{ Storage::url($product->images->first()->image_path) }}" alt="{{ $product->name }}" class="rounded avatar-sm">
                                    @else
                                        <div class="avatar-sm">
                                            <span class="avatar-title rounded bg-light text-secondary">No Img</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <h5 class="text-truncate font-size-14 mb-1"><a href="#" class="text-dark">{{ $product->name }}</a></h5>
                                    <p class="text-muted mb-0">{{ $product->slug }}</p>
                                </td>
                                <td>{{ $product->category ? $product->category->name : '-' }}</td>
                                <td>
                                    @if($product->sale_price)
                                        <del class="text-muted">{{ number_format($product->price, 2) }}</del> <br>
                                        <span class="text-success fw-bold">{{ number_format($product->sale_price, 2) }}</span>
                                    @else
                                        {{ number_format($product->price, 2) }}
                                    @endif
                                </td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge bg-warning-subtle text-warning">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-primary text-white"><i class="mdi mdi-eye"></i></a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info text-white"><i class="mdi mdi-pencil"></i></a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-trash-can"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No products found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                   {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
