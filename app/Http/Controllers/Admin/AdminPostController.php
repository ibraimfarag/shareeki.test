<?php

namespace App\Http\Controllers\Admin;

use App\Models\Area;
use App\Models\Attachment;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostFormRequest;
use Illuminate\Support\Str;
use App\Models\Post;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Upload\Upload;
use Illuminate\Support\Facades\DB;
use Spatie\Tags\Tag;

class AdminPostController extends Controller
{
    /**
     * Display featured posts only.
     * @return \Illuminate\Contracts\View\View
     */
    public function featured(): \Illuminate\Contracts\View\View
    {
        $featuredPosts = Post::featured()->get();
        return view('admin.posts.featured', compact('featuredPosts'));
    }

    /**
     * Show post details for modal
     */
    public function modalShow(Post $post)
    {
        return view('admin.posts.partials.modal-show', compact('post'));
    }

    /**
     * Show post edit form for modal
     */
    public function modalEdit(Post $post)
    {
        $theTags = Tag::all(["id", "name"]);
        $tags = [];
        foreach ($theTags as $tag) {
            array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
        }

        return view('admin.posts.partials.modal-edit', [
            'post' => $post,
            'countries' => Area::whereParentId(1)->orderBy('position')->get(),
            'categories' => Category::whereNull('category_id')->get(),
            'tags' => $tags
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Post::all();
        // dd($categories );
        if ($request->ajax()) {


            return DataTables::of($categories)->addIndexColumn()
                ->addcolumn('action', function ($row) {
                    $btn = '<a target="_blank" href="' . route("posts.show", [$row->slug]) . '" class="btn btn-primary">عرض</a>';
                    return $btn;
                })
                ->addColumn('actionone', function ($row) {
                    $btn = '<a href="' . route("posts.edit", [$row->slug]) . '" class="edit btn btn-primary btn-sm">تعديل</a>';
                    return $btn;
                })
                ->addColumn('actiontwo', function ($row) {
                    $btn = '<a href="' . route("posts.delete", [$row->slug]) . '" class="delete btn btn-danger btn-sm">حذف</a>';
                    return $btn;
                })
                ->addColumn('actionthree', function ($row) {
                    $btn = '<a href="' . route("posts.block", [$row->slug]) . '" class="block btn btn-warning btn-sm">حظر</a>';
                    return $btn;
                })
                ->rawColumns(['action', 'actionone', 'actiontwo', 'actionthree'])
                ->addIndexColumn()
                ->make(true);

        }
        return view('admin.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $theTags = Tag::all(["id", "name"]);
        $tags = [];
        foreach ($theTags as $tag) {
            array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
        }

        return view('admin.posts.add', ['countries' => Area::whereParentId(1)->orderBy('position')->get(), 'categories' => Category::whereNull('category_id')->get(), 'tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostFormRequest $request)
    {

        $request->full_partnership != "on" ?: $partner_sort[0] = "on";
        $request->loan != "on" ?: $partner_sort[1] = "on";

        if (!isset($partner_sort)) {
            $partner_sort[0] = "on";
            $partner_sort[1] = "on";
        }

        //upload only if request has main_image
        if ($request->has('main_image'))
            $request->merge(['img' => Upload::uploadImage($request->main_image, 'posts', $request->title)]);

        // Prepare All Request Input Either For Entering DB Or For Other Process Depending On Other Model
        $request->merge(['code' => rand(10, 50000), 'user_id' => 0, 'slug' => Str::slug($request->title), 'tags' => explode(',', $request->the_tags), 'partner_sort' => json_encode($partner_sort, JSON_FORCE_OBJECT)]);

        // Create Post
        $post = Post::create($request->except('_token', 'visible', 'main_image', 'the_attachment', 'tags', 'the_tags', 'full_partnership', 'loan'));

        // Get It's name for make a special attachments folder for
        $postName = $post->title;

        // Add The Post Tags
        foreach ($request->tags as $tag) {
            DB::table('taggables')->insert(['tag_id' => $tag, 'taggable_type' => 'App\Post', 'taggable_id' => $post->id]);
        }

        // Add The Attachments
        if ($request->has('the_attachment')) {
            foreach ($request->the_attachment as $attachment) {
                Attachment::create(['post_id' => $post->id, 'name' => Upload::uploadImage($attachment, "attachments/${postName}", $postName . "_" . rand(0, 100000))]);
            }
        }

        return view('admin.posts.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // Get Current Country Then City
        $city = Area::where('id', $post->area_id)->first();
        $city == null ? $country = null : $country = Area::where('id', $city->parent_id)->first()->id;

        // Get Current Category Then SubCategory
        $subCategory = Category::where('id', $post->category_id)->first();
        $subCategory == null ? $category = null : $category = Category::where('id', $subCategory->id)->first()->id;

        // Get The Post Tags
        //$tags = $post->tags()->get(["id","name"]);

        //$theTags = Tag::all(["id", "name"]);

        //$tags = [];
        //foreach($theTags as $tag){
        //   array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
        // }

        $theTags = Tag::whereNotNull('id')->get();
        $tags = [];
        foreach ($theTags as $tag) {
            array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
        }

        $post->partner_sort = json_decode($post->partner_sort, true);


        return view('admin.posts.edit', ['post' => $post, 'countries' => Area::whereParentId(1)->orderBy('position')->get(), 'categories' => Category::whereNull('category_id')->get(), 'theCity' => $city, 'theCountry' => $country, 'theCategory' => $category, 'theSubCategory' => $subCategory, 'tags' => json_decode($theTags)]);
    }

    public function show(Post $post)
    {
        // Get Current Country Then City
        $city = Area::where('id', $post->area_id)->first();
        $city == null ? $country = null : $country = Area::where('id', $city->parent_id)->first()->id;

        // Get Current Category Then SubCategory
        $subCategory = Category::where('id', $post->category_id)->first();
        $subCategory == null ? $category = null : $category = Category::where('id', $subCategory->id)->first()->id;

        // Get The Post Tags
        //$tags = $post->tags()->get(["id","name"]);

        $theTags = Tag::all(["id", "name"]);
        $tags = [];
        foreach ($theTags as $tag) {
            array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
        }

        $post->partner_sort = json_decode($post->partner_sort, true);


        return view('admin.posts.show', ['post' => $post, 'countries' => Area::whereParentId(1)->orderBy('position')->get(), 'categories' => Category::whereNull('category_id')->get(), 'theCity' => $city, 'theCountry' => $country, 'theCategory' => $category, 'theSubCategory' => $subCategory, 'tags' => $tags]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostFormRequest $request, Post $post)
    {

        $request->full_partnership != "on" ?: $partner_sort[0] = "on";
        $request->loan != "on" ?: $partner_sort[1] = "on";

        if ($request->loan == "on") {
            $request->merge(['partnership_percentage' => '0.00%']);
        }

        // check if request has main_image
        if ($request->has('main_image')) {
            $request->merge(['img' => Upload::uploadImage($request->main_image, 'posts', $post->title)]);

        }

        // Prepare All Request Input Either For Entering DB Or For Other Process Depending On Other Model
        $request->merge(['tags' => explode(',', $request->the_tags), 'partner_sort' => json_encode($partner_sort, JSON_FORCE_OBJECT)]);

        // Update The Post
        $post->update($request->except('_token', 'visible', 'main_image', 'the_attachment', 'tags', 'the_tags', 'full_partnership', 'loan'));

        // Get It's name for make a special attachments folder for
        $postName = $post->title;

        // Update The Post Tags
        DB::table('taggables')->where('taggable_id', $post->id)->delete();
        foreach ($request->tags as $tag) {
            DB::table('taggables')->insert(['tag_id' => $tag, 'taggable_type' => 'App\Post', 'taggable_id' => $post->id]);
        }

        // Add The Attachments
        if ($request->has('the_attachment')) {
            foreach ($request->the_attachment as $attachment) {
                Attachment::create(['post_id' => $post->id, 'name' => Upload::uploadImage($attachment, "attachments/${postName}", $postName . "_" . rand(0, 100000))]);
            }
        }

        return redirect()->route('posts.index')->with('success', 'تم تعديل البيانات بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        return $post->delete() ? redirect()->route('posts.index') : abort(500);
    }

    public function block(Post $post)
    {

        $post->update(["blacklist" => 1]);
        return redirect()->route('posts.index');
    }



}
