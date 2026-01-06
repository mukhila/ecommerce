<?php

namespace App\Repositories\Analytics;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OrderAnalyticsRepository
{
    /**
     * Get order statistics
     */
    public function getOrderStatistics(string $startDate = null, string $endDate = null): array
    {
        $query = Order::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalOrders = $query->count();
        $paidOrders = (clone $query)->where('payment_status', 'paid')->count();
        $pendingOrders = (clone $query)->where('status', 'pending')->count();
        $processingOrders = (clone $query)->where('status', 'processing')->count();
        $shippedOrders = (clone $query)->where('status', 'shipped')->count();
        $deliveredOrders = (clone $query)->where('status', 'delivered')->count();
        $cancelledOrders = (clone $query)->where('status', 'cancelled')->count();

        // Calculate rates
        $cancellationRate = $totalOrders > 0
            ? round(($cancelledOrders / $totalOrders) * 100, 2)
            : 0;

        $fulfillmentRate = $totalOrders > 0
            ? round(($deliveredOrders / $totalOrders) * 100, 2)
            : 0;

        $paymentSuccessRate = $totalOrders > 0
            ? round(($paidOrders / $totalOrders) * 100, 2)
            : 0;

        return [
            'total_orders' => $totalOrders,
            'paid_orders' => $paidOrders,
            'pending_orders' => $pendingOrders,
            'processing_orders' => $processingOrders,
            'shipped_orders' => $shippedOrders,
            'delivered_orders' => $deliveredOrders,
            'cancelled_orders' => $cancelledOrders,
            'cancellation_rate' => $cancellationRate,
            'fulfillment_rate' => $fulfillmentRate,
            'payment_success_rate' => $paymentSuccessRate,
        ];
    }

