<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filter   = $request->query('filter');
        $search   = $request->query('search');
        $sort     = $request->query('sort', 'latest');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $query = Product::with(['category', 'images'])
            ->where('is_active', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        if ($filter === 'sale') {
            $query->whereNotNull('sale_price');
        } elseif ($filter === 'featured') {
            $query->where('is_featured', true);
        }

        if (is_numeric($minPrice)) {
            $query->where('price', '>=', (float) $minPrice);
        }
        if (is_numeric($maxPrice) && $maxPrice > 0) {
            $query->where('price', '<=', (float) $maxPrice);
        }

        switch ($sort) {
            case 'price-low':  $query->orderBy('price', 'asc'); break;
            case 'price-high': $query->orderBy('price', 'desc'); break;
            case 'name':       $query->orderBy('name', 'asc'); break;
            default:           $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->get();

        $priceRange = Product::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        $pageTitle = $search
            ? 'Results for "' . $search . '"'
            : match ($filter) {
                'sale'     => 'Sale',
                'featured' => 'Featured Products',
                'new'      => 'New Arrivals',
                default    => 'All Products',
            };

        return view('product::index', compact(
            'products', 'categories', 'filter', 'search', 'sort', 'priceRange', 'minPrice', 'maxPrice', 'pageTitle'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show(Product $product)
    {
        // Load all relationships
        $product->load([
            'category',
            'images',
            'attributes.attribute',
            'attributes.attributeValue'
        ]);

        // Get related products from the same category
        $relatedProducts = Product::with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('product::show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('product::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
