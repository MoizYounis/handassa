<?php

namespace App\Providers;

use App\Contracts\AuthContract;
use App\Contracts\PostContract;
use App\Services\AuthService;
use App\Services\PostService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthContract::class,
            function ($app) {
                return $app->make(AuthService::class);
            }
        );

        $this->app->bind(
            PostContract::class,
            function ($app) {
                return $app->make(PostService::class);
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
