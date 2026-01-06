<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Exports\OrdersExport;
use App\Exports\ProductsExport;
use App\Exports\CustomersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
        $this->middleware('auth:admin');
        $this->middleware('admin.role:admin');  // Reports restricted to admin only
    }

    /**
     * Reports dashboard page
     */
    public function index()
    {
        $summary = $this->reportService->getReportSummary();

        return view('admin::reports.index', compact('summary'));
    }

    /**
     * Download revenue PDF report
     */
    public function revenuePdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $pdf = $this->reportService->generateRevenuePdf($startDate, $endDate);

        $filename = 'revenue_report_' . $startDate . '_to_' . $endDate . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download sales PDF report
     */
    public function salesPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $pdf = $this->reportService->generateSalesPdf($startDate, $endDate);

        $filename = 'sales_report_' . $startDate . '_to_' . $endDate . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download customer PDF report
     */
    public function customerPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $pdf = $this->reportService->generateCustomerPdf($startDate, $endDate);

        $filename = 'customer_report_' . $startDate . '_to_' . $endDate . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download order PDF report
     */
    public function orderPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $pdf = $this->reportService->generateOrderPdf($startDate, $endDate);

        $filename = 'order_report_' . $startDate . '_to_' . $endDate . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export orders to Excel
     */
    public function ordersExcel(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'payment_status' => $request->get('payment_status'),
            'payment_method' => $request->get('payment_method'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        // Remove null values
        $filters = array_filter($filters, fn($value) => !is_null($value) && $value !== '');

        $filename = 'orders_export_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new OrdersExport($filters), $filename);
    }

    /**
     * Export products to Excel
     */
    public function productsExcel(Request $request)
    {
        $filters = [
            'category_id' => $request->get('category_id'),
            'stock_status' => $request->get('stock_status'),
            'is_active' => $request->get('is_active'),
            'is_featured' => $request->get('is_featured'),
        ];

        // Remove null values
        $filters = array_filter($filters, fn($value) => !is_null($value) && $value !== '');

        $filename = 'products_export_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new ProductsExport($filters), $filename);
    }

    /**
     * Export customers to Excel
     */
    public function customersExcel(Request $request)
    {
        $filters = [
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'min_spent' => $request->get('min_spent'),
            'max_spent' => $request->get('max_spent'),
            'min_orders' => $request->get('min_orders'),
        ];

        // Remove null values
        $filters = array_filter($filters, fn($value) => !is_null($value) && $value !== '');

        $filename = 'customers_export_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new CustomersExport($filters), $filename);
    }
}
