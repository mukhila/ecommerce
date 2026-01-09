@extends('admin::layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Revenue Analytics</h4>
    <div>
        <input type="date" id="start_date" class="form-control d-inline-block" style="width: auto;" value="{{ $startDate }}">
        <span class="mx-2">to</span>
        <input type="date" id="end_date" class="form-control d-inline-block" style="width: auto;" value="{{ $endDate }}">
        <button class="btn btn-primary ms-2" onclick="refreshAnalytics()">
            <i class="mdi mdi-refresh"></i> Refresh
        </button>
        <a href="{{ route('admin.reports.revenue.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-danger ms-2">
            <i class="mdi mdi-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

<!-- Revenue Stats -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Total Revenue</p>
                <h3 class="mb-0">₹{{ number_format($data['stats']['total_revenue'], 2) }}</h3>
                @if($data['stats']['growth_percentage'] >= 0)
                <span class="badge bg-success-subtle text-success mt-2">
                    <i class="mdi mdi-arrow-up"></i> {{ $data['stats']['growth_percentage'] }}% from last period
                </span>
                @else
                <span class="badge bg-danger-subtle text-danger mt-2">
                    <i class="mdi mdi-arrow-down"></i> {{ abs($data['stats']['growth_percentage']) }}% from last period
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Total Orders</p>
                <h3 class="mb-0">{{ number_format($data['stats']['order_count']) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Average Order Value</p>
                <h3 class="mb-0">₹{{ number_format($data['stats']['average_order_value'], 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Growth Rate</p>
                <h3 class="mb-0 {{ $data['stats']['growth_percentage'] >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $data['stats']['growth_percentage'] }}%
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Trends -->
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Daily Revenue Trends (Last 30 Days)</h5>
                <div id="daily-revenue-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">GST Breakdown</h5>
                <div id="gst-chart"></div>
                <div class="mt-3">
                    @foreach($data['gstBreakdown']['labels'] as $index => $label)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $label }}</span>
                        <strong>₹{{ number_format($data['gstBreakdown']['values'][$index], 2) }}</strong>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Revenue & Payment Distribution -->
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Monthly Revenue Trends (Last 12 Months)</h5>
                <div id="monthly-revenue-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Payment Methods Distribution</h5>
                <div id="payment-distribution-chart"></div>
                <div class="mt-3">
                    @foreach($data['paymentDistribution']['labels'] as $index => $label)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $label }}</span>
                        <div>
                            <span class="badge bg-primary-subtle text-primary">{{ $data['paymentDistribution']['orders'][$index] }} orders</span>
                            <strong class="ms-2">₹{{ number_format($data['paymentDistribution']['revenue'][$index], 2) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue by Status -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Revenue by Order Status</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th>
                                <th>Orders</th>
                                <th>Revenue</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalRevenue = array_sum($data['statusRevenue']['revenue']);
                            @endphp
                            @foreach($data['statusRevenue']['labels'] as $index => $label)
                            <tr>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">{{ $label }}</span>
                                </td>
                                <td>{{ $data['statusRevenue']['orders'][$index] }}</td>
                                <td>₹{{ number_format($data['statusRevenue']['revenue'][$index], 2) }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $totalRevenue > 0 ? ($data['statusRevenue']['revenue'][$index] / $totalRevenue * 100) : 0 }}%">
                                            {{ $totalRevenue > 0 ? number_format($data['statusRevenue']['revenue'][$index] / $totalRevenue * 100, 1) : 0 }}%
                                        </div>
                                    </div>
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
@endsection

@section('scripts')
<script src="{{ asset('adminassets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    // Daily Revenue Chart
    var dailyOptions = {
        series: [{
            name: 'Revenue',
            data: @json($data['dailyRevenue']['revenue'])
        }, {
            name: 'GST',
            data: @json($data['dailyRevenue']['gst'])
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false }
        },
        colors: ['#556ee6', '#f46a6a'],
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 2
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
            shared: true,
            y: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString('en-IN', {minimumFractionDigits: 2});
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#daily-revenue-chart"), dailyOptions).render();

    // Monthly Revenue Chart
    var monthlyOptions = {
        series: [{
            name: 'Revenue',
            data: @json($data['monthlyRevenue']['revenue'])
        }],
        chart: {
            height: 350,
            type: 'bar',
            toolbar: { show: false }
        },
        colors: ['#34c38f'],
        plotOptions: {
            bar: {
                columnWidth: '50%',
                borderRadius: 4
            }
        },
        xaxis: {
            categories: @json($data['monthlyRevenue']['labels'])
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString();
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#monthly-revenue-chart"), monthlyOptions).render();

    // GST Breakdown Chart
    var gstOptions = {
        series: @json($data['gstBreakdown']['values']),
        chart: {
            height: 250,
            type: 'pie'
        },
        labels: @json($data['gstBreakdown']['labels']),
        colors: ['#556ee6', '#34c38f', '#f46a6a', '#50a5f1'],
        legend: {
            show: false
        }
    };
    new ApexCharts(document.querySelector("#gst-chart"), gstOptions).render();

    // Payment Distribution Chart
    var paymentOptions = {
        series: @json($data['paymentDistribution']['orders']),
        chart: {
            height: 250,
            type: 'donut'
        },
        labels: @json($data['paymentDistribution']['labels']),
        colors: ['#556ee6', '#34c38f'],
        legend: {
            show: false
        }
    };
    new ApexCharts(document.querySelector("#payment-distribution-chart"), paymentOptions).render();

    function refreshAnalytics() {
        var startDate = document.getElementById('start_date').value;
        var endDate = document.getElementById('end_date').value;
        window.location.href = '{{ route("admin.analytics.revenue") }}?start_date=' + startDate + '&end_date=' + endDate;
    }
</script>
@endsection
