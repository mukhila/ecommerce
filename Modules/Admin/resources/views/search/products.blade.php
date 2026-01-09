@extends('admin::layouts.main')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4>Product Search</h4>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.search.products') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="q" class="form-control" placeholder="Search products..." value="{{ $query }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stock_status" class="form-select">
                        <option value="">All Stock</option>
                        <option value="in_stock" {{ ($filters['stock_status'] ?? '') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ ($filters['stock_status'] ?? '') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ ($filters['stock_status'] ?? '') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="is_active" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ isset($filters['is_active']) && $filters['is_active'] == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ isset($filters['is_active']) && $filters['is_active'] == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <h5>Results: {{ count($results) }}</h5>
            <a href="{{ route('admin.reports.products.excel', $filters) }}" class="btn btn-success btn-sm">
                <i class="mdi mdi-file-excel"></i> Export Excel
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $product)
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['sku'] }}</td>
                        <td>{{ $product['category'] }}</td>
                        <td>₹{{ number_format($product['price'], 2) }}</td>
                        <td><span class="badge bg-{{ $product['stock'] > 10 ? 'success' : ($product['stock'] > 0 ? 'warning' : 'danger') }}">{{ $product['stock'] }}</span></td>
                        <td><span class="badge bg-{{ $product['is_active'] ? 'success' : 'secondary' }}">{{ $product['is_active'] ? 'Active' : 'Inactive' }}</span></td>
                        <td><a href="{{ $product['url'] }}" class="btn btn-sm btn-primary">Edit</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No results found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
