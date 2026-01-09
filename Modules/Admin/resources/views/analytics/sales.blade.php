@extends('admin::layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Sales Analytics</h4>
    <div>
        <input type="date" id="start_date" class="form-control d-inline-block" style="width: auto;" value="{{ $startDate }}">
        <span class="mx-2">to</span>
        <input type="date" id="end_date" class="form-control d-inline-block" style="width: auto;" value="{{ $endDate }}">
        <select id="limit" class="form-select d-inline-block ms-2" style="width: auto;">
            <option value="10" {{ $limit == 10 ? 'selected' : '' }}>Top 10</option>
            <option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option>
            <option value="50" {{ $limit == 50 ? 'selected' : '' }}>Top 50</option>
        </select>
        <button class="btn btn-primary ms-2" onclick="refreshAnalytics()">
            <i class="mdi mdi-refresh"></i> Refresh
        </button>
        <a href="{{ route('admin.reports.sales.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-danger ms-2">
            <i class="mdi mdi-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

<!-- Sales Overview -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Top Selling Products</h5>
                <div id="top-products-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Category Performance</h5>
                <div id="category-performance-chart"></div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Trends -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Daily Sales Trends</h5>
                <div id="daily-trends-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Monthly Sales Trends</h5>
                <div id="monthly-trends-chart"></div>
            </div>
        </div>
    </div>
</div>

<!-- Top Products Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Product Performance Details</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Rank</th>
                                <th>Product Name</th>
                                <th>Units Sold</th>
                                @if($data['canViewFinancial'] ?? true)
                                <th>Revenue</th>
                                <th>Avg Price</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['topProducts']['products'] as $index => $product)
                            <tr>
                                <td>
                                    @if($index < 3)
                                    <span class="badge bg-warning-subtle text-warning">{{ $index + 1 }}</span>
                                    @else
                                    {{ $index + 1 }}
                                    @endif
                                </td>
                                <td><strong>{{ $product['name'] }}</strong></td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">{{ $product['total_sold'] }} units</span>
                                </td>
                                @if($data['canViewFinancial'] ?? true)
                                <td>₹{{ number_format($product['total_revenue'], 2) }}</td>
                                <td>₹{{ number_format($product['total_revenue'] / max($product['total_sold'], 1), 2) }}</td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No sales data available for the selected period</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Alerts -->
@if(count($data['lowStock']) > 0 || count($data['outOfStock']) > 0)
<div class="row">
    @if(count($data['lowStock']) > 0)
    <div class="col-xl-6">
        <div class="card border-warning">
            <div class="card-body">
                <h5 class="card-title text-warning mb-4">
                    <i class="mdi mdi-alert-outline"></i> Low Stock Products ({{ count($data['lowStock']) }})
                </h5>
                <div class="table-responsive">
                    <table class="table table-sm table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['lowStock'] as $product)
                            <tr>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['category'] }}</td>
                                <td>
                                    <span class="badge bg-warning-subtle text-warning">
                                        {{ $product['stock'] }}
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
    @endif

    @if(count($data['outOfStock']) > 0)
    <div class="col-xl-6">
        <div class="card border-danger">
            <div class="card-body">
                <h5 class="card-title text-danger mb-4">
                    <i class="mdi mdi-close-circle-outline"></i> Out of Stock Products ({{ count($data['outOfStock']) }})
                </h5>
                <div class="table-responsive">
                    <table class="table table-sm table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
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
    @endif
</div>
@endif
@endsection

@section('scripts')
<script src="{{ asset('adminassets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    // Top Products Chart
    var topProductsOptions = {
        series: [{
            name: 'Units Sold',
            data: @json(array_slice($data['topProducts']['quantities'], 0, 10))
        }],
        chart: {
            height: 350,
            type: 'bar',
            toolbar: { show: false }
        },
        colors: ['#556ee6'],
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 4
            }
        },
        xaxis: {
            categories: @json(array_slice($data['topProducts']['labels'], 0, 10))
        }
    };
    new ApexCharts(document.querySelector("#top-products-chart"), topProductsOptions).render();

    // Category Performance Chart
    var categoryOptions = {
        series: @json($data['categoryPerformance']['quantities']),
        chart: {
            height: 350,
            type: 'donut'
        },
        labels: @json($data['categoryPerformance']['labels']),
        colors: ['#556ee6', '#34c38f', '#f46a6a', '#50a5f1', '#f1b44c'],
        legend: {
            position: 'bottom'
        }
    };
    new ApexCharts(document.querySelector("#category-performance-chart"), categoryOptions).render();

    // Daily Trends Chart
    var dailyOptions = {
        series: [{
            name: 'Quantity',
            data: @json($data['dailyTrends']['quantities'])
        }],
        chart: {
            height: 300,
            type: 'line',
            toolbar: { show: false }
        },
        colors: ['#34c38f'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: @json($data['dailyTrends']['labels'])
        }
    };
    new ApexCharts(document.querySelector("#daily-trends-chart"), dailyOptions).render();

    // Monthly Trends Chart
    var monthlyOptions = {
        series: [{
            name: 'Quantity',
            data: @json($data['monthlyTrends']['quantities'])
        }],
        chart: {
            height: 300,
            type: 'area',
            toolbar: { show: false }
        },
        colors: ['#556ee6'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1
            }
        },
        xaxis: {
            categories: @json($data['monthlyTrends']['labels'])
        }
    };
    new ApexCharts(document.querySelector("#monthly-trends-chart"), monthlyOptions).render();

    function refreshAnalytics() {
        var startDate = document.getElementById('start_date').value;
        var endDate = document.getElementById('end_date').value;
        var limit = document.getElementById('limit').value;
        window.location.href = '{{ route("admin.analytics.sales") }}?start_date=' + startDate + '&end_date=' + endDate + '&limit=' + limit;
    }
</script>
@endsection
