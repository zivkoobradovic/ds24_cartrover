<?php

namespace App\Providers;

use App\Models\Vendor;
use App\Services\Digistore24Service;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->singleton(Digistore24Service::class, function ($app) {
        //     return new Digistore24Service();
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::model('vendor', Vendor::class);
    }
}
