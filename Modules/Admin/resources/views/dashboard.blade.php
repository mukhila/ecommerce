@extends('admin::layouts.main')

@section('content')
<div class="row">
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
                        <h4 class="mb-0">$84,254</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-end">
                        <div class="badge bg-success-subtle text-success">
                            <i class="mdi mdi-arrow-up-bold"></i> 2.5%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                        <h6 class="fw-medium text-muted mb-1">Orders</h6>
                        <h4 class="mb-0">5,325</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-end">
                        <div class="badge bg-danger-subtle text-danger">
                            <i class="mdi mdi-arrow-down-bold"></i> 1.2%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle text-warning rounded-2 fs-2">
                            <i class="mdi mdi-account-group-outline"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-medium text-muted mb-1">Customers</h6>
                        <h4 class="mb-0">2,541</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-end">
                        <div class="badge bg-success-subtle text-success">
                            <i class="mdi mdi-arrow-up-bold"></i> 4.6%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-secondary-subtle text-secondary rounded-2 fs-2">
                            <i class="mdi mdi-wallet-outline"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-medium text-muted mb-1">My Balance</h6>
                        <h4 class="mb-0">$12,450</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Revenue Analysis</h4>
                <div id="revenue-chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Sales by Location</h4>
                <div id="sales-by-locations" style="height: 250px"></div>
                <div class="mt-3">
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <p class="mb-0">USA</p>
                        <h5 class="mb-0">45%</h5>
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Recent Orders</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Billing Name</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Payment Status</th>
                                <th>Payment Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#ORD-001</td>
                                <td>Neal Matthews</td>
                                <td>07 Oct, 2025</td>
                                <td>$400</td>
                                <td><span class="badge bg-success-subtle text-success">Paid</span></td>
                                <td><i class="fab fa-cc-mastercard me-1"></i> Mastercard</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-002</td>
                                <td>Jamal Burnett</td>
                                <td>06 Oct, 2025</td>
                                <td>$380</td>
                                <td><span class="badge bg-warning-subtle text-warning">Chargeback</span></td>
                                <td><i class="fab fa-cc-visa me-1"></i> Visa</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#ORD-003</td>
                                <td>Juan Mitchell</td>
                                <td>06 Oct, 2025</td>
                                <td>$384</td>
                                <td><span class="badge bg-success-subtle text-success">Paid</span></td>
                                <td><i class="fab fa-cc-paypal me-1"></i> Paypal</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                        View Details
                                    </button>
                                </td>
                            </tr>
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
    // Revenue Chart
    var options = {
        series: [{
            name: 'Revenue',
            data: [20, 35, 40, 60, 45, 70, 65, 80, 75, 90, 85, 100]
        }],
        chart: {
            height: 350,
            type: 'bar', // or 'line'
            toolbar: { show: false }
        },
        colors: ['#556ee6'],
        plotOptions: {
            bar: {
                dataLabels: { position: 'top' },
            }
        },
        dataLabels: {
            enabled: false,
            formatter: function(val) { return val + "%"; },
            offsetY: -20,
            style: { fontSize: '12px', colors: ["#304758"] }
        },
        xaxis: {
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            position: 'top',
            axisBorder: { show: false },
            axisTicks: { show: false },
            crosshairs: {
                fill: {
                    type: 'gradient',
                    gradient: {
                        colorFrom: '#D8E3F0',
                        colorTo: '#BED1E6',
                        stops: [0, 100],
                        opacityFrom: 0.4,
                        opacityTo: 0.5,
                    }
                }
            },
            tooltip: { enabled: true, }
        },
        yaxis: {
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { show: false, formatter: function(val) { return val + "%"; } }
        },
    };
    var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
    chart.render();
</script>
@endsection
