@extends('admin::layouts.main')

@section('title', 'Dashboard')

@push('styles')
<style>
    .dashboard-card {
        border: 1px solid #eef0f4;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(16, 24, 40, 0.04);
    }

    .metric-card {
        min-height: 132px;
    }

    .metric-icon {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 24px;
    }

    .metric-value {
        font-size: 28px;
        line-height: 1.1;
        letter-spacing: 0;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        display: inline-block;
        border-radius: 50%;
    }

    .table-fixed {
        table-layout: fixed;
    }

    .truncate-cell {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .dashboard-chart {
        min-height: 320px;
    }
</style>
@endpush

@section('content')
@php
    $money = fn ($value) => '&#8377;' . number_format((float) $value, 2);
    $count = fn ($value) => number_format((int) $value);
    $orderBadge = [
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger',
    ];
    $paymentBadge = [
        'pending' => 'warning',
        'paid' => 'success',
        'successful' => 'success',
        'failed' => 'danger',
        'refunded' => 'secondary',
    ];
@endphp

<div class="row mb-4">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-sm-0 font-size-18">Ecommerce Dashboard</h4>
                <p class="text-muted mb-0 mt-1">Live store overview for products, revenue, orders, payments, and stock.</p>
            </div>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    @if($data['canViewFinancial'])
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card metric-card border-start border-success border-4 mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="text-muted mb-2">30 Day Revenue</p>
                            <h3 class="metric-value mb-2">{!! $money($data['revenue']['total_revenue'] ?? 0) !!}</h3>
                            <span class="badge bg-{{ ($data['revenue']['growth_percentage'] ?? 0) >= 0 ? 'success' : 'danger' }}-subtle text-{{ ($data['revenue']['growth_percentage'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                {{ ($data['revenue']['growth_percentage'] ?? 0) >= 0 ? '+' : '' }}{{ $data['revenue']['growth_percentage'] ?? 0 }}%
                            </span>
                            <span class="text-muted ms-1">vs previous period</span>
                        </div>
                        <span class="metric-icon bg-success-subtle text-success">
                            <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card metric-card border-start border-primary border-4 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted mb-2">Total Orders</p>
                        <h3 class="metric-value mb-2">{{ $count($data['orderStats']['total'] ?? 0) }}</h3>
                        <span class="text-muted">{{ $count($data['orderStats']['today'] ?? 0) }} placed today</span>
                    </div>
                    <span class="metric-icon bg-primary-subtle text-primary">
                        <iconify-icon icon="solar:cart-large-4-bold-duotone"></iconify-icon>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card metric-card border-start border-warning border-4 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted mb-2">Pending Orders</p>
                        <h3 class="metric-value mb-2">{{ $count($data['orderStats']['pending'] ?? 0) }}</h3>
                        <span class="text-muted">{{ $count($data['orderStats']['payment_pending'] ?? 0) }} awaiting payment</span>
                    </div>
                    <span class="metric-icon bg-warning-subtle text-warning">
                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card metric-card border-start border-info border-4 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted mb-2">Products</p>
                        <h3 class="metric-value mb-2">{{ $count($data['productStats']['total'] ?? 0) }}</h3>
                        <span class="text-muted">{{ $count($data['productStats']['active'] ?? 0) }} active in {{ $count($data['productStats']['categories'] ?? 0) }} categories</span>
                    </div>
                    <span class="metric-icon bg-info-subtle text-info">
                        <iconify-icon icon="solar:box-bold-duotone"></iconify-icon>
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if($data['canViewFinancial'])
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card metric-card border-start border-secondary border-4 mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="text-muted mb-2">Average Order Value</p>
                            <h3 class="metric-value mb-2">{!! $money($data['revenue']['average_order_value'] ?? 0) !!}</h3>
                            <span class="text-muted">{{ $count($data['revenue']['order_count'] ?? 0) }} paid orders in range</span>
                        </div>
                        <span class="metric-icon bg-secondary-subtle text-secondary">
                            <iconify-icon icon="solar:tag-price-bold-duotone"></iconify-icon>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card metric-card border-start border-danger border-4 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted mb-2">Stock Alerts</p>
                        <h3 class="metric-value mb-2">{{ $count(count($data['lowStock'] ?? []) + count($data['outOfStock'] ?? [])) }}</h3>
                        <span class="text-muted">{{ $count(count($data['outOfStock'] ?? [])) }} out of stock</span>
                    </div>
                    <span class="metric-icon bg-danger-subtle text-danger">
                        <iconify-icon icon="solar:danger-circle-bold-duotone"></iconify-icon>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card metric-card border-start border-dark border-4 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted mb-2">Customers</p>
                        <h3 class="metric-value mb-2">{{ $count($data['customerStats']['total'] ?? 0) }}</h3>
                        <span class="text-muted">{{ $count($data['customerStats']['new_30_days'] ?? 0) }} joined in 30 days</span>
                    </div>
                    <span class="metric-icon bg-dark-subtle text-dark">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card metric-card border-start border-secondary border-4 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted mb-2">Fulfillment</p>
                        <h3 class="metric-value mb-2">{{ $count(($data['orderStats']['processing'] ?? 0) + ($data['orderStats']['shipped'] ?? 0)) }}</h3>
                        <span class="text-muted">{{ $count($data['orderStats']['delivered'] ?? 0) }} delivered total</span>
                    </div>
                    <span class="metric-icon bg-secondary-subtle text-secondary">
                        <iconify-icon icon="solar:delivery-bold-duotone"></iconify-icon>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    @if($data['canViewFinancial'])
        <div class="col-xl-8">
            <div class="card dashboard-card mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h5 class="card-title mb-1">Revenue And Orders</h5>
                            <p class="text-muted mb-0">{{ $data['dateRange']['start']->format('d M Y') }} to {{ $data['dateRange']['end']->format('d M Y') }}</p>
                        </div>
                        <a href="{{ route('admin.analytics.revenue') }}" class="btn btn-sm btn-outline-primary">Revenue Report</a>
                    </div>
                    <div id="revenue-chart" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
    @endif

    <div class="col-xl-{{ $data['canViewFinancial'] ? '4' : '12' }}">
        <div class="card dashboard-card mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-1">Payment Mix</h5>
                        <p class="text-muted mb-0">Paid order distribution</p>
                    </div>
                    <a href="{{ route('admin.orders.index', ['payment_status' => 'paid']) }}" class="btn btn-sm btn-outline-primary">View Paid</a>
                </div>
                <div id="payment-chart" class="dashboard-chart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-4">
        <div class="card dashboard-card h-100 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="card-title mb-0">Order Status</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">All Orders</a>
                </div>
                @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                    @php
                        $value = $data['orderStats'][$status] ?? 0;
                        $totalOrders = max((int) ($data['orderStats']['total'] ?? 0), 1);
                        $percent = round(($value / $totalOrders) * 100);
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-capitalize">
                                <span class="status-dot bg-{{ $orderBadge[$status] }}"></span>
                                {{ $status }}
                            </span>
                            <strong>{{ $count($value) }}</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-{{ $orderBadge[$status] }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card dashboard-card h-100 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-1">Pending Orders</h5>
                        <p class="text-muted mb-0">Orders waiting for action or fulfillment.</p>
                    </div>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-warning">Pending List</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-fixed align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 18%;">Order</th>
                                <th style="width: 26%;">Customer</th>
                                <th style="width: 14%;">Items</th>
                                @if($data['canViewFinancial'])
                                    <th style="width: 16%;" class="text-end">Total</th>
                                @endif
                                <th style="width: 14%;">Payment</th>
                                <th style="width: 12%;" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['pendingOrders'] as $order)
                                <tr>
                                    <td class="truncate-cell">
                                        <strong>{{ $order->order_number }}</strong>
                                        <div class="text-muted small">{{ $order->created_at->format('d M, h:i A') }}</div>
                                    </td>
                                    <td class="truncate-cell">
                                        {{ $order->user->name ?? $order->guest_name ?? 'Guest Customer' }}
                                        <div class="text-muted small truncate-cell">{{ $order->user->email ?? $order->guest_email ?? 'No email' }}</div>
                                    </td>
                                    <td>{{ $order->items->sum('quantity') }} units</td>
                                    @if($data['canViewFinancial'])
                                        <td class="text-end fw-semibold">{!! $money($order->total) !!}</td>
                                    @endif
                                    <td>
                                        <span class="badge bg-{{ $paymentBadge[$order->payment_status] ?? 'secondary' }}-subtle text-{{ $paymentBadge[$order->payment_status] ?? 'secondary' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $data['canViewFinancial'] ? 6 : 5 }}" class="text-center text-muted py-4">No pending orders right now.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-6">
        <div class="card dashboard-card h-100 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-1">Latest Payment Details</h5>
                        <p class="text-muted mb-0">Most recent gateway transactions.</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Orders</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Transaction</th>
                                <th>Order</th>
                                @if($data['canViewFinancial'])
                                    <th class="text-end">Amount</th>
                                @endif
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['latestPayments'] as $payment)
                                <tr>
                                    <td class="truncate-cell" style="max-width: 180px;">
                                        <strong>{{ $payment->gateway_transaction_id ?? $payment->gateway_reference ?? 'TXN-' . $payment->id }}</strong>
                                        <div class="text-muted small">{{ ucfirst($payment->payment_method ?? 'gateway') }}</div>
                                    </td>
                                    <td>
                                        @if($payment->order)
                                            <a href="{{ route('admin.orders.show', $payment->order) }}">{{ $payment->order->order_number }}</a>
                                            <div class="text-muted small truncate-cell" style="max-width: 160px;">{{ $payment->order->user->name ?? $payment->order->guest_name ?? 'Guest Customer' }}</div>
                                        @else
                                            <span class="text-muted">No order</span>
                                        @endif
                                    </td>
                                    @if($data['canViewFinancial'])
                                        <td class="text-end fw-semibold">{!! $money($payment->amount) !!}</td>
                                    @endif
                                    <td>
                                        <span class="badge bg-{{ $paymentBadge[$payment->status] ?? 'secondary' }}-subtle text-{{ $paymentBadge[$payment->status] ?? 'secondary' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->created_at->format('d M, h:i A') }}</td>
                                </tr>
                            @empty
                                @forelse($data['latestPaymentOrders'] as $order)
                                    <tr>
                                        <td class="truncate-cell" style="max-width: 180px;">
                                            <strong>{{ $order->razorpay_payment_id ?? $order->payment_reference ?? 'ORDER-' . $order->id }}</strong>
                                            <div class="text-muted small">{{ ucfirst($order->payment_method ?? 'manual') }}</div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a>
                                            <div class="text-muted small truncate-cell" style="max-width: 160px;">{{ $order->user->name ?? $order->guest_name ?? 'Guest Customer' }}</div>
                                        </td>
                                        @if($data['canViewFinancial'])
                                            <td class="text-end fw-semibold">{!! $money($order->total) !!}</td>
                                        @endif
                                        <td>
                                            <span class="badge bg-{{ $paymentBadge[$order->payment_status] ?? 'secondary' }}-subtle text-{{ $paymentBadge[$order->payment_status] ?? 'secondary' }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->updated_at->format('d M, h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $data['canViewFinancial'] ? 5 : 4 }}" class="text-center text-muted py-4">No payment records found.</td>
                                    </tr>
                                @endforelse
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card dashboard-card h-100 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-1">Top Selling Products</h5>
                        <p class="text-muted mb-0">Best performers in the selected 30 day period.</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">Products</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Sold</th>
                                @if($data['canViewFinancial'])
                                    <th class="text-end">Revenue</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(array_slice($data['topProducts']['products'] ?? [], 0, 6) as $product)
                                <tr>
                                    <td class="truncate-cell">
                                        <strong>{{ $product['name'] }}</strong>
                                        <div class="text-muted small">SKU/Product ID: {{ $product['id'] }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success rounded-pill">{{ $count($product['total_sold']) }}</span>
                                    </td>
                                    @if($data['canViewFinancial'])
                                        <td class="text-end fw-semibold">{!! $money($product['total_revenue']) !!}</td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $data['canViewFinancial'] ? 3 : 2 }}" class="text-center text-muted py-4">No product sales yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    @if($data['canViewFinancial'])
        <div class="col-xl-6">
            <div class="card dashboard-card h-100 mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h5 class="card-title mb-1">Category Revenue</h5>
                            <p class="text-muted mb-0">Revenue grouped by product category.</p>
                        </div>
                        <a href="{{ route('admin.analytics.sales') }}" class="btn btn-sm btn-outline-primary">Sales Report</a>
                    </div>
                    <div id="category-chart" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
    @endif

    <div class="col-xl-{{ $data['canViewFinancial'] ? '6' : '12' }}">
        <div class="card dashboard-card h-100 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-1">Stock Alerts</h5>
                        <p class="text-muted mb-0">Low and out-of-stock products that need attention.</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-danger">Manage Stock</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th class="text-end">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(array_merge($data['outOfStock'] ?? [], $data['lowStock'] ?? []) as $product)
                                <tr>
                                    <td class="truncate-cell"><strong>{{ $product['name'] }}</strong></td>
                                    <td><span class="badge bg-light text-dark border">{{ $product['category'] }}</span></td>
                                    <td>
                                        @if(($product['stock'] ?? 0) <= 0)
                                            <span class="badge bg-danger-subtle text-danger">Out of Stock</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Low Stock</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold">{{ $count($product['stock'] ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">All active products have healthy stock.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('adminassets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    const formatRupee = function (value) {
        return '\u20B9' + Number(value || 0).toLocaleString('en-IN', {
            maximumFractionDigits: 0
        });
    };

    const baseChartOptions = {
        chart: {
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        dataLabels: { enabled: false },
        grid: {
            borderColor: '#edf0f5',
            strokeDashArray: 4
        },
        noData: {
            text: 'No data available'
        }
    };

    @if($data['canViewFinancial'])
        new ApexCharts(document.querySelector('#revenue-chart'), {
            ...baseChartOptions,
            series: [
                {
                    name: 'Revenue',
                    type: 'area',
                    data: @json($data['dailyRevenue']['revenue'] ?? [])
                },
                {
                    name: 'Orders',
                    type: 'line',
                    data: @json($data['dailyRevenue']['orders'] ?? [])
                }
            ],
            chart: {
                ...baseChartOptions.chart,
                height: 320,
                type: 'line'
            },
            colors: ['#16a34a', '#2563eb'],
            stroke: {
                curve: 'smooth',
                width: [3, 3]
            },
            fill: {
                type: ['gradient', 'solid'],
                gradient: {
                    opacityFrom: 0.35,
                    opacityTo: 0.03
                }
            },
            xaxis: {
                categories: @json($data['dailyRevenue']['labels'] ?? []),
                labels: { rotate: -35 }
            },
            yaxis: [
                {
                    labels: {
                        formatter: formatRupee
                    }
                },
                {
                    opposite: true,
                    labels: {
                        formatter: function (value) {
                            return Number(value || 0).toFixed(0);
                        }
                    }
                }
            ],
            tooltip: {
                shared: true,
                y: [
                    {
                        formatter: function (value) {
                            return '\u20B9' + Number(value || 0).toLocaleString('en-IN', {
                                minimumFractionDigits: 2
                            });
                        }
                    },
                    {
                        formatter: function (value) {
                            return Number(value || 0).toFixed(0) + ' orders';
                        }
                    }
                ]
            }
        }).render();

        new ApexCharts(document.querySelector('#category-chart'), {
            ...baseChartOptions,
            series: [{
                name: 'Revenue',
                data: @json($data['categoryPerformance']['revenue'] ?? [])
            }],
            chart: {
                ...baseChartOptions.chart,
                height: 320,
                type: 'bar'
            },
            colors: ['#2563eb'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '48%'
                }
            },
            xaxis: {
                categories: @json($data['categoryPerformance']['labels'] ?? []),
                labels: { rotate: -25 }
            },
            yaxis: {
                labels: {
                    formatter: formatRupee
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return '\u20B9' + Number(value || 0).toLocaleString('en-IN', {
                            minimumFractionDigits: 2
                        });
                    }
                }
            }
        }).render();
    @endif

    const paymentSeries = @json($data['paymentMethods']['orders'] ?? []);
    const paymentLabels = @json($data['paymentMethods']['labels'] ?? []);

    new ApexCharts(document.querySelector('#payment-chart'), {
        ...baseChartOptions,
        series: paymentSeries.length ? paymentSeries : [0],
        labels: paymentLabels.length ? paymentLabels : ['No payments'],
        chart: {
            ...baseChartOptions.chart,
            height: 320,
            type: 'donut'
        },
        colors: ['#2563eb', '#16a34a', '#f59e0b', '#dc2626', '#64748b'],
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Paid Orders'
                        }
                    }
                }
            }
        }
    }).render();
</script>
@endpush
