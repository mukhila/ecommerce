@extends('admin::layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Analytics Dashboard</h4>
    <div>
        <input type="date" id="start_date" class="form-control d-inline-block" style="width: auto;" value="{{ $startDate }}">
        <span class="mx-2">to</span>
        <input type="date" id="end_date" class="form-control d-inline-block" style="width: auto;" value="{{ $endDate }}">
        <button class="btn btn-primary ms-2" onclick="refreshAnalytics()">
            <i class="mdi mdi-refresh"></i> Refresh
        </button>
    </div>
</div>

<!-- KPI Cards -->
<div class="row">
    @if($data['canViewFinancial'])
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-2 fs-2">
                            <i class="mdi mdi-currency-inr"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-medium text-muted mb-1">Total Revenue</h6>
                        <h4 class="mb-0">₹{{ number_format($data['revenue']['total_revenue'], 2) }}</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-end">
                        @if($data['revenue']['growth_percentage'] >= 0)
                        <div class="badge bg-success-subtle text-success">
                            <i class="mdi mdi-arrow-up-bold"></i> {{ $data['revenue']['growth_percentage'] }}%
                        </div>
                        @else
                        <div class="badge bg-danger-subtle text-danger">
                            <i class="mdi mdi-arrow-down-bold"></i> {{ abs($data['revenue']['growth_percentage']) }}%
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
                        <span class="avatar-title bg-success-subtle text-success rounded-2 fs-2">
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
                        <span class="avatar-title bg-warning-subtle text-warning rounded-2 fs-2">
                            <i class="mdi mdi-alert-outline"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-medium text-muted mb-1">Low Stock Alerts</h6>
                        <h4 class="mb-0">{{ count($data['lowStock']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    @if($data['canViewFinancial'])
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Revenue Trends</h5>
                <div id="revenue-chart"></div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-xl-{{ $data['canViewFinancial'] ? '4' : '12' }}">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Payment Methods</h5>
                <div id="payment-chart"></div>
            </div>
        </div>
    </div>
</div>

<!-- Top Products & Category Performance -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Top Selling Products</h5>
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
                            @forelse($data['topProducts']['products'] as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product['name'] }}</td>
                                <td><span class="badge bg-info-subtle text-info">{{ $product['total_sold'] }} units</span></td>
                                @if($data['canViewFinancial'])
                                <td>₹{{ number_format($product['total_revenue'], 2) }}</td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $data['canViewFinancial'] ? 4 : 3 }}" class="text-center text-muted">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Category Performance</h5>
                <div id="category-chart"></div>
            </div>
        </div>
    </div>
</div>

@if(count($data['lowStock']) > 0)
<div class="row">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-body">
                <h5 class="card-title text-warning mb-4">
                    <i class="mdi mdi-alert-outline"></i> Low Stock Alerts
                </h5>
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
@endsection

@section('scripts')
<script src="{{ asset('adminassets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    @if($data['canViewFinancial'])
    // Revenue Chart
    var revenueOptions = {
        series: [{
            name: 'Revenue',
            data: @json($data['dailyRevenue']['revenue'])
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false }
        },
        colors: ['#556ee6'],
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1
            }
        },
        xaxis: {
            categories: @json($data['dailyRevenue']['labels'])
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

    // Payment Methods Chart
    var paymentOptions = {
        series: @json($data['paymentMethods']['orders']),
        chart: {
            height: 300,
            type: 'donut'
        },
        labels: @json($data['paymentMethods']['labels']),
        colors: ['#556ee6', '#34c38f', '#f46a6a', '#50a5f1'],
        legend: {
            show: true,
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val.toFixed(1) + '%';
            }
        }
    };
    var paymentChart = new ApexCharts(document.querySelector("#payment-chart"), paymentOptions);
    paymentChart.render();

    // Category Performance Chart
    var categoryOptions = {
        series: [{
            name: 'Revenue',
            data: @json($data['categoryPerformance']['revenue'])
        }],
        chart: {
            height: 300,
            type: 'bar',
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 4
            }
        },
        colors: ['#34c38f'],
        xaxis: {
            categories: @json($data['categoryPerformance']['labels'])
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString();
                }
            }
        }
    };
    var categoryChart = new ApexCharts(document.querySelector("#category-chart"), categoryOptions);
    categoryChart.render();

    function refreshAnalytics() {
        var startDate = document.getElementById('start_date').value;
        var endDate = document.getElementById('end_date').value;
        window.location.href = '{{ route("admin.analytics.index") }}?start_date=' + startDate + '&end_date=' + endDate;
    }
</script>
@endsection
