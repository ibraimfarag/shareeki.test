<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use DataTables;
use Illuminate\Http\Request;

class PostEnumController extends Controller
{
    // Get Most Liked Posts
    public function mostLiked(Request $request)
    {
        
        if ($request->ajax()) {

            $posts = Post::withCount(['likes', 'dislikes', 'reports'])->with('user')->orderByDesc('likes_count')->get();
            
            return Datatables::of($posts)->addIndexColumn()->addColumn('show', function($row){ $btn = '<a href="'.route("the_posts.show",  [$row->slug]).'" target="_blank" class="edit btn btn-primary btn-sm">عرض</a>';return $btn;})
            ->addColumn('blacklist', function($row){
                if($row->blacklist == 0){$btn = '<a href="'.route("blacklist", [$row->slug,'Post']).'"  class="btn btn-warning btn-sm">حظر</a>';}else{$btn = '<a href="'.route("unblacklist", [$row->slug,'Post']).'"  class="btn btn-warning btn-sm">فك الحظر</a>';}
                return $btn;
            })
            ->addColumn('delete', function($row){$btn = '<a href="'.route("posts.delete", [$row->slug]).'"  class="btn btn-danger btn-sm">حذف</a>';return $btn;})
            ->rawColumns(['show','blacklist','delete'])
            ->make(true);
    
        }
    
        return view('admin.posts.enum.most_liked', ['name' => 'mostLiked']);
    }

    // Get Most DisLiked Posts
    public function mostDisliked(Request $request)
    {

        if ($request->ajax()) {

            $posts = Post::withCount(['likes', 'dislikes', 'reports'])->with('user')->orderByDesc('dislikes_count')->get();
            
            return Datatables::of($posts)->addIndexColumn()
            ->addColumn('show', function($row){$btn = '<a href="'.route("the_posts.show",  [$row->slug]).'" target="_blank" class="edit btn btn-primary btn-sm">عرض</a>';return $btn;})
            ->addColumn('blacklist', function($row){
                if($row->blacklist == 0){$btn = '<a href="'.route("blacklist", [$row->slug,'Post']).'"  class="btn btn-warning btn-sm">حظر</a>';}else{$btn = '<a href="'.route("unblacklist", [$row->slug,'Post']).'"  class="btn btn-warning btn-sm">فك الحظر</a>';}
                return $btn;
            })
            ->addColumn('delete', function($row){$btn = '<a href="'.route("posts.delete", [$row->slug]).'"  class="btn btn-danger btn-sm">حذف</a>';return $btn;})
            ->rawColumns(['show','blacklist','delete'])
            ->make(true);
    
        }
    
        return view('admin.posts.enum.most_disliked', ['name' => 'mostDisLiked']);
    }

    // Get Most Reported Posts
    public function mostReported(Request $request)
    {

        if ($request->ajax()) {

            $posts = Post::withCount(['likes', 'dislikes', 'reports'])->with('user')->orderByDesc('reports_count')->get();
            
            return Datatables::of($posts)->addIndexColumn()
            ->addColumn('show', function($row){$btn = '<a href="'.route("the_posts.show",  [$row->slug]).'" target="_blank" class="edit btn btn-primary btn-sm">عرض</a>'; return $btn;})
            ->addColumn('blacklist', function($row){
                if($row->blacklist == 0){$btn = '<a href="'.route("blacklist", [$row->slug,'Post']).'"  class="btn btn-warning btn-sm">حظر</a>';}else{$btn = '<a href="'.route("unblacklist", [$row->slug,'Post']).'"  class="btn btn-warning btn-sm">فك الحظر</a>';}
                return $btn;
            })
            ->addColumn('delete', function($row){$btn = '<a href="'.route("posts.delete", [$row->slug]).'"  class="btn btn-danger btn-sm">حذف</a>';return $btn;})
            ->rawColumns(['show','blacklist','delete'])
            ->make(true);

        }
    
        return view('admin.posts.enum.most_reported', ['name' => 'mostReported']);
    }


    // Get Most Blacklisted Posts
    public function blackListed(Request $request)
    {

        if ($request->ajax()) {

            $posts = Post::whereBlacklist(1)->withCount(['likes', 'dislikes', 'reports'])->with('user')->get();
            
            return Datatables::of($posts)->addIndexColumn()
            ->addColumn('show', function($row){$btn = '<a href="'.route("the_posts.show",  [$row->slug]).'" target="_blank" class="edit btn btn-primary btn-sm">عرض</a>'; return $btn;})
            ->addColumn('blacklist', function($row){
                if($row->blacklist == 0){$btn = '<a href="'.route("blacklist", [$row->slug,'Post']).'"  class="btn btn-warning btn-sm">حظر</a>';}else{$btn = '<a href="'.route("unblacklist", [$row->slug,'Post']).'"  class="btn btn-warning btn-sm">فك الحظر</a>';}
                return $btn;
            })
            ->addColumn('delete', function($row){$btn = '<a href="'.route("posts.delete", [$row->slug]).'"  class="btn btn-danger btn-sm">حذف</a>';return $btn;})
            ->rawColumns(['show','blacklist','delete'])
            ->make(true);
    
        } 
    
        return view('admin.posts.enum.blacklisted', ['name' => 'BlackListed']);
    }
}
