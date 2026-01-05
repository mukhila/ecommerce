<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\CompanySetting;
use Illuminate\Support\Facades\File;

class CompanySettingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $setting = CompanySetting::first();
        if (!$setting) {
            $setting = new CompanySetting();
        }
        return view('admin::company_settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'whatsapp_no' => 'nullable|string|max:255',
            'social_links' => 'nullable|array',
        ]);

        $setting = CompanySetting::first();
        if (!$setting) {
            $setting = new CompanySetting();
        }

        $input = $request->except(['logo', '_token', '_method']);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($setting->logo && File::exists(public_path($setting->logo))) {
                File::delete(public_path($setting->logo));
            }

            $image = $request->file('logo');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('uploads/settings'), $imageName);
            $input['logo'] = 'uploads/settings/' . $imageName;
        }

        if ($setting->exists) {
            $setting->update($input);
        } else {
            // If it's a new record and logo was uploaded, explicitly set it
            if (isset($input['logo'])) {
                $setting->logo = $input['logo'];
            }
            $setting->fill($input);
            $setting->save();
        }

        return redirect()->back()->with('success', 'Company settings updated successfully.');
    }
}
