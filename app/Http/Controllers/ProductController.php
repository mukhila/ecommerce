<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductAttribute;

class ProductController extends Controller
{
    /**
     * Display product detail page
     */
    public function show(string $slug)
    {
        $product = Product::with([
            'category',
            'images' => fn($q) => $q->orderBy('sort_order'),
            'sizeAttributes' => fn($q) => $q->active()->with('attributeValue'),
            'approvedReviews.user',
            'approvedReviews.images',
        ])->where('slug', $slug)
          ->where('is_active', true)
          ->firstOrFail();

        // Get size variations with stock info
        $sizeVariations = $product->sizeAttributes()
            ->active()
            ->with('attributeValue')
            ->get()
            ->map(fn($v) => [
                'id' => $v->id,
                'size' => $v->attributeValue->value,
                'stock' => $v->stock ?? 0,
                'price' => $v->price,
                'effective_price' => $v->effective_price,
                'is_available' => $v->isAvailable(),
            ]);

        // Find first available size
        $firstAvailable = $sizeVariations->firstWhere('is_available', true);

        // Related products
        $relatedProducts = Product::with(['images', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact(
            'product',
            'sizeVariations',
            'firstAvailable',
            'relatedProducts'
        ));
    }

    /**
     * Get variation details (AJAX)
     */
    public function getVariation(Request $request)
    {
        $request->validate([
            'variation_id' => 'required|exists:product_attributes,id'
        ]);

        $variation = ProductAttribute::with(['product', 'attributeValue'])
            ->findOrFail($request->variation_id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $variation->id,
                'size' => $variation->attributeValue->value,
                'stock' => $variation->stock,
                'price' => $variation->effective_price,
                'is_available' => $variation->isAvailable(),
                'formatted_price' => '₹' . number_format($variation->effective_price, 2),
            ]
        ]);
    }
}
