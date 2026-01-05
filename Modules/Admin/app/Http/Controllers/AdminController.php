<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin::dashboard');
    }

    public function profile()
    {
        return view('admin::profile');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
    public function changePassword()
    {
        return view('admin::auth.passwords.change');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Current password does not match!');
        }

        $admin->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password successfully changed!');
    }
}
