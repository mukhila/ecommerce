<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Modules\Product\Models\Product;
use Modules\Admin\Models\SupportTicket;
use Illuminate\Support\Facades\DB;

class SearchService
{
    /**
     * Global search across all entities
     */
    public function globalSearch(string $query, int $limit = 5): array
    {
        if (strlen($query) < 2) {
            return [
                'orders' => [],
                'products' => [],
                'customers' => [],
                'tickets' => [],
            ];
        }

        return [
            'orders' => $this->searchOrders($query, $limit),
            'products' => $this->searchProducts($query, $limit),
            'customers' => $this->searchCustomers($query, $limit),
            'tickets' => $this->searchTickets($query, $limit),
        ];
    }

    /**
     * Advanced order search with filters
     */
    public function searchOrders(string $query = null, int $limit = 20, array $filters = []): array
    {
        $queryBuilder = Order::with(['user', 'shippingAddress']);

        // Text search
        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('id', 'LIKE', "%{$query}%")
                  ->orWhere('razorpay_order_id', 'LIKE', "%{$query}%")
                  ->orWhere('tracking_number', 'LIKE', "%{$query}%")
                  ->orWhereHas('user', function ($q) use ($query) {
                      $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('email', 'LIKE', "%{$query}%");
                  });
            });
        }

        // Apply filters
        if (!empty($filters['status'])) {
            $queryBuilder->where('status', $filters['status']);
        }

        if (!empty($filters['payment_status'])) {
            $queryBuilder->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['payment_method'])) {
            $queryBuilder->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['date_from'])) {
            $queryBuilder->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $queryBuilder->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }

        if (!empty($filters['min_amount'])) {
            $queryBuilder->where('total', '>=', $filters['min_amount']);
        }

        if (!empty($filters['max_amount'])) {
            $queryBuilder->where('total', '<=', $filters['max_amount']);
        }

        $results = $queryBuilder->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $results->map(function ($order) {
            return [
                'id' => $order->id,
                'customer_name' => $order->user->name ?? 'Guest',
                'customer_email' => $order->user->email ?? 'N/A',
                'total' => $order->total,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'created_at' => $order->created_at->format('M d, Y H:i'),
                'url' => route('admin.orders.show', $order->id),
            ];
        })->toArray();
    }

    /**
     * Advanced product search with filters
     */
    public function searchProducts(string $query = null, int $limit = 20, array $filters = []): array
    {
        $queryBuilder = Product::with('category');

        // Text search
        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('slug', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            });
        }

        // Apply filters
        if (!empty($filters['category_id'])) {
            $queryBuilder->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['stock_status'])) {
            switch ($filters['stock_status']) {
                case 'in_stock':
                    $queryBuilder->where('stock', '>', 10);
                    break;
                case 'low_stock':
                    $queryBuilder->whereBetween('stock', [1, 10]);
                    break;
                case 'out_of_stock':
                    $queryBuilder->where('stock', '=', 0);
                    break;
            }
        }

        if (!empty($filters['min_price'])) {
            $queryBuilder->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $queryBuilder->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['is_active'])) {
            $queryBuilder->where('is_active', $filters['is_active']);
        }

        if (isset($filters['is_featured'])) {
            $queryBuilder->where('is_featured', $filters['is_featured']);
        }

        $results = $queryBuilder->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $results->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'category' => $product->category->name ?? 'N/A',
                'price' => $product->price,
                'stock' => $product->stock,
                'is_active' => $product->is_active,
                'is_featured' => $product->is_featured,
                'url' => route('admin.products.edit', $product->id),
            ];
        })->toArray();
    }

    /**
     * Advanced customer search with filters
     */
    public function searchCustomers(string $query = null, int $limit = 20, array $filters = []): array
    {
        $queryBuilder = User::withCount('orders')
            ->withSum('orders as total_spent', 'total');

        // Text search
        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('mobile', 'LIKE', "%{$query}%");
            });
        }

        // Apply filters
        if (!empty($filters['date_from'])) {
            $queryBuilder->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $queryBuilder->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }

        if (!empty($filters['min_spent'])) {
            $queryBuilder->having('total_spent', '>=', $filters['min_spent']);
        }

        if (!empty($filters['max_spent'])) {
            $queryBuilder->having('total_spent', '<=', $filters['max_spent']);
        }

        if (!empty($filters['min_orders'])) {
            $queryBuilder->having('orders_count', '>=', $filters['min_orders']);
        }

        $results = $queryBuilder->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $results->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'orders_count' => $user->orders_count,
                'total_spent' => round($user->total_spent ?? 0, 2),
                'created_at' => $user->created_at->format('M d, Y'),
            ];
        })->toArray();
    }

    /**
     * Support ticket search with filters
     */
    public function searchTickets(string $query = null, int $limit = 20, array $filters = []): array
    {
        $queryBuilder = SupportTicket::with('user');

        // Text search
        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('ticket_number', 'LIKE', "%{$query}%")
                  ->orWhere('subject', 'LIKE', "%{$query}%")
                  ->orWhere('message', 'LIKE', "%{$query}%")
                  ->orWhereHas('user', function ($q) use ($query) {
                      $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('email', 'LIKE', "%{$query}%");
                  });
            });
        }

        // Apply filters
        if (!empty($filters['status'])) {
            $queryBuilder->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $queryBuilder->where('priority', $filters['priority']);
        }

        if (!empty($filters['assigned_to'])) {
            $queryBuilder->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['date_from'])) {
            $queryBuilder->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $queryBuilder->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }

        $results = $queryBuilder->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $results->map(function ($ticket) {
            return [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'priority' => $ticket->priority,
                'customer_name' => $ticket->user->name ?? 'Unknown',
                'created_at' => $ticket->created_at->format('M d, Y H:i'),
                'url' => route('admin.support.show', $ticket->ticket_number),
            ];
        })->toArray();
    }

    /**
     * Get search suggestions (autocomplete)
     */
    public function getSuggestions(string $query, string $type = 'all', int $limit = 10): array
    {
        $suggestions = [];

        if ($type === 'all' || $type === 'products') {
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('sku', 'LIKE', "%{$query}%")
                ->limit($limit)
                ->pluck('name')
                ->toArray();

            $suggestions = array_merge($suggestions, $products);
        }

        if ($type === 'all' || $type === 'customers') {
            $customers = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->limit($limit)
                ->pluck('name')
                ->toArray();

            $suggestions = array_merge($suggestions, $customers);
        }

        return array_values(array_unique($suggestions));
    }
}
