<?php

namespace App\Http\Controllers;

use Modules\Product\Models\Product;
use Modules\Product\Models\Category;
use Modules\Admin\Models\Page;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->select('slug', 'updated_at')
            ->get();

        $categories = Category::select('slug', 'updated_at')->get();

        $pages = Page::select('slug', 'updated_at')->get();

        return response()->view('sitemap', compact('products', 'categories', 'pages'))
            ->header('Content-Type', 'application/xml');
    }
}
