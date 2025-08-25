<?php

use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\PostEnumController;
use App\Http\Controllers\Admin\BlackListController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CityController;

use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SearchController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function(){
  return redirect('/home');
});*/



Route::get('/', [PostController::class,'index'])->name('my_home');




Route::get('/clearcache185', function () {
    $clearcache = Artisan::call('cache:clear');
    echo "Cache cleared<br>";

    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";

    $clearconfig = Artisan::call('config:cache');
    echo "Config cleared<br>";


    $sotagelink = \Illuminate\Support\Facades\Artisan::call('storage:link');
});

Route::post('/payment', [PageController::class,'payment'])->name('Payment');
Route::get('/success', function(Request $request){
    echo "تمت عمليه  الدفع بنجاح";
});

Route::get('/error', function(Request $request){
   echo "هناك خطأ يرجي إعادة المحاولة";
});



Auth::routes(['verify' => true]);

Route::get('the_posts', [PostController::class,'index'])->name('the_posts.index');
Route::get('the_posts/{the_post}', [PostController::class,'show'])->name('the_posts.show');
// previsoully we looking for by the title but now by Id
// Posts
Route::get('new_post', [PostController::class,'create'])->name('the_posts.create');
Route::post('new_post', [PostController::class,'store'])->name('the_posts.store');

Route::group(['middleware' => 'verified'], function (){
// user logout
  Route::get('userlogout',function(){
    auth()->logout();
    return redirect('login');
  });


// User Home Profile
Route::get('/home', [HomeController::class , 'index'])->name('home');

Route::get('the_posts/{the_post}/edit', [PostController::class,'edit'])->name('the_posts.edit');
Route::put('the_posts/{the_post}', [PostController::class,'update'])->name('the_posts.update');

Route::delete('the_posts/{the_post}', [PostController::class,'destroy'])->name('the_posts.destroy');


//Post Like & Dislike
Route::post('like', [LikeController::class,'like']);
Route::post('dislike', [LikeController::class,'dislike']);
Route::post('check', [LikeController::class,'check']);
Route::post('reportpost', [ReportController::class,'store']);

Route::get('personal_info', [ProfileController::class,'personalInfo'])->name('get_personal_info');
Route::patch('update_personal_info', [ProfileController::class,'updatePersonalInfo'])->name('update_personal_info');

Route::get('change_password', [ProfileController::class,'changePassword'])->name('get_change_password');
Route::patch('update_change_password', [ProfileController::class,'updateTheChangePassword'])->name('update_change_password');

Route::get('suggestions', [ProfileController::class,'suggestions'])->name('get_suggestions');
Route::patch('update_suggestions', [ProfileController::class,'updateSuggestion'])->name('update_suggestions');


//Delete Attachment
Route::get('delete_the_attachment/{id}', [AttachmentController::class,'delete'])->name('the_attachments.delete');

});


// Contacts
Route::get('contact_us', [ContactController::class,'show'])->name('contact_us');
Route::post('sendcontact', [ContactController::class,'store'])->name('submit_contact_us');

// pages
Route::get('the_page/{the_page}', [PageController::class,'show'])->name('the_page');

//Social Login
//Route::get('/auth/social/{social}', [SocialLoginController::class,'redirectToSocial'])->name('social');
//Route::get('/auth/{social}/callback', [SocialLoginController::class,'handleSocialCallback']);

//Search
Route::post('search', [SearchController::class,'getFilters'])->name('search');


/*---------------- Admin Panel ------------------*/

// DON'T Put it inside the '/admin' Prefix , Otherwise you'll never get the page due to assign.guard that will redirect you too many times
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm']);
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

Route::group(['middleware' => 'assign.guard:admin', 'prefix' => 'admin'],function(){

    Route::get('dashboard', [DashboardController::class, 'index']);

    // Users
    Route::resource('users', UserController::class);
    Route::get('users/{user}/delete', [UserController::class , 'destroy'])->name('users.delete');

    // Admins
    Route::resource('admins', AdminController::class);

    // Categories & Subcategories
    Route::resource('categories', CategoryController::class);
    Route::get('categories/{category}/delete', [CategoryController::class , 'destroy'])->name('categories.delete');
    Route::resource('categories/{category}/subcategories', SubcategoryController::class);
    Route::get('categories/{category}/subcategories/{subcategory}/delete', [SubcategoryController::class , 'destroy'])->name('subcategories.delete');

    // Settings Panels Page
    Route::get('the_settings', [SettingsController::class,'index'])->name('the_settings');

    // Settings
    Route::get('settings', [SettingsController::class,'edit'])->name('settings.edit');
    Route::patch('settings/update', [SettingsController::class,'update'])->name('settings.update');

    // Pages
    Route::get('pages/{page}', [AdminPageController::class , 'edit'])->name('pages.edit');
    Route::patch('pages/{page}', [AdminPageController::class , 'update'])->name('pages.update');

    // Posts
    Route::resource('posts', AdminPostController::class);
    Route::get('posts/{post}/delete', [AdminPostController::class , 'destroy'])->name('posts.delete');

    // Delete Post Attachment
    Route::get('delete_attachment/{id}', [AttachmentController::class,'delete'])->name('attachments.delete');

    // Post Enumerations
    Route::get('posts/type_enum/most_liked', [PostEnumController::class,'mostLiked'])->name('most_liked_posts');
    Route::get('posts/type_enum/most_disliked', [PostEnumController::class,'mostDisLiked'])->name('most_disliked_posts');
    Route::get('posts/type_enum/most_reported', [PostEnumController::class,'mostReported'])->name('most_reported_posts');
    Route::get('posts/type_enum/blacklisted', [PostEnumController::class,'blackListed'])->name('blacklisted_posts');

    // Post Blacklist
    Route::get('blacklist/{slug}/{type}/blacklist', [BlackListController::class,'blacklist'])->name('blacklist');
    Route::get('blacklist/{slug}/{type}/unblacklist', [BlackListController::class,'unblacklist'])->name('unblacklist');

    // Contact
    Route::resource('contacts', AdminContactController::class);

    // Countries
    Route::resource('countries', CountryController::class);
    Route::get('countries/{country}/delete', [CountryController::class,'destroy'])->name('countries.delete');


    // Cities
    Route::resource('countries/{country}/cities', CityController::class);
    Route::get('countries/{country}/cities/{city}/delete', [CityController::class,'destroy'])->name('cities.delete');

});


/*---------------- General ------------------*/

//Get All Areas (Countries And Cities)
Route::resource('areas/{id}', AreaController::class)->only(['index']);

// Get All SubCategories
Route::get('list_subcategories/{id}', [SubCategoryController::class,'list'])->name('list_subcategories');

// Get All Tags
Route::get('tags', [TagController::class,'index'])->name('tags.index');


// Google Auth
Route::controller(GoogleController::class)->group(function(){
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});
