<?php

namespace App\Services;

use App\Repositories\Analytics\RevenueAnalyticsRepository;
use App\Repositories\Analytics\SalesAnalyticsRepository;
use App\Repositories\Analytics\CustomerAnalyticsRepository;
use App\Repositories\Analytics\OrderAnalyticsRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportService
{
    protected $revenueRepo;
    protected $salesRepo;
    protected $customerRepo;
    protected $orderRepo;

    public function __construct(
        RevenueAnalyticsRepository $revenueRepo,
        SalesAnalyticsRepository $salesRepo,
        CustomerAnalyticsRepository $customerRepo,
        OrderAnalyticsRepository $orderRepo
    ) {
        $this->revenueRepo = $revenueRepo;
        $this->salesRepo = $salesRepo;
        $this->customerRepo = $customerRepo;
        $this->orderRepo = $orderRepo;
    }

    /**
     * Generate revenue PDF report
     */
    public function generateRevenuePdf(string $startDate, string $endDate)
    {
        $data = [
            'title' => 'Revenue Report',
            'period' => Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y'),
            'generated_at' => Carbon::now()->format('M d, Y H:i:s'),
            'stats' => $this->revenueRepo->getRevenueStats($startDate, $endDate),
            'dailyRevenue' => $this->revenueRepo->getDailyRevenue(30),
            'monthlyRevenue' => $this->revenueRepo->getMonthlyRevenue(12),
            'gstBreakdown' => $this->revenueRepo->getGstBreakdown($startDate, $endDate),
            'paymentDistribution' => $this->revenueRepo->getPaymentMethodDistribution($startDate, $endDate),
        ];

        $pdf = Pdf::loadView('admin::reports.pdf.revenue', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Generate sales PDF report
     */
    public function generateSalesPdf(string $startDate, string $endDate)
    {
        $data = [
            'title' => 'Sales Report',
            'period' => Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y'),
            'generated_at' => Carbon::now()->format('M d, Y H:i:s'),
            'topProducts' => $this->salesRepo->getTopSellingProducts(20, $startDate, $endDate),
            'categoryPerformance' => $this->salesRepo->getCategoryPerformance($startDate, $endDate),
            'lowStock' => $this->salesRepo->getLowStockProducts(20),
            'outOfStock' => $this->salesRepo->getOutOfStockProducts(),
            'salesTrends' => $this->salesRepo->getSalesTrends('daily', 30),
        ];

        $pdf = Pdf::loadView('admin::reports.pdf.sales', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Generate customer PDF report
     */
    public function generateCustomerPdf(string $startDate, string $endDate)
    {
        $data = [
            'title' => 'Customer Report',
            'period' => Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y'),
            'generated_at' => Carbon::now()->format('M d, Y H:i:s'),
            'stats' => $this->customerRepo->getCustomerStats($startDate, $endDate),
            'topCustomers' => $this->customerRepo->getCustomerLifetimeValue(20),
            'segmentation' => $this->customerRepo->getCustomerSegmentation(),
            'geographic' => $this->customerRepo->getGeographicDistribution(10),
        ];

        $pdf = Pdf::loadView('admin::reports.pdf.customer', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Generate order PDF report
     */
    public function generateOrderPdf(string $startDate, string $endDate)
    {
        $data = [
            'title' => 'Order Report',
            'period' => Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y'),
            'generated_at' => Carbon::now()->format('M d, Y H:i:s'),
            'stats' => $this->orderRepo->getOrderStatistics($startDate, $endDate),
            'statusDistribution' => $this->orderRepo->getOrderStatusDistribution($startDate, $endDate),
            'processingTime' => $this->orderRepo->getAverageProcessingTime(),
            'deliveryPerformance' => $this->orderRepo->getDeliveryPerformance(),
            'cancellationReasons' => $this->orderRepo->getCancellationReasons(10),
        ];

        $pdf = Pdf::loadView('admin::reports.pdf.order', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Generate comprehensive analytics report for email
     */
    public function generateComprehensiveReport(string $startDate, string $endDate): array
    {
        return [
            'revenue' => $this->revenueRepo->getRevenueStats($startDate, $endDate),
            'sales' => [
                'topProducts' => $this->salesRepo->getTopSellingProducts(10, $startDate, $endDate),
                'categoryPerformance' => $this->salesRepo->getCategoryPerformance($startDate, $endDate),
            ],
            'customers' => $this->customerRepo->getCustomerStats($startDate, $endDate),
            'orders' => $this->orderRepo->getOrderStatistics($startDate, $endDate),
        ];
    }

    /**
     * Get report summary for dashboard
     */
    public function getReportSummary(): array
    {
        $today = Carbon::now()->format('Y-m-d');
        $thirtyDaysAgo = Carbon::now()->subDays(30)->format('Y-m-d');

        return [
            'period' => 'Last 30 Days',
            'revenue' => $this->revenueRepo->getRevenueStats($thirtyDaysAgo, $today),
            'orders' => $this->orderRepo->getOrderStatistics($thirtyDaysAgo, $today),
            'customers' => $this->customerRepo->getCustomerStats($thirtyDaysAgo, $today),
            'sales' => [
                'topProduct' => $this->salesRepo->getTopSellingProducts(1, $thirtyDaysAgo, $today)['products'][0] ?? null,
                'lowStockCount' => count($this->salesRepo->getLowStockProducts(10)),
            ],
        ];
    }
}
