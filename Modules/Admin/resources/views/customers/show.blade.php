@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-primary-subtle">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-primary">Customer Details</h5>
                            <p>Overview</p>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-4">
                            <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                         <div class="col-sm-12">
                             <h5 class="font-size-15 text-truncate">{{ $customer->name }}</h5>
                             <p class="text-muted mb-0 text-truncate">{{ $customer->email }}</p>
                         </div>
                    </div>
                    <div class="mt-4">
                         <div class="d-flex gap-2">
                             <span class="badge bg-primary"><i class="mdi mdi-cart me-1"></i> {{ $customer->orders_count }} Orders</span>
                             <span class="badge bg-success"><i class="mdi mdi-calendar me-1"></i> Joined {{ $customer->created_at->format('M Y') }}</span>
                         </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Contact Information</h4>
                 <div class="table-responsive">
                    <table class="table table-nowrap mb-0">
                        <tbody>
                            <tr>
                                <th scope="row">Full Name :</th>
                                <td>{{ $customer->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Email :</th>
                                <td>{{ $customer->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Joined Date :</th>
                                <td>{{ $customer->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Order History</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-body fw-bold">{{ $order->order_number }}</a>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>₹{{ number_format($order->total, 2) }}</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning">Pending</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge bg-info-subtle text-info">Processing</span>
                                    @elseif($order->status == 'shipped')
                                        <span class="badge bg-primary-subtle text-primary">Shipped</span>
                                    @elseif($order->status == 'delivered')
                                        <span class="badge bg-success-subtle text-success">Delivered</span>
                                     @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-dark-subtle text-body text-uppercase">{{ $order->payment_method }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No orders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                     {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
