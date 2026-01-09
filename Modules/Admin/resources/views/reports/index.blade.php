@extends('admin::layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Reports & Exports</h4>
</div>

<!-- Report Summary -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="text-white-50 mb-2">Total Revenue</h6>
                <h4 class="text-white mb-0">₹{{ number_format($summary['revenue']['total_revenue'], 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="text-white-50 mb-2">Total Orders</h6>
                <h4 class="text-white mb-0">{{ number_format($summary['orders']['total_orders']) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="text-white-50 mb-2">Total Customers</h6>
                <h4 class="text-white mb-0">{{ number_format($summary['customers']['total_customers']) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="text-white-50 mb-2">Low Stock Items</h6>
                <h4 class="text-white mb-0">{{ $summary['sales']['lowStockCount'] }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- PDF Reports -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">PDF Reports</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <h6><i class="mdi mdi-file-pdf text-danger"></i> Revenue Report</h6>
                            <p class="text-muted mb-3">Comprehensive revenue analytics with GST breakdown</p>
                            <div class="row g-2">
                                <div class="col-5">
                                    <input type="date" id="revenue_start" class="form-control form-control-sm" value="{{ date('Y-m-01') }}">
                                </div>
                                <div class="col-5">
                                    <input type="date" id="revenue_end" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-2">
                                    <button onclick="downloadReport('revenue')" class="btn btn-danger btn-sm w-100">
                                        <i class="mdi mdi-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <h6><i class="mdi mdi-file-pdf text-danger"></i> Sales Report</h6>
                            <p class="text-muted mb-3">Top products, categories, and sales trends</p>
                            <div class="row g-2">
                                <div class="col-5">
                                    <input type="date" id="sales_start" class="form-control form-control-sm" value="{{ date('Y-m-01') }}">
                                </div>
                                <div class="col-5">
                                    <input type="date" id="sales_end" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-2">
                                    <button onclick="downloadReport('sales')" class="btn btn-danger btn-sm w-100">
                                        <i class="mdi mdi-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <h6><i class="mdi mdi-file-pdf text-danger"></i> Customer Report</h6>
                            <p class="text-muted mb-3">Customer analytics and segmentation</p>
                            <div class="row g-2">
                                <div class="col-5">
                                    <input type="date" id="customer_start" class="form-control form-control-sm" value="{{ date('Y-m-01') }}">
                                </div>
                                <div class="col-5">
                                    <input type="date" id="customer_end" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-2">
                                    <button onclick="downloadReport('customer')" class="btn btn-danger btn-sm w-100">
                                        <i class="mdi mdi-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <h6><i class="mdi mdi-file-pdf text-danger"></i> Order Report</h6>
                            <p class="text-muted mb-3">Order statistics and fulfillment metrics</p>
                            <div class="row g-2">
                                <div class="col-5">
                                    <input type="date" id="order_start" class="form-control form-control-sm" value="{{ date('Y-m-01') }}">
                                </div>
                                <div class="col-5">
                                    <input type="date" id="order_end" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-2">
                                    <button onclick="downloadReport('order')" class="btn btn-danger btn-sm w-100">
                                        <i class="mdi mdi-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Excel Exports -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Excel Exports</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="border p-3 rounded">
                            <h6><i class="mdi mdi-file-excel text-success"></i> Orders Export</h6>
                            <p class="text-muted mb-3">Export all orders with filters</p>
                            <a href="{{ route('admin.reports.orders.excel') }}" class="btn btn-success btn-sm w-100">
                                <i class="mdi mdi-download"></i> Download Excel
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="border p-3 rounded">
                            <h6><i class="mdi mdi-file-excel text-success"></i> Products Export</h6>
                            <p class="text-muted mb-3">Export all products with inventory</p>
                            <a href="{{ route('admin.reports.products.excel') }}" class="btn btn-success btn-sm w-100">
                                <i class="mdi mdi-download"></i> Download Excel
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="border p-3 rounded">
                            <h6><i class="mdi mdi-file-excel text-success"></i> Customers Export</h6>
                            <p class="text-muted mb-3">Export all customers with analytics</p>
                            <a href="{{ route('admin.reports.customers.excel') }}" class="btn btn-success btn-sm w-100">
                                <i class="mdi mdi-download"></i> Download Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function downloadReport(type) {
        const startDate = document.getElementById(type + '_start').value;
        const endDate = document.getElementById(type + '_end').value;

        if (!startDate || !endDate) {
            alert('Please select both start and end dates');
            return;
        }

        window.location.href = '{{ route("admin.reports.index") }}/../' + type + '/pdf?start_date=' + startDate + '&end_date=' + endDate;
    }
</script>
@endsection
