<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Traits\HasMediaTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use HasMediaTrait;

    public function index()
    {
        $settings = [
            'website_name' => Setting::get('website_name', 'PreSkool'),
            'logo' => Setting::get('logo', 'assets/img/logo.png'),
            'favicon' => Setting::get('favicon', 'assets/img/favicon.png'),
            'rtl' => Setting::get('rtl', '0'),
            'address_line_1' => Setting::get('address_line_1', ''),
            'address_line_2' => Setting::get('address_line_2', ''),
            'city' => Setting::get('city', ''),
            'state' => Setting::get('state', ''),
            'zip_code' => Setting::get('zip_code', ''),
            'country' => Setting::get('country', ''),
        ];
        
        return view('setting.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'website_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|file|max:1024',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
        ]);

        if ($request->has('website_name')) {
            Setting::set('website_name', $request->website_name);
            Setting::set('rtl', $request->has('rtl') ? '1' : '0');
        }

        $addressFields = ['address_line_1', 'address_line_2', 'city', 'state', 'zip_code', 'country'];
        foreach ($addressFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        if ($request->hasFile('logo')) {
            $logoId = $this->uploadMedia($request->file('logo'), 'settings');
            if ($logoId) {
                Setting::set('logo', $this->getMedia($logoId));
            }
        }

        if ($request->hasFile('favicon')) {
            $faviconId = $this->uploadMedia($request->file('favicon'), 'settings');
            if ($faviconId) {
                Setting::set('favicon', $this->getMedia($faviconId));
            }
        }

        return response()->json(['message' => 'Settings updated successfully!']);
    }
}
