<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{



    public function store()
    {
        // return \request();

        $s = Setting::find(1);
        $data = \request()->data;
        if (\request()->hasFile('bg_image')) {
            $path = \request()->bg_image->store('bg_images', ['disk' => 'public']);
            $data['bg'] = $path;
        }
        if (\request()->hasFile('main_image')) {
            $path = \request()->main_image->store('main_images', ['disk' => 'public']);
            $data['main_image'] = $path;
        }

        if (\request()->hasFile('side_bare_image')) {
            $path = \request()->side_bare_image->store('bg_images', ['disk' => 'public']);
            $data['side_bar_bg'] = $path;
        }
        $s->update(['value' => $data]);
        return Setting::all();
    }
}
