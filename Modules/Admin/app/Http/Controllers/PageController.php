<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')->paginate(10);
        return view('admin::pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $input = $request->all();
        $input['slug'] = Str::slug($request->title);
        $input['status'] = $request->has('status') ? 1 : 0;

        // Ensure unique slug
        if (Page::where('slug', $input['slug'])->exists()) {
             $input['slug'] = $input['slug'] . '-' . time();
        }

        Page::create($input);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin::pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $page = Page::findOrFail($id);
        $input = $request->all();
        $input['slug'] = Str::slug($request->title);
        $input['status'] = $request->has('status') ? 1 : 0;
        
        // Ensure unique slug (excluding current page)
        if (Page::where('slug', $input['slug'])->where('id', '!=', $id)->exists()) {
            $input['slug'] = $input['slug'] . '-' . time();
        }

        $page->update($input);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}
