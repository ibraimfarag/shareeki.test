<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{

    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('admin.settings.edit', ['settings' => Settings::findOrFail(1)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required|sometimes', 'about' => 'required|sometimes', 'telephone' => 'required|sometimes', 'email' => 'required|sometimes', 'commission_percentage' => 'required|sometimes|numeric']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }
        
        Settings::whereId(1)->update($request->except('_token','_method','1','0'));
        return redirect()->back();
    }
}
