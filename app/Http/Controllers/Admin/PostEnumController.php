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

    // Get Featured (Paid) Posts
public function featured(Request $request)
{
    if ($request->ajax()) {

        $posts = Post::query()
            ->where('blacklist', 0)
            ->where('is_paid', true)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            })
            // ->whereNotNull('pinned_at')
            // ->whereJsonContains('features->badge', true)
            ->withCount(['likes', 'dislikes', 'reports'])
            ->with(['user:id,name'])
            ->orderByRaw('pinned_at IS NULL')   
            ->orderByDesc('pinned_at')
            ->orderByDesc('featured_rank')
            ->latest('created_at')
            ->get();

        return DataTables::of($posts)->addIndexColumn()
            ->addColumn('show', function ($row) {
                return '<a href="'.route("the_posts.show", [$row->slug]).'" target="_blank" class="btn btn-primary btn-sm">عرض</a>';
            })
            ->addColumn('blacklist', function ($row) {
                if ($row->blacklist == 0) {
                    return '<a href="'.route("blacklist", [$row->slug,'Post']).'" class="btn btn-warning btn-sm">حظر</a>';
                }
                return '<a href="'.route("unblacklist", [$row->slug,'Post']).'" class="btn btn-warning btn-sm">فك الحظر</a>';
            })
            ->addColumn('delete', function ($row) {
                return '<a href="'.route("posts.delete", [$row->slug]).'" class="btn btn-danger btn-sm">حذف</a>';
            })
            // أعمدة إضافية مفيدة للإدارة
            ->addColumn('status_badge', function ($row) {
                $expired = $row->ends_at && $row->ends_at->lt(now());
                if ($expired || $row->status !== 'active') {
                    return '<span class="badge bg-danger">منتهي/غير فعّال</span>';
                }
                return '<span class="badge bg-success">فعّال</span>';
            })
            ->addColumn('type', function ($row) {
                return $row->adType->name ?? 'paid';
            })
            ->addColumn('ends_in', function ($row) {
                if (!$row->ends_at) return '-';
                return e($row->ends_at->diffForHumans());
            })
            ->rawColumns(['show','blacklist','delete','status_badge'])
            ->make(true);
    }


    return view('admin.posts.enum.featured', ['name' => 'featured']);
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
