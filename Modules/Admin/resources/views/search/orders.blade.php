@extends('admin::layouts.main')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4>Advanced Order Search</h4>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.search.orders') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="q" class="form-control" placeholder="Order ID, Customer..." value="{{ $query }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ ($filters['status'] ?? '') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ ($filters['status'] ?? '') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ ($filters['status'] ?? '') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All</option>
                        <option value="paid" {{ ($filters['payment_status'] ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ ($filters['payment_status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ ($filters['payment_status'] ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
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
            <a href="{{ route('admin.reports.orders.excel', $filters) }}" class="btn btn-success btn-sm">
                <i class="mdi mdi-file-excel"></i> Export Excel
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $order)
                    <tr>
                        <td><a href="{{ $order['url'] }}">#{{ $order['id'] }}</a></td>
                        <td>{{ $order['customer_name'] }}</td>
                        <td>₹{{ number_format($order['total'], 2) }}</td>
                        <td><span class="badge bg-primary">{{ ucfirst($order['status']) }}</span></td>
                        <td><span class="badge bg-{{ $order['payment_status'] == 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order['payment_status']) }}</span></td>
                        <td>{{ $order['created_at'] }}</td>
                        <td><a href="{{ $order['url'] }}" class="btn btn-sm btn-primary">View</a></td>
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
