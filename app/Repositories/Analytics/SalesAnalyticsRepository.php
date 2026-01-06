<?php

namespace App\Repositories\Analytics;

use Modules\Product\Models\Product;
use Modules\Product\Models\Category;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SalesAnalyticsRepository
{
    /**
     * Get top-selling products
     */
    public function getTopSellingProducts(int $limit = 10, string $startDate = null, string $endDate = null): array
    {
        $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->join('products', 'order_items.product_id', '=', 'products.id');

        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        $products = $query->select(
                'products.id',
                'products.name',
                'products.slug',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        return [
            'labels' => $products->pluck('name')->toArray(),
            'quantities' => $products->pluck('total_sold')->toArray(),
            'revenue' => $products->pluck('total_revenue')->toArray(),
            'products' => $products->toArray(),
        ];
    }

    /**
     * Get category performance
     */
    public function getCategoryPerformance(string $startDate = null, string $endDate = null): array
    {
        $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id');

        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        $categories = $query->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_items.product_id) as product_count')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        return [
            'labels' => $categories->pluck('name')->toArray(),
            'revenue' => $categories->pluck('total_revenue')->toArray(),
            'quantities' => $categories->pluck('total_sold')->toArray(),
            'products' => $categories->pluck('product_count')->toArray(),
        ];
    }

    /**
     * Get low stock alerts
     */
    public function getLowStockProducts(int $threshold = 10): array
    {
        return Cache::remember('low_stock_products', now()->addMinutes(5), function () use ($threshold) {
            return Product::where('is_active', true)
                ->where('stock', '<=', $threshold)
                ->where('stock', '>', 0)
                ->with('category')
                ->orderBy('stock')
                ->limit(20)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'stock' => $product->stock,
                        'category' => $product->category->name ?? 'N/A',
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Get out of stock products
     */
    public function getOutOfStockProducts(): array
    {
        return Cache::remember('out_of_stock_products', now()->addMinutes(5), function () {
            return Product::where('is_active', true)
                ->where('stock', '=', 0)
                ->with('category')
                ->limit(20)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'category' => $product->category->name ?? 'N/A',
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Get sales trends over time
     */
    public function getSalesTrends(string $period = 'daily', int $limit = 30): array
    {
        $cacheKey = "sales_trends_{$period}_{$limit}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($period, $limit) {
            $dateFormat = $period === 'daily' ? '%Y-%m-%d' : '%Y-%m';
            $dateColumn = $period === 'daily' ? 'DATE(orders.created_at)' : 'DATE_FORMAT(orders.created_at, "%Y-%m")';

            $data = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.payment_status', 'paid')
                ->where('orders.created_at', '>=', $period === 'daily'
                    ? Carbon::now()->subDays($limit)
                    : Carbon::now()->subMonths($limit))
                ->select(
                    DB::raw("{$dateColumn} as period"),
                    DB::raw('SUM(order_items.quantity) as total_quantity'),
                    DB::raw('COUNT(DISTINCT order_items.order_id) as order_count')
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
                'quantities' => $data->pluck('total_quantity')->toArray(),
                'orders' => $data->pluck('order_count')->toArray(),
            ];
        });
    }

    /**
     * Get sales by payment method
     */
    public function getSalesByPaymentMethod(string $startDate = null, string $endDate = null): array
    {
        $query = Order::where('payment_status', 'paid');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data = $query->select(
                'payment_method',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('payment_method')
            ->get();

        return [
            'labels' => $data->pluck('payment_method')->map(fn($pm) => ucfirst($pm ?? 'Unknown'))->toArray(),
            'orders' => $data->pluck('order_count')->toArray(),
            'revenue' => $data->pluck('revenue')->toArray(),
        ];
    }
}
