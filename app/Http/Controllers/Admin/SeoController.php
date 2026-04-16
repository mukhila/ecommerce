<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seo;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seos = Seo::all();
        return view('admin::seo.index', compact('seos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->getName();
        })->filter()->unique()->values();

        return view('admin::seo.create', compact('routes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'route_name' => 'required|unique:seos',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'robots' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'type' => 'nullable|in:website,article,product',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/seo'), $imageName);
            $data['image'] = 'uploads/seo/' . $imageName;
        }

        Seo::create($data);

        return redirect()->route('admin.seo.index')->with('success', 'SEO entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Seo $seo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seo $seo)
    {
         $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->getName();
        })->filter()->unique()->values();
        return view('admin::seo.edit', compact('seo', 'routes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seo $seo)
    {
        $request->validate([
            'route_name' => 'required|unique:seos,route_name,' . $seo->id,
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'robots' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'type' => 'nullable|in:website,article,product',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/seo'), $imageName);
            $data['image'] = 'uploads/seo/' . $imageName;
        }

        $seo->update($data);

        return redirect()->route('admin.seo.index')->with('success', 'SEO entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seo $seo)
    {
        $seo->delete();
        return redirect()->route('admin.seo.index')->with('success', 'SEO entry deleted successfully.');
    }
}
