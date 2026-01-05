@extends('admin::layouts.main')

@section('title', 'Orders Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Orders Management</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <h2 class="mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2 class="mb-0 text-warning">{{ $stats['pending'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Processing</h5>
                    <h2 class="mb-0 text-info">{{ $stats['processing'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Shipped</h5>
                    <h2 class="mb-0 text-primary">{{ $stats['shipped'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Delivered</h5>
                    <h2 class="mb-0 text-success">{{ $stats['delivered'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Revenue</h5>
                    <h2 class="mb-0">₹{{ number_format($stats['total_revenue'], 0) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">All Orders</h4>
                        </div>
                        <div class="col-auto">
                            <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_number }}</strong></td>
                                        <td>
                                            {{ $order->user ? $order->user->name : $order->guest_name }}<br>
                                            <small class="text-muted">{{ $order->user ? $order->user->email : $order->guest_email }}</small>
                                        </td>
                                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $order->items->count() }} items</td>
                                        <td><strong>₹{{ number_format($order->total, 2) }}</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span><br>
                                            <small>{{ strtoupper($order->payment_method) }}</small>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                                @csrf
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                <i class="ri-eye-line"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <p class="text-muted mb-0">No orders found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($orders->hasPages())
                        <div class="mt-3">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
