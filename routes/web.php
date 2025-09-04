<?php
use App\Http\Controllers\Admin\PricingRuleController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FeaturedPostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\PostEnumController;
use App\Http\Controllers\Admin\BlackListController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CityController as AdminCityController;
use App\Http\Controllers\Admin\AdTypeController;

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
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CommissionPaymentController;

use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Auth\PhoneVerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
// راوت التسجيل منفصل
use App\Http\Controllers\Auth\RegisterController;
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
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


Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);


// مسارات الدفع والمدفوعات
Route::group(['prefix' => 'payments', 'middleware' => ['auth']], function () {
    Route::get('/checkout/{payment}', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/process', [PaymentController::class, 'process'])->name('payments.process');
    Route::post('/verify-otp', [PaymentController::class, 'verifyOTP'])->name('payments.verify-otp');

    // نجاح وفشل الدفع العام
    Route::view('/success', 'payments.success')->name('payments.success');
    Route::view('/error', 'payments.error')->name('payments.error');

    // بوابة الراجحي للدفع
    Route::prefix('rajhi')->name('rajhi.')->group(function () {
        Route::get('/success', [PaymentController::class, 'handleSuccess'])->name('success');
        Route::get('/error', [PaymentController::class, 'handleError'])->name('error');
        Route::post('/webhook', [PaymentController::class, 'handleWebhook'])->name('webhook');
        Route::get('/return', [PaymentController::class, 'handleReturn'])->name('return');
    });
});


// صفحات سجل المدفوعات في لوحة التحكم
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/download', [AdminPaymentController::class, 'download'])->name('payments.download');

    // سجل مدفوعات العمولة
    Route::get('commission-payments', [CommissionPaymentController::class, 'index'])->name('commission_payments.index');
    Route::get('commission-payments/{id}', [CommissionPaymentController::class, 'show'])->name('commission_payments.show');
});



Route::get('/', [PostController::class, 'index'])->name('my_home');



Route::get('/clearcache185', function () {
    $clearcache = Artisan::call('cache:clear');
    echo "Cache cleared<br>";

    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";

    $clearconfig = Artisan::call('config:cache');
    echo "Config cleared<br>";


    $sotagelink = \Illuminate\Support\Facades\Artisan::call('storage:link');
});

Route::post('/payment', [PageController::class, 'payment'])->name('payment.process');
Route::get('/success', function (Request $request) {
    echo "تمت عمليه  الدفع بنجاح";
});

//     Route::get('/ads/{post}/promote', function($postId) {
//     return "Route works! Post ID: " . $postId;
// });

Route::post('/ads/{post}/calculate-price', [PostController::class, 'calculatePrice'])->name('ads.calculate-price');
Route::post('/ads/{post}/checkout', [PostController::class, 'checkout'])->name('ads.checkout');

// مسارات الإعلانات المميزة
Route::middleware('auth')->group(function () {
    Route::post('/posts/{pageID}/feature', [FeaturedPostController::class, 'feature'])->name('posts.feature');
    Route::post('/posts/{post}/unfeature', [FeaturedPostController::class, 'unfeature'])->name('posts.unfeature');
});

Auth::routes(['verify' => true]);

Route::get('the_posts', [PostController::class, 'index'])->name('the_posts.index');
Route::get('the_posts/{the_post}', [PostController::class, 'show'])->name('the_posts.show');
// previsoully we looking for by the title but now by Id
// Posts
Route::get('new_post', [PostController::class, 'create'])->name('the_posts.create');
Route::post('new_post', [PostController::class, 'store'])->name('the_posts.store');

// مسارات عرض تمييز الإعلان
Route::get('posts/{id}/featured-offer', [PostController::class, 'showFeaturedOffer'])->name('posts.featured.offer');
Route::get('posts/{id}/featured-checkout', [PostController::class, 'featuredCheckout'])->name('posts.featured.checkout');
Route::get('posts/{id}/featured-confirm', [PostController::class, 'confirmFeatured'])->name('posts.featured.confirm');

// user logout
Route::get('userlogout', function () {
    auth()->logout();
    return redirect('login');
});
Route::group(['middleware' => 'verified'], function () {



    // User Home Profile
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('the_posts/{the_post}/edit', [PostController::class, 'edit'])->name('the_posts.edit');
    Route::put('the_posts/{the_post}', [PostController::class, 'update'])->name('the_posts.update');

    Route::delete('the_posts/{the_post}', [PostController::class, 'destroy'])->name('the_posts.destroy');


    //Post Like & Dislike
    Route::post('like', [LikeController::class, 'like']);
    Route::post('dislike', [LikeController::class, 'dislike']);
    Route::post('check', [LikeController::class, 'check']);
    Route::post('reportpost', [ReportController::class, 'store']);

    Route::get('personal_info', [ProfileController::class, 'personalInfo'])->name('get_personal_info');
    Route::patch('update_personal_info', [ProfileController::class, 'updatePersonalInfo'])->name('update_personal_info');

    Route::get('change_password', [ProfileController::class, 'changePassword'])->name('get_change_password');
    Route::patch('update_change_password', [ProfileController::class, 'updateTheChangePassword'])->name('update_change_password');

    Route::get('suggestions', [ProfileController::class, 'suggestions'])->name('get_suggestions');
    Route::patch('update_suggestions', [ProfileController::class, 'updateSuggestion'])->name('update_suggestions');


    //Delete Attachment
    Route::get('delete_the_attachment/{id}', [AttachmentController::class, 'delete'])->name('the_attachments.delete');

});


