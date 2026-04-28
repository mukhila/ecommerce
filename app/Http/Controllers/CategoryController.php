<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with(['parent', 'children'])
            ->firstOrFail();

        // Collect this category + all active descendant IDs
        $categoryIds = $this->gatherIds($category);

        $query = Product::with(['images', 'category'])
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true);

        // Sort
        $sort = $request->query('sort', 'newest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $products = $query->paginate(16)->withQueryString();

        return view('categories.show', compact('category', 'products', 'sort'));
    }

    private function gatherIds(Category $category): array
    {
        $ids = [$category->id];
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            foreach ($child->children()->get() as $grandChild) {
                $ids[] = $grandChild->id;
            }
        }
        return $ids;
    }
}
