<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Attribute;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::orderBy('id', 'desc')->paginate(10);
        return view('admin::attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
        ]);

        $slug = Str::slug($request->name);
        
        Attribute::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $attribute = Attribute::findOrFail($id);
        return view('admin::attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,' . $id,
        ]);

        $slug = Str::slug($request->name);

        $attribute->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted successfully.');
    }
}
