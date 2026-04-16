<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Product\Models\Product;

class ProductSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q', ''));

        if ($query === '') {
            return view('search.index', [
                'query'    => '',
                'products' => null,
            ]);
        }

        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('slug', 'like', '%' . $query . '%')
                  ->orWhereHas('category', function ($cq) use ($query) {
                      $cq->where('name', 'like', '%' . $query . '%');
                  });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('search.index', compact('query', 'products'));
    }
}
