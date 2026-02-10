@extends('layouts.master')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Dashboard</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--  dashboard section start -->
    <section class="dashboard-section section-b-space user-dashboard-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="dashboard-sidebar">
                        <button class="btn back-btn">
                            <i class="ri-close-line"></i><span>Close</span>
                        </button>
                        <div class="profile-top">
                            <div class="profile-top-box">
                                <div class="profile-image">
                                    <div class="position-relative">
                                        <div class="user-round">
                                            <h4>{{ substr(Auth::user()->name, 0, 1) }}</h4>
                                        </div>
                                        <div class="user-icon"><input type="file" accept="image/*"><i class="ri-image-edit-line d-lg-block d-none"></i><i class="ri-pencil-fill edit-icon d-lg-none"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-detail">
                                <h5>{{ Auth::user()->name }}</h5>
                                <h6>{{ Auth::user()->email }}</h6>
                            </div>
                        </div>
                        <div class="faq-tab">
                            <ul id="pills-tab" role="tablist" class="nav nav-tabs">
                                <li role="presentation" class="nav-item">
                                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab">
                                        <i class="ri-home-line"></i> dashboard
                                    </button>
                                </li>
                                <li role="presentation" class="nav-item">
                                    <button class="nav-link" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification-tab-pane" type="button" role="tab">
                                        <i class="ri-notification-line"></i> Notifications
                                    </button>
                                </li>
                                <li role="presentation" class="nav-item">
                                    <button class="nav-link" id="bank-details-tab" data-bs-toggle="tab" data-bs-target="#bank-details-tab-pane" type="button" role="tab">
                                        <i class="ri-bank-line"></i> Bank Details
                                    </button>
                                </li>
                                <li role="presentation" class="nav-item">
                                    <button class="nav-link" id="order-tab" data-bs-toggle="tab" data-bs-target="#order-tab-pane" type="button" role="tab">
                                        <i class="ri-file-text-line"></i>My Orders
                                    </button>
                                </li>
                                <li role="presentation" class="nav-item">
                                    <button class="nav-link" id="refund-tab" data-bs-toggle="tab" data-bs-target="#refund-tab-pane" type="button" role="tab">
                                        <i class="ri-money-dollar-circle-line"></i> Refund History
                                    </button>
                                </li>
                                <li role="presentation" class="nav-item">
                                    <button class="nav-link" id="address" data-bs-toggle="tab" data-bs-target="#address-tab-pane" type="button" role="tab">
                                        <i class="ri-map-pin-line"></i> Saved Address
                                    </button>
                                </li>
                                <li role="presentation" class="nav-item">
                                    <button class="nav-link" id="profile" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab">
                                        <i class="ri-user-line"></i> Change Password
                                    </button>
                                </li>
                                
                                <li role="presentation" class="nav-item logout-cls">
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn loagout-btn">
                                        <i class="ri-logout-box-r-line"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <button class="show-btn btn d-lg-none d-block">Show Menu</button>
                    <div class="faq-content tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel">
                            <div class="counter-section">
                                <div class="welcome-msg">
                                    <h4>Hello, {{ Auth::user()->name }} !</h4>
                                    <p>From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="counter-box">
                                            <img src="{{ asset('frontassets/images/dashboard/balance.png') }}" alt="" class="img-fluid">
                                            <div>
                                                <h3>₹{{ number_format($totalSpent, 2) }}</h3>
                                                <h5>Total Spent</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="counter-box">
                                            <img src="{{ asset('frontassets/images/dashboard/order.png') }}" alt="" class="img-fluid">
                                            <div>
                                                <h3>{{ $totalOrders }}</h3>
                                                <h5>Total Orders</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="counter-box">
                                            <img src="{{ asset('frontassets/images/dashboard/points.png') }}" alt="" class="img-fluid">
                                            <div>
                                                <h3>{{ $deliveredOrders }}</h3>
                                                <h5>Delivered</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-account box-info">
                                    <div class="box-head">
                                        <h4>Account Information</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="box">
                                                <ul class="box-content">
                                                    <li class="w-100">
                                                        <h6>Full Name: {{ Auth::user()->name }}</h6>
                                                    </li>
                                                    <li class="w-100">
                                                        <h6>Email: {{ Auth::user()->email }}</h6>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Other tabs placeholders for brevity, can render them fully if needed -->
                        <div class="tab-pane fade" id="notification-tab-pane" role="tabpanel">
                             <h3>Notifications</h3>
                             @if($notifications->count() > 0)
                                <div class="d-flex justify-content-end mb-2">
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-solid">Mark All as Read</button>
                                    </form>
                                </div>
                                <div class="list-group">
                                    @foreach($notifications as $notification)
                                        <div class="list-group-item list-group-item-action flex-column align-items-start {{ $notification->read_at ? '' : 'active' }}">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h5>
                                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">{{ $notification->data['message'] ?? 'No detail available.' }}</p>
                                            <div class="mt-2">
                                                @if(!$notification->read_at)
                                                    <a href="{{ route('notifications.markRead', $notification->id) }}" class="btn btn-sm btn-light">Mark as Read</a>
                                                @endif
                                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    {{ $notifications->links() }}
                                </div>
                             @else
                                <p>You have no notifications.</p>
                             @endif
                        </div>
                        <div class="tab-pane fade" id="bank-details-tab-pane" role="tabpanel">
                             <h3>Bank Details</h3>
                             <!-- Form content -->
                        </div>
                         <div class="tab-pane fade" id="order-tab-pane" role="tabpanel">
                             <h3>My Orders</h3>
                             @if($recentOrders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentOrders as $order)
                                                <tr>
                                                    <td>{{ $order->order_number }}</td>
                                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td>₹{{ number_format($order->total, 2) }}</td>
                                                    <td>
                                                        <a href="{{ route('order.tracking', $order) }}" class="btn btn-sm btn-primary">
                                                            <i class="ri-map-pin-line"></i> Track
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                             @else
                                <p class="text-muted">You have no orders yet.</p>
                             @endif
                        </div>
                         <div class="tab-pane fade" id="refund-tab-pane" role="tabpanel">
                             <h3>Refund History</h3>
                        </div>
                         <div class="tab-pane fade" id="address-tab-pane" role="tabpanel">
                             <h3>Saved Address</h3>
                        </div>
                        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel">
                             <h3>Profile</h3>
                             <div class="box-account box-info">
                                <div class="box-head">
                                    <h4>Change Password</h4>
                                </div>
                                <div class="row">
                                    <form class="theme-form" action="" method="POST">
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <div class="form-box">
                                                    <label for="current_password" class="form-label">Current Password</label>
                                                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current Password" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <div class="form-box">
                                                    <label for="new_password" class="form-label">New Password</label>
                                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-box">
                                                    <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm Password" required="">
                                                </div>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <button type="submit" class="btn btn-solid w-auto">Change Password</button>
                                            </div>
                                        </div>
                                    </form>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--  dashboard section end -->
@endsection
