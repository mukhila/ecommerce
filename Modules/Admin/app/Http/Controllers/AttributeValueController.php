<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Attribute;
use Modules\Product\Models\AttributeValue;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributeValues = AttributeValue::with('attribute')->orderBy('id', 'desc')->paginate(10);
        return view('admin::attribute_values.index', compact('attributeValues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = Attribute::all();
        return view('admin::attribute_values.create', compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
        ]);

        AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
        ]);

        return redirect()->route('admin.attribute_values.index')->with('success', 'Attribute Value created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributes = Attribute::all();
        return view('admin::attribute_values.edit', compact('attributeValue', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
        ]);

        $attributeValue->update([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
        ]);

        return redirect()->route('admin.attribute_values.index')->with('success', 'Attribute Value updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributeValue->delete();
        return redirect()->route('admin.attribute_values.index')->with('success', 'Attribute Value deleted successfully.');
    }
}
