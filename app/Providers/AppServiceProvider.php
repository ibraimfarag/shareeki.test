<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Post;
use App\Models\Settings;
use Illuminate\Support\ServiceProvider;
use App\Services\Pricing\AdPricingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AdPricingService::class, function ($app) {
            return new AdPricingService();
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->share('settings', Settings::findOrFail(1));

        view()->share('adminContact', Contact::whereNull('title')->whereNull('image')->take(3)->OrderBy('id', 'DESC')->get());
        view()->share('adminLatestPosts', Post::Latest()->take(5)->get());

    }
}
