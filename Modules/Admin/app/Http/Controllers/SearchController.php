<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
        $this->middleware('auth:admin');
        $this->middleware('admin.role:admin,staff');
    }

    /**
     * Global search (AJAX endpoint)
     */
    public function global(Request $request)
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 5);

        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'message' => 'Please enter at least 2 characters',
            ]);
        }

        $results = $this->searchService->globalSearch($query, $limit);

        return response()->json([
            'results' => $results,
            'query' => $query,
        ]);
    }

    /**
     * Advanced order search page
     */
    public function orders(Request $request)
    {
        $query = $request->get('q', '');
        $filters = [
            'status' => $request->get('status'),
            'payment_status' => $request->get('payment_status'),
            'payment_method' => $request->get('payment_method'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'min_amount' => $request->get('min_amount'),
            'max_amount' => $request->get('max_amount'),
        ];

        // Remove null values
        $filters = array_filter($filters, fn($value) => !is_null($value) && $value !== '');

        $results = $this->searchService->searchOrders($query, 50, $filters);

        if ($request->ajax()) {
            return response()->json([
                'results' => $results,
                'count' => count($results),
            ]);
        }

        return view('admin::search.orders', [
            'results' => $results,
            'query' => $query,
            'filters' => $filters,
        ]);
    }

    /**
     * Advanced product search page
     */
    public function products(Request $request)
    {
        $query = $request->get('q', '');
        $filters = [
            'category_id' => $request->get('category_id'),
            'stock_status' => $request->get('stock_status'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'is_active' => $request->get('is_active'),
            'is_featured' => $request->get('is_featured'),
        ];

        // Remove null values
        $filters = array_filter($filters, fn($value) => !is_null($value) && $value !== '');

        $results = $this->searchService->searchProducts($query, 50, $filters);

        if ($request->ajax()) {
            return response()->json([
                'results' => $results,
                'count' => count($results),
            ]);
        }

        // Get categories for filter dropdown
        $categories = \Modules\Product\Models\Category::orderBy('name')->get();

        return view('admin::search.products', [
            'results' => $results,
            'query' => $query,
            'filters' => $filters,
            'categories' => $categories,
        ]);
    }

    /**
     * Advanced customer search page
     */
    public function customers(Request $request)
    {
        $query = $request->get('q', '');
        $filters = [
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'min_spent' => $request->get('min_spent'),
            'max_spent' => $request->get('max_spent'),
            'min_orders' => $request->get('min_orders'),
        ];

        // Remove null values
        $filters = array_filter($filters, fn($value) => !is_null($value) && $value !== '');

        $results = $this->searchService->searchCustomers($query, 50, $filters);

        if ($request->ajax()) {
            return response()->json([
                'results' => $results,
                'count' => count($results),
            ]);
        }

        return view('admin::search.customers', [
            'results' => $results,
            'query' => $query,
            'filters' => $filters,
        ]);
    }

    /**
     * Support ticket search page
     */
    public function tickets(Request $request)
    {
        $query = $request->get('q', '');
        $filters = [
            'status' => $request->get('status'),
            'priority' => $request->get('priority'),
            'assigned_to' => $request->get('assigned_to'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        // Remove null values
        $filters = array_filter($filters, fn($value) => !is_null($value) && $value !== '');

        $results = $this->searchService->searchTickets($query, 50, $filters);

        if ($request->ajax()) {
            return response()->json([
                'results' => $results,
                'count' => count($results),
            ]);
        }

        return view('admin::search.tickets', [
            'results' => $results,
            'query' => $query,
            'filters' => $filters,
        ]);
    }

    /**
     * Search suggestions (autocomplete)
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = $this->searchService->getSuggestions($query, $type);

        return response()->json($suggestions);
    }
}
