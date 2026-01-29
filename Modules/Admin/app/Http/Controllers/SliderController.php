<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->get();
        return view('admin::sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $input = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('sliders', 'public_uploads');
            $input['image'] = $path;
        }
        
        $input['status'] = $request->has('status') ? 1 : 0;

        Slider::create($input);

        return redirect()->route('admin.sliders.index')
                        ->with('success', 'Slider created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::sliders.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $slider = Slider::find($id);
        return view('admin::sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'link' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $slider = Slider::find($id);
        $input = $request->all();

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            // Delete old image
            if ($slider->image && Storage::disk('public_uploads')->exists($slider->image)) {
                Storage::disk('public_uploads')->delete($slider->image);
            }

            $path = $request->file('image')->store('sliders', 'public_uploads');
            $input['image'] = $path;
        }

        $input['status'] = $request->has('status') ? 1 : 0;

        $slider->update($input);

        return redirect()->route('admin.sliders.index')
                        ->with('success', 'Slider updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);
        if ($slider->image && Storage::disk('public_uploads')->exists($slider->image)) {
            Storage::disk('public_uploads')->delete($slider->image);
        }
        $slider->delete();

        return redirect()->route('admin.sliders.index')
                        ->with('success', 'Slider deleted successfully');
    }
}
