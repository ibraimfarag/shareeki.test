<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return DB::table('areas')->where('parent_id',$id)->orderBy('position')->get();
    }


}
