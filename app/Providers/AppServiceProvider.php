<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\News\NewYorkTimesService;
use App\Services\News\GuardianService;
use App\Services\News\NewsAPIService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NewYorkTimesService::class, function ($app) {
            return new NewYorkTimesService();
        });

        $this->app->singleton(GuardianService::class, function ($app) {
            return new GuardianService();
        });

        $this->app->singleton(NewsAPIService::class, function ($app) {
            return new NewsAPIService();
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
