<?php

namespace App\Providers;

use App\Services\NotifikasiService;
use App\View\Composers\NotifikasiComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if (config('app.env') !== 'local' || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            URL::forceScheme('https');
        }
        // Share notifications to all views that use layouts.app (via composer)
        View::composer('layouts.app', NotifikasiComposer::class);
    }
}
