@extends('admin::layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @if($data['canViewFinancial'])
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-white-50 mb-3 d-block text-truncate">Total Revenue</span>
                        <h3 class="mb-3 text-white">
                            ₹{{ number_format($data['revenue']['total_revenue'], 2) }}
                        </h3>
                        <div class="text-white-50">
                            @if($data['revenue']['growth_percentage'] >= 0)
                                <span class="badge bg-success-subtle text-success ms-1"><i class="mdi mdi-arrow-up-bold"></i> {{ $data['revenue']['growth_percentage'] }}%</span> vs last period
                            @else
                                <span class="badge bg-danger-subtle text-danger ms-1"><i class="mdi mdi-arrow-down-bold"></i> {{ abs($data['revenue']['growth_percentage']) }}%</span> vs last period
                            @endif
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-white bg-opacity-25 text-white rounded-3 fs-2">
                                <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #2af598 0%, #009efd 100%); color: white;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-white-50 mb-3 d-block text-truncate">Total Orders</span>
                        <h3 class="mb-3 text-white">
                            {{ number_format($data['revenue']['order_count']) }}
                        </h3>
                        <div class="text-white-50">
                            <span class="opacity-75">Lifetime orders</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-white bg-opacity-25 text-white rounded-3 fs-2">
                                <iconify-icon icon="solar:cart-large-4-bold-duotone"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #fab2ff 0%, #1904e5 100%); color: white;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-white-50 mb-3 d-block text-truncate">Avg. Order Value</span>
                        <h3 class="mb-3 text-white">
                            ₹{{ number_format($data['revenue']['average_order_value'], 2) }}
                        </h3>
                        <div class="text-white-50">
                            <span class="opacity-75">Per completed order</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-white bg-opacity-25 text-white rounded-3 fs-2">
                                <iconify-icon icon="solar:tag-price-bold-duotone"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%); color: #5a5a5a;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 d-block text-truncate">Low Stock</span>
                        <h3 class="mb-3">
                            {{ count($data['lowStock']) }}
                        </h3>
                        <div class="text-muted">
                            <span class="text-danger fw-bold">{{ count($data['outOfStock']) }}</span> out of stock
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-danger bg-opacity-10 text-danger rounded-3 fs-2">
                                <iconify-icon icon="solar:box-minimalistic-bold-duotone"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @if($data['canViewFinancial'])
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h4 class="card-title mb-4">Revenue Analytics</h4>
                <div id="revenue-chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="col-xl-{{ $data['canViewFinancial'] ? '4' : '12' }}">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h4 class="card-title mb-4">Payment Methods</h4>
                <div id="payment-chart" class="apex-charts py-2" style="height: 350px"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h4 class="card-title mb-4">Category Performance (Revenue)</h4>
                <div id="category-chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="card-title">Top Selling Products</h4>
                    <span class="badge bg-primary-subtle text-primary">Top 5</span>
                </div>
                
                @if(count($data['topProducts']['products']) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
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
                            @foreach(array_slice($data['topProducts']['products'], 0, 5) as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-light text-primary">
                                                {{ substr($product['name'], 0, 1) }}
                                            </span>
                                        </div>
                                        <h6 class="mb-0 font-size-14 text-truncate" style="max-width: 200px;">{{ $product['name'] }}</h6>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success rounded-pill">{{ $product['total_sold'] }}</span>
                                </td>
                                @if($data['canViewFinancial'])
                                <td class="text-end fw-bold">
                                    ₹{{ number_format($product['total_revenue'], 2) }}
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-muted">No sales data available yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(count($data['lowStock']) > 0 || count($data['outOfStock']) > 0)
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 text-danger"><iconify-icon icon="solar:danger-circle-bold-duotone" class="align-middle fs-18 me-1"></iconify-icon> Stock Alerts</h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Product Name</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Stock Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['outOfStock'] as $product)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $product['name'] }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $product['category'] }}</span></td>
                                <td><span class="badge bg-danger-subtle text-danger">Out of Stock</span></td>
                                <td class="text-end pe-4"><span class="text-danger fw-bold">0</span></td>
                            </tr>
                            @endforeach
                            
                            @foreach($data['lowStock'] as $product)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $product['name'] }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $product['category'] }}</span></td>
                                <td><span class="badge bg-warning-subtle text-warning">Low Stock</span></td>
                                <td class="text-end pe-4"><span class="text-warning fw-bold">{{ $product['stock'] }}</span></td>
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
    // Common Chart Options
    const commonOptions = {
        fontFamily: 'inherit',
        parentHeightOffset: 0,
        toolbar: { show: false },
        animations: { enabled: true }
    };

    @if($data['canViewFinancial'])
    // Revenue & Orders Trends Chart
    var revenueOptions = {
        ...commonOptions,
        series: [{
            name: 'Revenue',
            type: 'area',
            data: @json($data['dailyRevenue']['revenue'])
        }, {
            name: 'Orders',
            type: 'line',
            data: @json($data['dailyRevenue']['orders'])
        }],
        chart: {
            height: 350,
            type: 'line',
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        colors: ['#667eea', '#fb2c36'],
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: [3, 3],
            dashArray: [0, 5]
        },
        fill: {
            type: ['gradient', 'solid'],
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.6,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: @json($data['dailyRevenue']['labels']),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: '#adb5bd' }
            }
        },
        yaxis: [
            {
                seriesName: 'Revenue',
                labels: {
                    formatter: function(val) {
                        return '₹' + val.toLocaleString('en-IN', { maximumFractionDigits: 0 });
                    },
                    style: { colors: '#667eea' }
                },
            },
            {
                opposite: true,
                seriesName: 'Orders',
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0);
                    },
                    style: { colors: '#fb2c36' }
                }
            }
        ],
        grid: {
            borderColor: '#f1f1f1',
            padding: { top: 10, right: 10, bottom: 10, left: 10 }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (y, { seriesIndex, dataPointIndex, w }) {
                    if(seriesIndex === 0) {
                        return '₹' + y.toLocaleString('en-IN', {minimumFractionDigits: 2});
                    }
                    return y + ' orders';
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions).render();
    
    // Category Performance Chart
    var categoryOptions = {
        ...commonOptions,
        series: [{
            name: 'Revenue',
            data: @json($data['categoryPerformance']['revenue'])
        }],
        chart: {
            height: 350,
            type: 'bar',
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '45%',
                distributed: true,
            }
        },
        colors: ['#667eea', '#764ba2', '#009efd', '#2af598', '#fab2ff', '#1904e5', '#ff9a9e', '#fecfef'],
        dataLabels: { enabled: false },
        legend: { show: false },
        xaxis: {
            categories: @json($data['categoryPerformance']['labels']),
            labels: {
                style: { fontSize: '12px' }
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString('en-IN', { maximumFractionDigits: 0 });
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
    new ApexCharts(document.querySelector("#category-chart"), categoryOptions).render();
    @endif

    // Payment Methods Distribution Chart
    var paymentOptions = {
        ...commonOptions,
        series: @json($data['paymentMethods']['orders']),
        chart: {
            height: 320,
            type: 'donut',
        },
        labels: @json($data['paymentMethods']['labels']),
        colors: ['#667eea', '#2af598', '#fb2c36', '#ff9a9e'],
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center', 
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val.toFixed(0) + '%';
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => {
                                    return a + b
                                }, 0)
                            }
                        }
                    }
                }
            }
        },
        stroke: { show: false }
    };
    new ApexCharts(document.querySelector("#payment-chart"), paymentOptions).render();
</script>
@endsection
