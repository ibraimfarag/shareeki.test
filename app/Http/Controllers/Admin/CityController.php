<?php

namespace App\Http\Controllers\Admin;

use App\Models\Area;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Area $country)
    {
        if ($request->ajax()) {

            $areas = Area::getChildrenAreas($country->id);

            $areas = $areas->where('id' , '!=' , 1)->all();

            return DataTables::of($areas)->addIndexColumn()
            ->addcolumn('action', function($row){ $btn = '<a href="'.route("cities.index", [Area::whereId($row->parent_id)->first()->id, $row->id]).'" class="btn btn-primary">عرض</a>'; return $btn; })
            ->addColumn('actionone', function($row){$btn = '<a href="'.route("cities.edit", [Area::whereId($row->parent_id)->first()->id, $row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';return $btn;})
            ->addColumn('actiontwo', function($row){$btn = '<a href="'.route("cities.delete", [Area::whereId($row->parent_id)->first()->id, $row->id]).'" class="delete btn btn-danger btn-sm">حذف</a>';return $btn;})
            ->rawColumns(['action','actionone','actiontwo'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('admin.cities.index', compact('country'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Area $country)
    {
        return view('admin.cities.add', compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Area $country)
    {
        $validator = Validator::make($request->all(), ['arabic' => 'required', 'english' => 'required']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['name' => $request->arabic, 'parent_id' => $country->id]);

        Area::create($request->except('main_image','arabic'));
        return redirect()->route('cities.index', $country->id)->with('message', 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $country, Area $city)
    {
        return view('admin.cities.edit')->withCountry($country)->withCity($city);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $country, Area $city)
    {
        $validator = Validator::make($request->all(), ['arabic' => 'required', 'english' => 'required']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['name' => $request->arabic]);

        $city->update($request->except('main_image','arabic'));
        return redirect()->route('cities.index' , $country)->with('message', 'City Updated Successfully');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $country, Area $city)
    {
        return $city->delete() ? redirect()->back()->with('message', 'City Deleted Successfully') : abort(500);   
    }
}