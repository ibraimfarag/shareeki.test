<?php

namespace App\Http\Controllers\Admin;

use App\Models\Area;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $areas = Area::getMainAreas();

            $areas = $areas->where('id' , '!=' , 1)->all();

            return DataTables::of($areas)->addIndexColumn()
            ->addcolumn('action', function($row){ $btn = '<a href="'.route("cities.index", [$row->id]).'" class="btn btn-primary">عرض</a>'; return $btn; })
            ->addColumn('actionone', function($row){$btn = '<a href="'.route("countries.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';return $btn;})
            ->addColumn('actiontwo', function($row){$btn = '<a href="'.route("countries.delete", [$row->id]).'" class="delete btn btn-danger btn-sm">حذف</a>';return $btn;})
            ->rawColumns(['action','actionone','actiontwo'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('admin.countries.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['arabic' => 'required', 'english' => 'required']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['name' => $request->arabic, 'parent_id' => 1]);

        Area::create($request->except('main_image','arabic'));
        return redirect()->route('countries.index')->with('status', 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $country)
    {
        return view('admin.countries.show')->withCountry($country)->withCities(Area::getChildrenAreas($country->id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $country)
    {
        return view('admin.countries.edit')->withCountry($country);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $country)
    {
        $validator = Validator::make($request->all(), ['arabic' => 'required', 'english' => 'required']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['name' => $request->arabic]);

        $country->update($request->except('main_image','arabic'));
        return redirect()->route('countries.index')->with('status', 'Country Updated Successfully');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $country)
    {
        return $country->delete() ? redirect()->back()->with('status', 'Country Deleted Successfully') : abort(500);   
    }
}
