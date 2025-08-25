<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        if(auth()->user()){
            $request->merge(['user_id' => auth()->user()->id]);
            Report::create($request->all());
            return 'OK';
        }else{
            return 'الرجاء من فضلك تسجيل الدخول أو الإنضمام للموقع';
        }
    }
}