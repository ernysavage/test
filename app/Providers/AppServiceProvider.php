<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AttachmentService;
use App\Services\AuthService;
use App\Services\ClientService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AttachmentService::class, function ($app) {
            return new AttachmentService();
        });

        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService();
        });

        $this->app->singleton(ClientService::class, function ($app) {
            return new ClientService();
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
