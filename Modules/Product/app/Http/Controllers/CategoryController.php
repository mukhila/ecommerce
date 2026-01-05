<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->get();

        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('product::category.index', compact('categories', 'products'));
    }

    /**
     * Display products for a specific category
     */
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Get all child category IDs if this category has children
        $categoryIds = [$category->id];
        if ($category->children->count() > 0) {
            $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
        }

        // Get all categories for the slider
        $allCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->get();

        // Build products query
        $productsQuery = Product::with(['category', 'images', 'attributes.attribute', 'attributes.attributeValue'])
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true);

        // Filter by attributes if provided
        if ($request->has('attributes') && is_array($request->attributes)) {
            foreach ($request->attributes as $attributeValueId) {
                $productsQuery->whereHas('attributes', function($query) use ($attributeValueId) {
                    $query->where('attribute_value_id', $attributeValueId);
                });
            }
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price-low':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price-high':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'name':
                $productsQuery->orderBy('name', 'asc');
                break;
            default:
                $productsQuery->latest();
        }

        $products = $productsQuery->paginate(12);

        // Get all attributes with their values that are used in products in this category
        $attributesWithValues = \DB::table('attributes')
            ->select('attributes.id', 'attributes.name', 'attributes.slug')
            ->join('product_attributes', 'attributes.id', '=', 'product_attributes.attribute_id')
            ->join('products', 'product_attributes.product_id', '=', 'products.id')
            ->whereIn('products.category_id', $categoryIds)
            ->where('products.is_active', true)
            ->distinct()
            ->get()
            ->map(function($attribute) use ($categoryIds) {
                // Get all values for this attribute used in products in this category
                $values = \DB::table('attribute_values')
                    ->select('attribute_values.id', 'attribute_values.value')
                    ->join('product_attributes', 'attribute_values.id', '=', 'product_attributes.attribute_value_id')
                    ->join('products', 'product_attributes.product_id', '=', 'products.id')
                    ->where('attribute_values.attribute_id', $attribute->id)
                    ->whereIn('products.category_id', $categoryIds)
                    ->where('products.is_active', true)
                    ->distinct()
                    ->get();

                $attribute->values = $values;
                return $attribute;
            })
            ->filter(function($attribute) {
                return $attribute->values->count() > 0;
            });

        return view('product::category.show', compact('category', 'allCategories', 'products', 'attributesWithValues'));
    }
}
