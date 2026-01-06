<?php

namespace App\Repositories\Analytics;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CustomerAnalyticsRepository
{
    /**
     * Get customer statistics
     */
    public function getCustomerStats(string $startDate = null, string $endDate = null): array
    {
        $totalCustomers = User::count();

        $query = User::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $newCustomers = $query->count();

        // Get customers with multiple orders (returning customers)
        $returningCustomers = Order::select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        // Calculate retention rate
        $retentionRate = $totalCustomers > 0
            ? round(($returningCustomers / $totalCustomers) * 100, 2)
            : 0;

        return [
            'total_customers' => $totalCustomers,
            'new_customers' => $newCustomers,
            'returning_customers' => $returningCustomers,
            'retention_rate' => $retentionRate,
        ];
    }

    /**
     * Get customer lifetime value (top customers by total spent)
     */
    public function getCustomerLifetimeValue(int $limit = 10): array
    {
        $cacheKey = "customer_ltv_{$limit}";

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($limit) {
            $customers = Order::join('users', 'orders.user_id', '=', 'users.id')
                ->where('orders.payment_status', 'paid')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    DB::raw('SUM(orders.total) as lifetime_value'),
                    DB::raw('COUNT(orders.id) as total_orders'),
                    DB::raw('AVG(orders.total) as avg_order_value'),
                    DB::raw('MIN(orders.created_at) as first_order_date'),
                    DB::raw('MAX(orders.created_at) as last_order_date')
                )
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderByDesc('lifetime_value')
                ->limit($limit)
                ->get();

            return $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'lifetime_value' => round($customer->lifetime_value, 2),
                    'total_orders' => $customer->total_orders,
                    'avg_order_value' => round($customer->avg_order_value, 2),
                    'first_order' => Carbon::parse($customer->first_order_date)->format('M d, Y'),
                    'last_order' => Carbon::parse($customer->last_order_date)->format('M d, Y'),
                ];
            })->toArray();
        });
    }

    /**
     * Get customer acquisition trends
     */
    public function getCustomerAcquisitionTrend(int $months = 12): array
    {
        $cacheKey = "customer_acquisition_{$months}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($months) {
            $data = User::where('created_at', '>=', Carbon::now()->subMonths($months))
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            return [
                'labels' => $data->pluck('month')->map(fn($m) => Carbon::parse($m)->format('M Y'))->toArray(),
                'values' => $data->pluck('count')->toArray(),
            ];
        });
    }

    /**
     * Get top customers by spending
     */
    public function getTopCustomers(int $limit = 20, string $startDate = null, string $endDate = null): array
    {
        $query = Order::join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.payment_status', 'paid');

        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        $customers = $query->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('SUM(orders.total) as total_spent'),
                DB::raw('COUNT(orders.id) as order_count')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit($limit)
            ->get();

        return $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'total_spent' => round($customer->total_spent, 2),
                'order_count' => $customer->order_count,
            ];
        })->toArray();
    }

    /**
     * Get customer growth trends
     */
    public function getCustomerGrowthTrends(string $period = 'daily', int $limit = 30): array
    {
        $cacheKey = "customer_growth_{$period}_{$limit}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($period, $limit) {
            $dateFormat = $period === 'daily' ? '%Y-%m-%d' : '%Y-%m';
            $dateColumn = $period === 'daily' ? 'DATE(created_at)' : 'DATE_FORMAT(created_at, "%Y-%m")';

            $data = User::where('created_at', '>=', $period === 'daily'
                    ? Carbon::now()->subDays($limit)
                    : Carbon::now()->subMonths($limit))
                ->select(
                    DB::raw("{$dateColumn} as period"),
                    DB::raw('COUNT(*) as new_customers')
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
                'values' => $data->pluck('new_customers')->toArray(),
            ];
        });
    }

    /**
     * Get customer segmentation by order count
     */
    public function getCustomerSegmentation(): array
    {
        $cacheKey = 'customer_segmentation';

        return Cache::remember($cacheKey, now()->addMinutes(15), function () {
            $segments = [
                'one_time' => Order::select('user_id')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(*) = 1')
                    ->count(),
                'repeat' => Order::select('user_id')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(*) BETWEEN 2 AND 5')
                    ->count(),
                'loyal' => Order::select('user_id')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(*) > 5')
                    ->count(),
            ];

            return [
                'labels' => ['One-time Buyers', 'Repeat Customers', 'Loyal Customers'],
                'values' => array_values($segments),
            ];
        });
    }

    /**
     * Get customer geographic distribution (by state/city from shipping addresses)
     */
    public function getGeographicDistribution(int $limit = 10): array
    {
        $cacheKey = "customer_geography_{$limit}";

        return Cache::remember($cacheKey, now()->addMinutes(20), function () use ($limit) {
            $distribution = Order::join('shipping_addresses', 'orders.shipping_address_id', '=', 'shipping_addresses.id')
                ->where('orders.payment_status', 'paid')
                ->select(
                    'shipping_addresses.state',
                    DB::raw('COUNT(DISTINCT orders.user_id) as customer_count'),
                    DB::raw('SUM(orders.total) as total_revenue')
                )
                ->groupBy('shipping_addresses.state')
                ->orderByDesc('customer_count')
                ->limit($limit)
                ->get();

            return [
                'labels' => $distribution->pluck('state')->toArray(),
                'customers' => $distribution->pluck('customer_count')->toArray(),
                'revenue' => $distribution->pluck('total_revenue')->toArray(),
            ];
        });
    }
}
