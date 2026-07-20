<?php

namespace App\Providers;

use App\Services\NotifikasiService;
use App\View\Composers\NotifikasiComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NotifikasiService::class, fn() => new NotifikasiService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share notifications to all views that use layouts.app (via composer)
        View::composer('layouts.app', NotifikasiComposer::class);
    }
}
