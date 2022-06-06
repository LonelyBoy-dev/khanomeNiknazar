<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class AdminSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (admin()->can('settings')) {
            $settings = Setting::where('show', 'YES')->orderby('sort', 'asc')->get();
            $title = "تنضیمات";
            $Active_list = "settings";
            $Active = "settings";
            return view('admin.settings.index', compact(['title', 'settings', 'Active_list', 'Active']));
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->input() as $key => $value) {
            if ($key == 'theme') {
                Setting::where('setting', $key)->update(['orgv' => $value]);
            } else {
                Setting::where('setting', $key)->update(['value' => $value]);
            }


        }
        session()->put('store-success', 'با موفقیت ذخیره شده');
        return redirect('/admin/settings');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
