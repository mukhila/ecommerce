<?php

namespace App\Repositories\Analytics;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RevenueAnalyticsRepository
{
    /**
     * Get revenue statistics for dashboard KPIs
     */
    public function getRevenueStats(string $startDate = null, string $endDate = null): array
    {
        $query = Order::where('payment_status', 'paid');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalRevenue = $query->sum('total');
        $orderCount = $query->count();
        $avgOrderValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        // Get previous period for comparison
        $previousPeriod = $this->getPreviousPeriodRevenue($startDate, $endDate);

        return [
            'total_revenue' => round($totalRevenue, 2),
            'order_count' => $orderCount,
            'average_order_value' => round($avgOrderValue, 2),
            'growth_percentage' => $this->calculateGrowth($totalRevenue, $previousPeriod),
        ];
    }

    /**
     * Get daily revenue for the last N days
     */
    public function getDailyRevenue(int $days = 30): array
    {
        $cacheKey = "revenue_daily_{$days}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($days) {
            $data = Order::where('payment_status', 'paid')
                ->where('created_at', '>=', Carbon::now()->subDays($days))
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as revenue'),
                    DB::raw('SUM(gst_amount) as gst'),
                    DB::raw('COUNT(*) as orders')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return [
                'labels' => $data->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(),
                'revenue' => $data->pluck('revenue')->toArray(),
                'gst' => $data->pluck('gst')->toArray(),
                'orders' => $data->pluck('orders')->toArray(),
            ];
        });
    }

    /**
     * Get monthly revenue trends
     */
    public function getMonthlyRevenue(int $months = 12): array
    {
        $cacheKey = "revenue_monthly_{$months}";

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($months) {
            $data = Order::where('payment_status', 'paid')
                ->where('created_at', '>=', Carbon::now()->subMonths($months))
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('SUM(total) as revenue'),
                    DB::raw('SUM(gst_amount) as gst'),
                    DB::raw('COUNT(*) as orders')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            return [
                'labels' => $data->pluck('month')->map(fn($m) => Carbon::parse($m)->format('M Y'))->toArray(),
                'revenue' => $data->pluck('revenue')->toArray(),
                'gst' => $data->pluck('gst')->toArray(),
                'orders' => $data->pluck('orders')->toArray(),
            ];
        });
    }

    /**
     * Get GST breakdown by rate
     */
    public function getGstBreakdown(string $startDate = null, string $endDate = null): array
    {
        $query = Order::where('payment_status', 'paid')
            ->whereNotNull('gst_breakdown');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $orders = $query->get();

        $breakdown = [
            '5%' => 0,
            '12%' => 0,
            '18%' => 0,
            '28%' => 0,
        ];

        foreach ($orders as $order) {
            $gstData = $order->gst_breakdown;
            if (is_array($gstData)) {
                foreach ($gstData as $rate => $amount) {
                    if (isset($breakdown[$rate])) {
                        $breakdown[$rate] += $amount;
                    }
                }
            }
        }

        return [
            'labels' => array_keys($breakdown),
            'values' => array_values($breakdown),
        ];
    }

    /**
     * Get payment method distribution
     */
    public function getPaymentMethodDistribution(string $startDate = null, string $endDate = null): array
    {
        $query = Order::where('payment_status', 'paid');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data = $query->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('payment_method')
            ->get();

        return [
            'labels' => $data->pluck('payment_method')->map(fn($pm) => ucfirst($pm ?? 'Unknown'))->toArray(),
            'orders' => $data->pluck('count')->toArray(),
            'revenue' => $data->pluck('revenue')->toArray(),
        ];
    }

    /**
     * Get revenue by order status
     */
    public function getRevenueByStatus(string $startDate = null, string $endDate = null): array
    {
        $query = Order::where('payment_status', 'paid');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data = $query->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $data->pluck('status')->map(fn($s) => ucfirst($s))->toArray(),
            'orders' => $data->pluck('count')->toArray(),
            'revenue' => $data->pluck('revenue')->toArray(),
        ];
    }

    /**
     * Calculate growth percentage
     */
    private function calculateGrowth(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    /**
     * Get previous period revenue for comparison
     */
    private function getPreviousPeriodRevenue(string $startDate = null, string $endDate = null): float
    {
        if (!$startDate || !$endDate) {
            // Default to previous 30 days
            $days = 30;
            return Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [
                    Carbon::now()->subDays($days * 2),
                    Carbon::now()->subDays($days)
                ])
                ->sum('total');
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $diffDays = $start->diffInDays($end);

        return Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [
                $start->copy()->subDays($diffDays),
                $start
            ])
            ->sum('total');
    }
}
