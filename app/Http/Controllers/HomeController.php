<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Models\Slider;
use Modules\Product\Models\Product;
use Modules\Product\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch sliders
        $sliders = Slider::where('status', 1)->orderBy('sort_order')->get();

        // Fetch featured products (5 products)
        $featuredProducts = Product::with(['category', 'images'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->limit(8)
            ->get();

        // Fetch latest products (8 products for "Latest Drops")
        $latestProducts = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->latest()
            ->limit(8)
            ->get();

        // Fetch products by category for tabs
        $dressesCategory = Category::where('slug', 'womens-dresses')->first();
        $topsCategory = Category::where('slug', 'womens-tops')->first();
        $jeansCategory = Category::where('slug', 'womens-jeans')->first();

        $dresses = $dressesCategory ? Product::with(['category', 'images'])
            ->where('category_id', $dressesCategory->id)
            ->where('is_active', true)
            ->limit(8)
            ->get() : collect();

        $tops = $topsCategory ? Product::with(['category', 'images'])
            ->where('category_id', $topsCategory->id)
            ->where('is_active', true)
            ->limit(8)
            ->get() : collect();

        $winterWear = $jeansCategory ? Product::with(['category', 'images'])
            ->where('category_id', $jeansCategory->id)
            ->where('is_active', true)
            ->limit(8)
            ->get() : collect();

        return view('frontend.index', compact(
            'sliders',
            'featuredProducts',
            'latestProducts',
            'dresses',
            'tops',
            'winterWear'
        ));
    }
}
