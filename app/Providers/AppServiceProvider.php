<?php

namespace App\Providers;

use App\Contracts\AuthContract;
use App\Contracts\NotificationContract;
use App\Contracts\PostContract;
use App\Services\AuthService;
use App\Services\NotificationService;
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

        $this->app->bind(
            NotificationContract::class,
            function ($app) {
                return $app->make(NotificationService::class);
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
