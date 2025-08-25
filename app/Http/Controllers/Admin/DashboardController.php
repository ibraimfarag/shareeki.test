<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Charts\CountCategoryChart;
use App\Charts\PostsByMonthChart;
use App\Charts\PostsInYearChart;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Like;
use App\Models\Post;
use App\Models\Report;
use App\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $monthLabels;
    protected $sortLabels;

    public function __construct()
    {
        $this->monthLabels =  ['يناير', 'فبراير', 'مارس', 'إبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];
        $this->sortLabels = Category::whereCategoryId(null)->pluck('name')->toArray();
    }

    public function index()
    {
        //Counts
        $lastUsers = User::whereDate('created_at', Carbon::today())->count();
        $users = User::count();
        $lastPosts = Post::whereDate('created_at', Carbon::today())->count();
        $posts = Post::count();
        $lastLikes = Like::whereDate('created_at', Carbon::today())->count();
        $likes = Like::count();
        $lastReports = Report::whereDate('created_at', Carbon::today())->count();
        $reports = Post::whereBlacklist(1)->count();
        $likesPosts = Post::whereHas('likes')->get();
        $totalPrices = $likesPosts->sum('price');


        // Get Posts Of The Specific Category And Make Line Chart Depending On
        // Count Category Posts
        $countCategories = Post::CountCategories();
        // Make Doghnut Chart Depending On
        $countCategoryChart = new CountCategoryChart();
        $countCategoryChart->labels = array_keys($countCategories);
        $resultCountCategories = array_values($countCategories);
        // $countCategoryChart->dataset('My dataset', 'doughnut', $resultCountCategories);
        // $countCategoryChart->minimalist(false);
        // $countCategoryChart->displayAxes(false);



        
        // Get Posts Of The Current Month And Make Line Chart Depending On
        $postsByMonthChart = new PostsByMonthChart;
        $postsByMonthChart->labels = ($this->sortLabels);
        $postsInMonth = [];
        // for($j = 0; $j < count( $this->sortLabels); $j++){
        //     array_push($postsInMonth,Post::FindPostsByMonth($this->sortLabels[$j], now()->month));
        // }
        // $postsByMonthChart->dataset('My dataset', 'line', $postsInMonth);
        // $postsByMonthChart->height(200);




        //most posts reported (eloquent relationship)
        $mostReportedPosts = Post::withCount(['likes', 'dislikes', 'reports'])->orderByDesc('reports_count')->take(20)->get();
        //most user adding (eloquent relationship)
        $mostSellersPosts = User::withCount(['posts'])->orderByDesc('posts_count')->take(20)->get();




        // Get Posts Of The Specific Category And Make Line Chart Depending On During The Whole Year
        $PostsBySortInYear = [];
        //for($i = 0; $i < count($this->sortLabels); $i++){
            //$PostsBySortInYear[$i] = [];
            for($j = 1; $j < 13; $j++){
                array_push($PostsBySortInYear,Post::FindPostsByMonth($i = 0, $j));
            }
        //}
        //dd($PostsBySortInYear);       
        $postsAddingInYearChart = new PostsInYearChart;
        $postsAddingInYearChart->labels = ($this->monthLabels);
        // for($i = 0; $i < 4; $i++){
        //     $postsAddingInYearChart->dataset($this->sortLabels[$i], 'line', $PostsBySortInYear);
        // }



        // Take Latest Users
        $newUsers = User::take(5)->get();
        // Take Most Reported Posts
        $mostReportedPosts = Post::withCount(['likes', 'dislikes', 'reports'])->orderByDesc('reports_count')->take(7)->get();
        // Take Last Contacts
        $contacts = Contact::latest()->take(14)->get();
        // Take Best Posts
        $bestPosts = Post::withCount(['likes', 'dislikes', 'reports'])->orderBy('likes_count')->take(8)->get();



        return view('admin.dashboard', compact('lastUsers', 'users' , 'lastPosts' , 'posts' , 'lastLikes' , 'likes' , 'lastReports', 'reports', 'totalPrices' , 'countCategoryChart' , 'postsByMonthChart' , 'mostSellersPosts' , 'mostReportedPosts', 'postsAddingInYearChart' , 'newUsers', 'mostReportedPosts' , 'contacts' , 'bestPosts'));
        
    }
}