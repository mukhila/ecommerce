<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Product;
use Modules\Product\Models\Category;
use Modules\Product\Models\ProductImage;
use Modules\Product\Models\Attribute;
use Modules\Product\Models\ProductAttribute;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'images'])->orderBy('id', 'desc')->paginate(10);
        return view('admin::products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $attributes = Attribute::with('values')->get();
        return view('admin::products.create', compact('categories', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes.*.*.stock' => 'nullable|integer|min:0',
            'attributes.*.*.price' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'stock' => $request->stock,
                'is_active' => $request->input('is_active', 1),
                'is_featured' => $request->input('is_featured', 0),
            ]);

            // Save Attributes
            // Save Attributes
            // 1. Handle legacy/simple single-select attributes
            if ($request->has('attribute_values')) {
                foreach ($request->attribute_values as $attributeId => $valueId) {
                     if($valueId) {
                        ProductAttribute::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => $valueId
                        ]);
                    }
                }
            }

            // 2. Handle multi-select attributes with metadata (e.g. Size with stock/price)
            if ($request->has('attributes')) {
                foreach ($request->input('attributes') as $attributeId => $values) {
                    foreach ($values as $valueId => $data) {
                         if (isset($data['enabled']) && $data['enabled']) {
                             ProductAttribute::create([
                                'product_id' => $product->id,
                                'attribute_id' => $attributeId,
                                'attribute_value_id' => $valueId,
                                'stock' => isset($data['stock']) && $data['stock'] !== '' ? $data['stock'] : null,
                                'price' => isset($data['price']) && $data['price'] !== '' ? $data['price'] : null,
                             ]);
                         }
                    }
                }
            }

            // Upload Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $i === 0, // First image is primary by default if we strictly iterate
                        'sort_order' => $i,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['category', 'images', 'attributes.attribute', 'attributes.attributeValue'])->findOrFail($id);
        return view('admin::products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::with(['images', 'attributes'])->findOrFail($id);
        $categories = Category::all();
        $attributes = Attribute::with('values')->get();
        // Map product attributes for easier access in view
        // Map product attributes for easier access in view
        $productAttributes = $product->attributes->groupBy('attribute_id')->map(function ($items) {
             return $items->keyBy('attribute_value_id')->toArray();
        })->toArray();

        return view('admin::products.edit', compact('product', 'categories', 'attributes', 'productAttributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes.*.*.stock' => 'nullable|integer|min:0',
            'attributes.*.*.price' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);

            $slug = Str::slug($request->name);
            if ($product->slug !== $slug) {
                $originalSlug = $slug;
                $count = 1;
                while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                     $slug = $originalSlug . '-' . $count++;
                }
            } else {
                 $slug = $product->slug;
            }

            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'stock' => $request->stock,
                'is_active' => $request->input('is_active', 1),
                'is_featured' => $request->input('is_featured', 0),
            ]);

            // Update Attributes
            $product->attributes()->delete();
            
            // 1. Handle legacy/simple single-select attributes
            if ($request->has('attribute_values')) {
                foreach ($request->attribute_values as $attributeId => $valueId) {
                    if($valueId) {
                        ProductAttribute::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => $valueId
                        ]);
                    }
                }
            }

            // 2. Handle multi-select attributes with metadata (e.g. Size with stock/price)
            if ($request->has('attributes')) {
                foreach ($request->input('attributes') as $attributeId => $values) {
                    foreach ($values as $valueId => $data) {
                         if (isset($data['enabled']) && $data['enabled']) {
                             ProductAttribute::create([
                                'product_id' => $product->id,
                                'attribute_id' => $attributeId,
                                'attribute_value_id' => $valueId,
                                'stock' => isset($data['stock']) && $data['stock'] !== '' ? $data['stock'] : null,
                                'price' => isset($data['price']) && $data['price'] !== '' ? $data['price'] : null,
                             ]);
                         }
                    }
                }
            }

             // Upload New Images
             if ($request->hasFile('images')) {
                 $nextSortOrder = $product->images()->max('sort_order') + 1;
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => false,
                        'sort_order' => $nextSortOrder++,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // Optionally delete actual image files here if needed
        foreach($product->images as $image) {
            if(Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
    
    public function destroyImage($id) {
        $image = ProductImage::findOrFail($id);
        if(Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();
        return back()->with('success', 'Image deleted successfully');
    }
}
