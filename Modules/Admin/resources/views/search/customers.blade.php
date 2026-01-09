@extends('admin::layouts.main')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4>Customer Search</h4>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.search.customers') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="q" class="form-control" placeholder="Search by name, email, mobile..." value="{{ $query }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" placeholder="From Date" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" placeholder="To Date" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <input type="number" name="min_orders" class="form-control" placeholder="Min Orders" value="{{ $filters['min_orders'] ?? '' }}">
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
            <a href="{{ route('admin.reports.customers.excel', $filters) }}" class="btn btn-success btn-sm">
                <i class="mdi mdi-file-excel"></i> Export Excel
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $customer)
                    <tr>
                        <td>{{ $customer['name'] }}</td>
                        <td>{{ $customer['email'] }}</td>
                        <td>{{ $customer['mobile'] }}</td>
                        <td><span class="badge bg-primary">{{ $customer['orders_count'] }}</span></td>
                        <td>₹{{ number_format($customer['total_spent'], 2) }}</td>
                        <td>{{ $customer['created_at'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No results found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
