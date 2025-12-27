<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if ($request->hasFile('loader_logo')) {
            $oldLogo = Setting::where('key', 'loader_logo')->first();
            if ($oldLogo && $oldLogo->value) {
                Storage::disk('public')->delete($oldLogo->value);
            }

            $path = $request->file('loader_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'loader_logo'], ['value' => $path]);
        }

        $data = $request->except(['_token', 'loader_logo']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        return back()->with('success', 'Sazlamalar üstünlikli ýatda saklandy.');
    }
}
