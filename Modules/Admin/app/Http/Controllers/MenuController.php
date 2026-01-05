<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'main');
        $menus = Menu::where('type', $type)
                     ->whereNull('parent_id')
                     ->with('children')
                     ->orderBy('sort_order')
                     ->get();
        return view('admin::menus.index', compact('menus', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'main');
        $parents = Menu::where('type', $type)->whereNull('parent_id')->get();
        return view('admin::menus.create', compact('parents', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:main,footer',
            'url' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'nullable|integer',
        ]);

        $input = $request->all();
        $input['status'] = $request->has('status') ? 1 : 0;

        Menu::create($input);

        return redirect()->route('admin.menus.index', ['type' => $request->type])
                         ->with('success', 'Menu created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::menus.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $menu = Menu::find($id);
        $parents = Menu::where('type', $menu->type)
                       ->whereNull('parent_id')
                       ->where('id', '!=', $id)
                       ->get();
        return view('admin::menus.edit', compact('menu', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'nullable|integer',
        ]);

        $menu = Menu::find($id);
        $input = $request->all();
        $input['status'] = $request->has('status') ? 1 : 0;

        $menu->update($input);

        return redirect()->route('admin.menus.index', ['type' => $menu->type])
                         ->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);
        $type = $menu->type;
        $menu->delete();

        return redirect()->route('admin.menus.index', ['type' => $type])
                         ->with('success', 'Menu deleted successfully.');
    }
}
