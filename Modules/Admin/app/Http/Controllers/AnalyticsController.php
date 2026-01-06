<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Analytics\RevenueAnalyticsRepository;
use App\Repositories\Analytics\SalesAnalyticsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    protected $revenueRepo;
    protected $salesRepo;

    public function __construct(
        RevenueAnalyticsRepository $revenueRepo,
        SalesAnalyticsRepository $salesRepo
    ) {
        $this->revenueRepo = $revenueRepo;
        $this->salesRepo = $salesRepo;
        $this->middleware('auth:admin');
        $this->middleware('admin.role:admin,staff');
    }

    /**
     * Main analytics dashboard
     */
    public function index(Request $request)
    {
        // Get date range from request or default to last 30 days
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $data = [
            'revenue' => $this->revenueRepo->getRevenueStats($startDate, $endDate),
            'dailyRevenue' => $this->revenueRepo->getDailyRevenue(30),
            'topProducts' => $this->salesRepo->getTopSellingProducts(10, $startDate, $endDate),
            'categoryPerformance' => $this->salesRepo->getCategoryPerformance($startDate, $endDate),
            'lowStock' => $this->salesRepo->getLowStockProducts(10),
            'outOfStock' => $this->salesRepo->getOutOfStockProducts(),
            'salesTrends' => $this->salesRepo->getSalesTrends('daily', 30),
            'paymentMethods' => $this->salesRepo->getSalesByPaymentMethod($startDate, $endDate),
        ];

        // Check if user can view financial reports
        $data['canViewFinancial'] = Gate::allows('view_financial_reports');

        return view('admin::analytics.index', compact('data', 'startDate', 'endDate'));
    }

    /**
     * Detailed revenue analytics
     */
    public function revenue(Request $request)
    {
        // Check permission for financial reports
        if (!Gate::allows('view_financial_reports')) {
            abort(403, 'You do not have permission to view financial reports.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $data = [
            'stats' => $this->revenueRepo->getRevenueStats($startDate, $endDate),
            'dailyRevenue' => $this->revenueRepo->getDailyRevenue(30),
            'monthlyRevenue' => $this->revenueRepo->getMonthlyRevenue(12),
            'gstBreakdown' => $this->revenueRepo->getGstBreakdown($startDate, $endDate),
            'paymentDistribution' => $this->revenueRepo->getPaymentMethodDistribution($startDate, $endDate),
            'statusRevenue' => $this->revenueRepo->getRevenueByStatus($startDate, $endDate),
        ];

        return view('admin::analytics.revenue', compact('data', 'startDate', 'endDate'));
    }

    /**
     * Detailed sales analytics
     */
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $limit = $request->get('limit', 10);

        $data = [
            'topProducts' => $this->salesRepo->getTopSellingProducts($limit, $startDate, $endDate),
            'categoryPerformance' => $this->salesRepo->getCategoryPerformance($startDate, $endDate),
            'lowStock' => $this->salesRepo->getLowStockProducts(10),
            'outOfStock' => $this->salesRepo->getOutOfStockProducts(),
            'dailyTrends' => $this->salesRepo->getSalesTrends('daily', 30),
            'monthlyTrends' => $this->salesRepo->getSalesTrends('monthly', 12),
            'paymentMethods' => $this->salesRepo->getSalesByPaymentMethod($startDate, $endDate),
        ];

        return view('admin::analytics.sales', compact('data', 'startDate', 'endDate', 'limit'));
    }

    /**
     * AJAX endpoint for dynamic chart data
     */
    public function chartData(Request $request)
    {
        $type = $request->get('type');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $period = $request->get('period', 'daily');
        $limit = $request->get('limit', 30);

        $data = [];

        switch ($type) {
            case 'revenue_daily':
                $data = $this->revenueRepo->getDailyRevenue($limit);
                break;

            case 'revenue_monthly':
                $data = $this->revenueRepo->getMonthlyRevenue($limit);
                break;

            case 'revenue_stats':
                if (Gate::allows('view_financial_reports')) {
                    $data = $this->revenueRepo->getRevenueStats($startDate, $endDate);
                } else {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
                break;

            case 'gst_breakdown':
                if (Gate::allows('view_financial_reports')) {
                    $data = $this->revenueRepo->getGstBreakdown($startDate, $endDate);
                } else {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
                break;

            case 'top_products':
                $data = $this->salesRepo->getTopSellingProducts($limit, $startDate, $endDate);
                break;

            case 'category_performance':
                $data = $this->salesRepo->getCategoryPerformance($startDate, $endDate);
                break;

            case 'sales_trends':
                $data = $this->salesRepo->getSalesTrends($period, $limit);
                break;

            case 'low_stock':
                $data = $this->salesRepo->getLowStockProducts($limit);
                break;

            case 'payment_methods':
                $data = $this->salesRepo->getSalesByPaymentMethod($startDate, $endDate);
                break;

            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }

        return response()->json($data);
    }
}
