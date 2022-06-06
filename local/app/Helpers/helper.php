<?php


function make_slug($string) {
    return preg_replace('/\s+/u', '-', trim($string));
}


function Admin() {
    return \Illuminate\Support\Facades\Auth::guard('admin')->user();
}

function setting(){
    $options=App\Models\Setting::all();
    $setting = array();
    foreach ($options as $option) {
        $name = $option['setting'];
        $value = $option['value'];
        $setting[$name] = $value;
    }
    return $setting;
}

