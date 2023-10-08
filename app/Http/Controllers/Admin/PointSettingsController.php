<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PointSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::all();

        return view('admin.points.edit', get_defined_vars());
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // $settings = Setting::all();

        foreach ($request->request as $key => $value ) :
            # code...

            $setting = Setting::where('name', $key)->first();
            // dd($setting);

            $setting ? $setting->update([
                'value' => $value
            ]) :null;

        endforeach;

        return  redirect()->back()->with(['success'=>'update success']);
    }


}