// Contacts
Route::get('contact_us', [ContactController::class, 'show'])->name('contact_us');
Route::post('sendcontact', [ContactController::class, 'store'])->name('submit_contact_us');

// pages
Route::get('the_page/{the_page}', [PageController::class, 'show'])->name('the_page');

//Social Login
// Route::get('/auth/social/{social}', [SocialLoginController::class,'redirectToSocial'])->name('social');
// Route::get('/auth/{social}/callback', [SocialLoginController::class,'handleSocialCallback']);

//Search
Route::post('search', [SearchController::class, 'getFilters'])->name('search');


/*---------------- Admin Panel ------------------*/

// DON'T Put it inside the '/admin' Prefix , Otherwise you'll never get the page due to assign.guard that will redirect you too many times
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm']);
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin'], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class);
    Route::get('users/{user}/delete', [UserController::class, 'destroy'])->name('users.delete');

    // Admins
    Route::resource('admins', AdminController::class);

    // Categories & Subcategories
    Route::resource('categories', CategoryController::class);
    Route::get('categories/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.delete');
    Route::resource('categories/{category}/subcategories', SubcategoryController::class);
    Route::get('categories/{category}/subcategories/{subcategory}/delete', [SubcategoryController::class, 'destroy'])->name('subcategories.delete');

    // Settings Panels Page
    Route::get('the_settings', [SettingsController::class, 'index'])->name('the_settings');

    // Settings
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('settings/update', [SettingsController::class, 'update'])->name('settings.update');

    // Pages
    Route::get('pages/{page}', [AdminPageController::class, 'edit'])->name('pages.edit');
    Route::patch('pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');

    // Posts
    Route::resource('posts', AdminPostController::class);
    Route::get('posts/{post}/delete', [AdminPostController::class, 'destroy'])->name('posts.delete');
    Route::get('posts/{post}/block', [AdminPostController::class, 'block'])->name('posts.block');

    // Delete Post Attachment
    Route::get('delete_attachment/{id}', [AttachmentController::class, 'delete'])->name('attachments.delete');

    // Post Enumerations
    Route::get('posts/type_enum/featured', [PostEnumController::class, 'featured'])->name('featured_posts');
    Route::get('posts/type_enum/most_liked', [PostEnumController::class, 'mostLiked'])->name('most_liked_posts');
    Route::get('posts/type_enum/most_disliked', [PostEnumController::class, 'mostDisLiked'])->name('most_disliked_posts');
    Route::get('posts/type_enum/most_reported', [PostEnumController::class, 'mostReported'])->name('most_reported_posts');
    Route::get('posts/type_enum/blacklisted', [PostEnumController::class, 'blackListed'])->name('blacklisted_posts');

    // Post Blacklist
    Route::get('blacklist/{slug}/{type}/blacklist', [BlackListController::class, 'blacklist'])->name('blacklist');
    Route::get('blacklist/{slug}/{type}/unblacklist', [BlackListController::class, 'unblacklist'])->name('unblacklist');

    // Contact
    Route::resource('contacts', AdminContactController::class);

    // Countries
    Route::resource('countries', CountryController::class);
    Route::get('countries/{country}/delete', [CountryController::class, 'destroy'])->name('countries.delete');


    // Cities
    Route::resource('countries/{country}/cities', AdminCityController::class);
    Route::get('countries/{country}/cities/{city}/delete', [AdminCityController::class, 'destroy'])->name('cities.delete');



    Route::name('admin.')->group(function () {

        Route::resource('ad-types', AdTypeController::class);

        // إدارة قواعد التسعير
        Route::resource('pricing-rules', PricingRuleController::class);
        Route::patch('pricing-rules/{pricingRule}/toggle', [PricingRuleController::class, 'toggleStatus'])
            ->name('pricing-rules.toggle');


    });
});
/*---------------- General ------------------*/

//Get All Areas (Countries And Cities)
Route::resource('areas/{id}', AreaController::class)->only(['index']);

// Get All SubCategories
Route::get('list_subcategories/{id}', [SubCategoryController::class, 'list'])->name('list_subcategories');

// Get All Tags
Route::get('tags', [TagController::class, 'index'])->name('tags.index');


// Google Auth
Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

// Facebook Auth
Route::controller(FacebookController::class)->group(function () {
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});



// Route::middleware('auth')->group(function() {
// مسارات الإعلانات الموجودة...

// مسارات التمييز

// });

// routes/web.php

Route::post('phone/send-code', [PhoneVerificationController::class, 'sendCode'])->name('phone.send.code');