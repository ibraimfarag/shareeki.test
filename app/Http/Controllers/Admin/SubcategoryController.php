<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Upload\Upload;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Category $category)
    {
        if ($request->ajax()) {

            $subcategories = $category->subcategories()->get();

            return DataTables::of($subcategories)->addIndexColumn()
            ->addcolumn('action', function($row){ $btn = '<a href="'.route('subcategories.show', [Category::whereId($row->category_id)->first()->slug, $row->slug]).'" class="btn btn-primary">عرض</a>'; return $btn; })
            ->addColumn('actionone', function($row){$btn = '<a href="'.route('subcategories.edit', [Category::whereId($row->category_id)->first()->slug, $row->slug]).'" class="edit btn btn-primary btn-sm">تعديل</a>';return $btn;})
            ->addColumn('actiontwo', function($row){$btn = '<a href="'.route('subcategories.delete', [Category::whereId($row->category_id)->first()->slug, $row->slug]).'" class="delete btn btn-danger btn-sm">حذف</a>';return $btn;})
            ->rawColumns(['action','actionone','actiontwo'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('admin.subcategories.index')->withCategory($category)->withSubcategories($category->subcategories()->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        return view('admin.subcategories.add')->withCategory($category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), ['name' => 'required', 'description' => 'required', 'visible' => 'required|boolean']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        // Prepare All Request Input For Entering DB
        $request->merge(['slug' => Str::slug(request('name'))]);

        if($request->has('main_image')) { $request->merge(['image' => Upload::uploadImage($request->main_image, 'categories' , $request->name)]); }

        $category->subcategories()->create($request->except('main_image'));
        return redirect()->route('subcategories.index', $category)->with('status', 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('admin.subcategories.show')->withSubcategory($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @param  \App\Category  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category, Category $subcategory)
    {   
        return view('admin.subcategories.edit')->withCategory($category)->withSubcategory($subcategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category, Category $subcategory)
    {
        if($request->has('main_image')) { 
            $request->merge(['image' => Upload::uploadImage($request->main_image, 'categories' , $subcategory->name)]);
        }

        $validator = Validator::make($request->all(), ['name' => 'required', 'description' => 'required', 'visible' => 'required|boolean']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        // Prepare All Request Input For Entering DB
        $request->merge(['slug' => Str::slug(request('name'))]);

        //$subcategory->update($request->except('_token','visible','main_image'));
        return redirect()->route('subcategories.index', $category)->with('status', 'Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, Category $subcategory)
    {
        return $subcategory->delete() ? redirect()->back()->with('status', 'Category Deleted Successfully') : abort(500);
    }
    
    public function list($id)
    {
        return Category::where('category_id',$id)->get();
    }

}
