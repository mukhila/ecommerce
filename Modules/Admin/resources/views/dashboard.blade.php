@extends('admin::layouts.main')

@section('content')
<div class="row">
    @if($data['canViewFinancial'])
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-2 fs-2">
                            <i class="mdi mdi-chart-bar"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-medium text-muted mb-1">Total Revenue</h6>
                        <h4 class="mb-0">₹{{ number_format($data['revenue']['total_revenue'], 2) }}</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-end">
                        @if($data['revenue']['growth_percentage'] >= 0)
                        <div class="badge bg-success-subtle text-success">
                            <i class="mdi mdi-arrow-up-bold"></i> {{ number_format($data['revenue']['growth_percentage'], 1) }}%
                        </div>
                        @else
                        <div class="badge bg-danger-subtle text-danger">
                            <i class="mdi mdi-arrow-down-bold"></i> {{ number_format(abs($data['revenue']['growth_percentage']), 1) }}%
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info-subtle text-info rounded-2 fs-2">
                            <i class="mdi mdi-cart-outline"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-medium text-muted mb-1">Total Orders</h6>
                        <h4 class="mb-0">{{ number_format($data['revenue']['order_count']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($data['canViewFinancial'])
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle text-warning rounded-2 fs-2">
                            <i class="mdi mdi-wallet-outline"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-medium text-muted mb-1">Avg Order Value</h6>
                        <h4 class="mb-0">₹{{ number_format($data['revenue']['average_order_value'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-secondary-subtle text-secondary rounded-2 fs-2">
                            <i class="mdi mdi-package-variant"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-medium text-muted mb-1">Low Stock Products</h6>
                        <h4 class="mb-0">{{ count($data['lowStock']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @if($data['canViewFinancial'])
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Revenue Trends (Last 30 Days)</h4>
                <div id="revenue-chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-xl-{{ $data['canViewFinancial'] ? '4' : '12' }}">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Payment Methods Distribution</h4>
                <div id="payment-chart" class="apex-charts" style="height: 300px"></div>
            </div>
        </div>
    </div>
</div>

@if(count($data['topProducts']['products']) > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Top Selling Products</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Units Sold</th>
                                @if($data['canViewFinancial'])
                                <th>Revenue</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['topProducts']['products'] as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product['name'] }}</td>
                                <td><span class="badge bg-info-subtle text-info">{{ $product['total_sold'] }} units</span></td>
                                @if($data['canViewFinancial'])
                                <td>₹{{ number_format($product['total_revenue'], 2) }}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(count($data['lowStock']) > 0)
<div class="row">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-body">
                <h4 class="card-title mb-4 text-warning">
                    <i class="mdi mdi-alert-outline"></i> Low Stock Alerts
                </h4>
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Stock Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['lowStock'] as $product)
                            <tr>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['category'] }}</td>
                                <td>
                                    <span class="badge bg-warning-subtle text-warning">
                                        {{ $product['stock'] }} remaining
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(count($data['outOfStock']) > 0)
<div class="row">
    <div class="col-12">
        <div class="card border-danger">
            <div class="card-body">
                <h4 class="card-title mb-4 text-danger">
                    <i class="mdi mdi-close-circle-outline"></i> Out of Stock Products
                </h4>
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['outOfStock'] as $product)
                            <tr>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['category'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script src="{{ asset('adminassets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    @if($data['canViewFinancial'])
    // Revenue Trends Chart
    var revenueOptions = {
        series: [{
            name: 'Revenue',
            data: @json($data['dailyRevenue']['revenue'])
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        colors: ['#556ee6'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
            }
        },
        xaxis: {
            categories: @json($data['dailyRevenue']['labels']),
            labels: {
                rotate: -45,
                rotateAlways: false
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString();
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString('en-IN', {minimumFractionDigits: 2});
                }
            }
        }
    };
    var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
    revenueChart.render();
    @endif

    // Payment Methods Distribution Chart
    var paymentOptions = {
        series: @json($data['paymentMethods']['orders']),
        chart: {
            height: 300,
            type: 'donut',
        },
        labels: @json($data['paymentMethods']['labels']),
        colors: ['#556ee6', '#34c38f', '#f46a6a', '#50a5f1'],
        legend: {
            show: true,
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val.toFixed(1) + '%';
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + ' orders';
                }
            }
        }
    };
    var paymentChart = new ApexCharts(document.querySelector("#payment-chart"), paymentOptions);
    paymentChart.render();
</script>
@endsection
