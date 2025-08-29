<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('welcome' , 'payment' , 'encryption' , 'decryption');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        return view('home');
    }
    


    public function welcome()
    {
        $theTags = Tag::all(["id", "name"]);
        $tags = [];
        foreach($theTags as $tag){
            array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
        }

        return view('main.posts.index', [
            'countries' => Area::whereParentId(1)->orderBy('position')->get(),
            'categories' => Category::whereNull('category_id')->get(),
            'subcategories' => Category::whereNotNull('category_id')->get(),
            'tags' => $tags,
            'posts' => Post::where('is_featured', false)->latest()->paginate(20),
            'featuredPosts' => Post::homeFeatured()->get()
        ]);
    }
}
