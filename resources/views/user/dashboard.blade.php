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
                                            @if(Auth::user()->avatar)
                                                <img src="{{ asset('uploads/avatars/' . Auth::user()->avatar) }}"
                                                     alt="{{ Auth::user()->name }}"
                                                     class="img-fluid rounded-circle"
                                                     style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                <h4>{{ substr(Auth::user()->name, 0, 1) }}</h4>
                                            @endif
                                        </div>
                                        <div class="user-icon">
                                            <form id="avatarForm" action="{{ route('dashboard.avatar.update') }}" method="POST" enctype="multipart/form-data" style="display:none;">
                                                @csrf
                                                <input type="file" id="avatarInput" name="avatar" accept="image/*">
                                            </form>
                                            <label for="avatarInput" style="cursor:pointer;margin:0;">
                                                <i class="ri-image-edit-line d-lg-block d-none"></i>
                                                <i class="ri-pencil-fill edit-icon d-lg-none"></i>
                                            </label>
                                        </div>
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
                                    <button class="nav-link" id="wishlist-tab" data-bs-toggle="tab" data-bs-target="#wishlist-tab-pane" type="button" role="tab">
                                        <i class="ri-heart-line"></i> Wishlist
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
                                @if(!Auth::user()->hasVerifiedEmail())
                                <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
                                    <i class="ri-mail-unread-line fs-5"></i>
                                    <div>
                                        Your email address is not verified.
                                        <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 align-baseline fw-semibold">Resend verification email</button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                                @if(session('verification_resent'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    Verification email sent. Please check your inbox.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                @endif
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
                         <div class="tab-pane fade" id="order-tab-pane" role="tabpanel">
                             <h3>My Orders</h3>

                             @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                             @endif
                             @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                             @endif

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
                                                    <td class="d-flex gap-1 flex-wrap">
                                                        <a href="{{ route('order.tracking', $order) }}" class="btn btn-sm btn-primary">
                                                            <i class="ri-map-pin-line"></i> Track
                                                        </a>
                                                        @if(in_array($order->status, ['pending', 'processing']))
                                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#cancelModal{{ $order->id }}">
                                                                <i class="ri-close-circle-line"></i> Cancel
                                                            </button>
                                                            <!-- Cancel confirmation modal -->
                                                            <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Cancel Order {{ $order->order_number }}</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        <form method="POST" action="{{ route('order.cancel', $order) }}">
                                                                            @csrf
                                                                            <div class="modal-body">
                                                                                <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                                                                                <div class="mb-3">
                                                                                    <label for="reason{{ $order->id }}" class="form-label">Reason (optional)</label>
                                                                                    <textarea class="form-control" id="reason{{ $order->id }}" name="reason" rows="2" maxlength="500" placeholder="Let us know why you're cancelling..."></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                                                                                <button type="submit" class="btn btn-danger">Yes, Cancel Order</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $recentOrders->appends(request()->except('orders_page'))->links() }}
                                </div>
                             @else
                                <p class="text-muted">You have no orders yet.</p>
                             @endif
                        </div>
                        <div class="tab-pane fade" id="wishlist-tab-pane" role="tabpanel">
                            <h3>My Wishlist</h3>
                            @if(isset($wishlistItems) && $wishlistItems->isNotEmpty())
                                <div class="row g-3">
                                    @foreach($wishlistItems as $item)
                                        @if($item->product)
                                            <div class="col-xl-3 col-md-4 col-6">
                                                <x-product-card :product="$item->product" />
                                                <div class="text-center mt-1">
                                                    <form action="{{ route('wishlist.destroy', $item->product_id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="ri-heart-fill me-1"></i> Remove
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Your wishlist is empty. <a href="{{ route('category.index') }}">Browse products</a> to add some.</p>
                            @endif
                        </div>

                         <div class="tab-pane fade" id="refund-tab-pane" role="tabpanel">
                             <h3>Refund History</h3>
                             <p class="text-muted mb-3">Orders you cancelled after payment. Refunds are processed within 5–7 business days to your original payment method.</p>
                             @if($refundOrders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Cancelled On</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Refund Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($refundOrders as $order)
                                                <tr>
                                                    <td>{{ $order->order_number }}</td>
                                                    <td>{{ $order->updated_at->format('d M Y') }}</td>
                                                    <td>₹{{ number_format($order->total, 2) }}</td>
                                                    <td>{{ strtoupper($order->payment_method ?? 'N/A') }}</td>
                                                    <td>
                                                        @if($order->payment_status === 'refunded')
                                                            <span class="badge bg-success">Refunded</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">Processing (5–7 days)</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                             @else
                                <p class="text-muted">No refund history found.</p>
                             @endif
                        </div>
                         <div class="tab-pane fade" id="address-tab-pane" role="tabpanel">
                             <h3>Saved Address</h3>

                             @if(session('address_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('address_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                             @endif

                             <form class="theme-form" action="{{ route('dashboard.address.save') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-box">
                                            <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                            <input type="text" name="address_line1" class="form-control @error('address_line1') is-invalid @enderror"
                                                value="{{ old('address_line1', $savedAddress->address_line1 ?? '') }}"
                                                placeholder="House / Flat / Block No., Street name" required>
                                            @error('address_line1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-box">
                                            <label class="form-label">Address Line 2</label>
                                            <input type="text" name="address_line2" class="form-control @error('address_line2') is-invalid @enderror"
                                                value="{{ old('address_line2', $savedAddress->address_line2 ?? '') }}"
                                                placeholder="Area, Colony, Locality (optional)">
                                            @error('address_line2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-box">
                                            <label class="form-label">City <span class="text-danger">*</span></label>
                                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                                value="{{ old('city', $savedAddress->city ?? '') }}"
                                                placeholder="City" required>
                                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-box">
                                            <label class="form-label">State <span class="text-danger">*</span></label>
                                            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                                                value="{{ old('state', $savedAddress->state ?? '') }}"
                                                placeholder="State" required>
                                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-box">
                                            <label class="form-label">Postal Code <span class="text-danger">*</span></label>
                                            <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror"
                                                value="{{ old('postal_code', $savedAddress->postal_code ?? '') }}"
                                                placeholder="PIN / ZIP code" required>
                                            @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-box">
                                            <label class="form-label">Country <span class="text-danger">*</span></label>
                                            <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                                                value="{{ old('country', $savedAddress->country ?? 'India') }}"
                                                placeholder="Country" required>
                                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-solid w-auto">Save Address</button>
                                    </div>
                                </div>
                             </form>
                        </div>
                        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel">
                             <h3>Profile</h3>
                             <div class="box-account box-info">
                                <div class="box-head">
                                    <h4>Set / Change Password</h4>
                                </div>

                                @if(session('password_success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('password_success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <p class="text-muted small mb-3">
                                    Since your account uses phone OTP for sign-in, you can optionally set a password here.
                                </p>

                                <div class="row">
                                    <form class="theme-form" action="{{ route('dashboard.password.update') }}" method="POST">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-box">
                                                    <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                                        id="new_password" name="new_password"
                                                        placeholder="Min 8 chars, upper+lower+number" required>
                                                    @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-box">
                                                    <label for="new_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control"
                                                        id="new_password_confirmation" name="new_password_confirmation"
                                                        placeholder="Repeat new password" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-solid w-auto">Save Password</button>
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
@push('scripts')
<script>
    const params = new URLSearchParams(window.location.search);

    if (params.has('orders_page')) {
        const t = document.getElementById('order-tab');
        if (t) t.click();
    }

    @if(session('address_success'))
    (function () { const t = document.getElementById('address'); if (t) t.click(); })();
    @endif

    @if(session('password_success') || $errors->has('new_password'))
    (function () { const t = document.getElementById('profile'); if (t) t.click(); })();
    @endif

    // Submit avatar form immediately on file selection
    const avatarInput = document.getElementById('avatarInput');
    if (avatarInput) {
        avatarInput.addEventListener('change', function () {
            if (this.files.length) document.getElementById('avatarForm').submit();
        });
    }
</script>
@endpush
@endsection
