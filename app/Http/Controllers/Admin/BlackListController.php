<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Http\Controllers\Controller;

class BlackListController extends Controller
{
    // Blacklist Post
    public function blacklist($slug, $type)
    {  
       Post::whereSlug($slug)->update(['blacklist' => 1]);
       return redirect()->back();
    }

    // UnBlacklist Post
    public function unblacklist($slug, $type)
    {
        Post::whereSlug($slug)->update(['blacklist' => 0]);
        return redirect()->back();
    }
}
