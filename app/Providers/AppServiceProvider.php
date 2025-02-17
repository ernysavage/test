<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AttachmentService;
use App\Services\AuthService;
use App\Services\ClientService;
use App\Services\Core\ResponseService;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        $this->app->singleton(AttachmentService::class, function ($app) {
            return new AttachmentService();
        });

        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService();
        });

        $this->app->singleton(ResponseService::class, function ($app) {
            return new ResponseService([]); 
        });

        $this->app->singleton(ClientService::class, function ($app) {
            return new ClientService($app->make(ResponseService::class));
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
