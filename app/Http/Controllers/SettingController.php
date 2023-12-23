<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::query()->first();
        return view('settings.index', compact('settings'));
    }

    public function submit(Request $request)
    {
        $salary = 0;
        if ($request->has('salary')) {
            $salary = 1;
        }

        $request->validate([
            'city_id' => 'required',
            'category_id' => 'required',
        ]);

        $settings = Setting::where('id', $request->id)->first();
        $settings->update([
            'city_id' => $request->city_id,
            'category_id' => $request->category_id,
            'salary' => $salary
        ]);

        return redirect()->route('settings.index');
    }
}
