<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Upload\Upload;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $categories = Category::whereNull('category_id')->get();

            return DataTables::of($categories)->addIndexColumn()
            ->addcolumn('action', function($row){ $btn = '<a href="'.route("subcategories.index", [$row->slug]).'" class="btn btn-primary">عرض</a>'; return $btn; })
            ->addColumn('actionone', function($row){$btn = '<a href="'.route("categories.edit", [$row->slug]).'" class="edit btn btn-primary btn-sm">تعديل</a>';return $btn;})
            ->addColumn('actiontwo', function($row){$btn = '<a href="'.route("categories.delete", [$row->slug]).'" class="delete btn btn-danger btn-sm">حذف</a>';return $btn;})
            ->rawColumns(['action','actionone','actiontwo'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $validator = Validator::make($request->all(), ['name' => 'required', 'description' => 'required', 'visible' => 'required|boolean']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        // Prepare All Request Input For Entering DB
        $request->merge(['slug' => Str::slug(request('name'))]);

        if($request->has('main_image')) { $request->merge(['image' => Upload::uploadImage($request->main_image, 'categories' , $request->name)]); }

        Category::create($request->except('main_image'));
        return redirect()->route('categories.index')->with('status', 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('admin.categories.show')->withCategory($category)->withSubcategories($category->subcategories());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit')->withCategory($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if($request->has('main_image')) { $request->merge(['image' => Upload::uploadImage($request->main_image, 'categories' , $category->name)]); }

        $validator = Validator::make($request->all(), ['name' => 'required', 'description' => 'required', 'visible' => 'required|boolean']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        // Prepare All Request Input For Entering DB
        $request->merge(['slug' => Str::slug(request('name'))]);

        $category->update($request->except('main_image'));
        return redirect()->route('categories.index')->with('status', 'Category Updated Successfully');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        return $category->delete() ? redirect()->back()->with('status', 'Category Deleted Successfully') : abort(500);   
    }
}
