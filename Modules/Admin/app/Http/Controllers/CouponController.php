<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Coupon;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::orderBy('id', 'desc')->paginate(10);
        return view('admin::coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:255',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'status' => $request->input('status', 1),
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin::coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,'.$id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $coupon->update([
             'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'status' => $request->input('status', 1),
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