    /**
     * Get order status distribution
     */
    public function getOrderStatusDistribution(string $startDate = null, string $endDate = null): array
    {
        $query = Order::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data = $query->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $data->pluck('status')->map(fn($s) => ucfirst($s))->toArray(),
            'values' => $data->pluck('count')->toArray(),
        ];
    }

    /**
     * Get average order processing time
     */
    public function getAverageProcessingTime(): array
    {
        $cacheKey = 'avg_processing_time';

        return Cache::remember($cacheKey, now()->addMinutes(15), function () {
            // Time from pending to processing
            $pendingToProcessing = Order::whereNotNull('updated_at')
                ->where('status', 'processing')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
                ->value('avg_hours');

            // Time from processing to shipped
            $processingToShipped = Order::whereNotNull('updated_at')
                ->where('status', 'shipped')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
                ->value('avg_hours');

            // Time from shipped to delivered
            $shippedToDelivered = Order::whereNotNull('updated_at')
                ->where('status', 'delivered')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
                ->value('avg_hours');

            // Total time from created to delivered
            $totalTime = Order::where('status', 'delivered')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
                ->value('avg_hours');

            return [
                'pending_to_processing_hours' => round($pendingToProcessing ?? 0, 2),
                'processing_to_shipped_hours' => round($processingToShipped ?? 0, 2),
                'shipped_to_delivered_hours' => round($shippedToDelivered ?? 0, 2),
                'total_fulfillment_hours' => round($totalTime ?? 0, 2),
                'total_fulfillment_days' => round(($totalTime ?? 0) / 24, 2),
            ];
        });
    }

    /**
     * Get orders by payment status
     */
    public function getOrdersByPaymentStatus(string $startDate = null, string $endDate = null): array
    {
        $query = Order::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data = $query->select(
                'payment_status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total_amount')
            )
            ->groupBy('payment_status')
            ->get();

        return [
            'labels' => $data->pluck('payment_status')->map(fn($ps) => ucfirst($ps))->toArray(),
            'orders' => $data->pluck('count')->toArray(),
            'amounts' => $data->pluck('total_amount')->toArray(),
        ];
    }

    /**
     * Get delivery performance metrics
     */
    public function getDeliveryPerformance(): array
    {
        $cacheKey = 'delivery_performance';

        return Cache::remember($cacheKey, now()->addMinutes(15), function () {
            $totalShipped = Order::where('status', 'shipped')->count();
            $totalDelivered = Order::where('status', 'delivered')->count();

            // On-time delivery rate (delivered within expected time)
            // Assuming expected delivery is 7 days from order creation
            $onTimeDeliveries = Order::where('status', 'delivered')
                ->whereRaw('TIMESTAMPDIFF(DAY, created_at, updated_at) <= 7')
                ->count();

            $onTimeRate = $totalDelivered > 0
                ? round(($onTimeDeliveries / $totalDelivered) * 100, 2)
                : 0;

            return [
                'total_shipped' => $totalShipped,
                'total_delivered' => $totalDelivered,
                'on_time_deliveries' => $onTimeDeliveries,
                'on_time_delivery_rate' => $onTimeRate,
            ];
        });
    }

    /**
     * Get cancellation reasons breakdown
     */
    public function getCancellationReasons(int $limit = 10): array
    {
        $cacheKey = "cancellation_reasons_{$limit}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($limit) {
            $reasons = Order::where('status', 'cancelled')
                ->whereNotNull('cancellation_reason')
                ->select('cancellation_reason', DB::raw('COUNT(*) as count'))
                ->groupBy('cancellation_reason')
                ->orderByDesc('count')
                ->limit($limit)
                ->get();

            return [
                'labels' => $reasons->pluck('cancellation_reason')->toArray(),
                'values' => $reasons->pluck('count')->toArray(),
            ];
        });
    }

    /**
     * Get order trends over time
     */
    public function getOrderTrends(string $period = 'daily', int $limit = 30): array
    {
        $cacheKey = "order_trends_{$period}_{$limit}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($period, $limit) {
            $dateFormat = $period === 'daily' ? '%Y-%m-%d' : '%Y-%m';
            $dateColumn = $period === 'daily' ? 'DATE(created_at)' : 'DATE_FORMAT(created_at, "%Y-%m")';

            $data = Order::where('created_at', '>=', $period === 'daily'
                    ? Carbon::now()->subDays($limit)
                    : Carbon::now()->subMonths($limit))
                ->select(
                    DB::raw("{$dateColumn} as period"),
                    DB::raw('COUNT(*) as total_orders'),
                    DB::raw('SUM(CASE WHEN payment_status = "paid" THEN 1 ELSE 0 END) as paid_orders'),
                    DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_orders')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            return [
                'labels' => $data->pluck('period')->map(function ($date) use ($period) {
                    return $period === 'daily'
                        ? Carbon::parse($date)->format('M d')
                        : Carbon::parse($date)->format('M Y');
                })->toArray(),
                'total_orders' => $data->pluck('total_orders')->toArray(),
                'paid_orders' => $data->pluck('paid_orders')->toArray(),
                'cancelled_orders' => $data->pluck('cancelled_orders')->toArray(),
            ];
        });
    }

    /**
     * Get hourly order distribution (peak hours analysis)
     */
    public function getHourlyOrderDistribution(): array
    {
        $cacheKey = 'hourly_order_distribution';

        return Cache::remember($cacheKey, now()->addMinutes(30), function () {
            $data = Order::where('created_at', '>=', Carbon::now()->subDays(30))
                ->select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            // Fill in missing hours with 0
            $hours = range(0, 23);
            $distribution = array_fill(0, 24, 0);

            foreach ($data as $row) {
                $distribution[$row->hour] = $row->count;
            }

            return [
                'labels' => array_map(fn($h) => sprintf('%02d:00', $h), $hours),
                'values' => $distribution,
            ];
        });
    }

    /**
     * Get order value distribution (categorize by price ranges)
     */
    public function getOrderValueDistribution(): array
    {
        $cacheKey = 'order_value_distribution';

        return Cache::remember($cacheKey, now()->addMinutes(15), function () {
            $ranges = [
                '0-500' => Order::whereBetween('total', [0, 500])->count(),
                '501-1000' => Order::whereBetween('total', [501, 1000])->count(),
                '1001-2000' => Order::whereBetween('total', [1001, 2000])->count(),
                '2001-5000' => Order::whereBetween('total', [2001, 5000])->count(),
                '5000+' => Order::where('total', '>', 5000)->count(),
            ];

            return [
                'labels' => array_keys($ranges),
                'values' => array_values($ranges),
            ];
        });
    }
}
