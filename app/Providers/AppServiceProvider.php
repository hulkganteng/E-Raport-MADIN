<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share active period globally
        view()->composer('*', function ($view) {
            $activePeriode = \App\Models\Periode::where('is_active', true)->first();
            $view->with('globalActivePeriode', $activePeriode);
        });
    }
}
