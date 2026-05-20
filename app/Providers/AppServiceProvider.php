<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        $appUrl = config('app.url');
        $host = request()->getHost();

        if ($host && (str_contains($host, 'devtunnels.ms') || str_contains($host, 'ngrok-free.app') || str_contains($host, 'trycloudflare.com'))) {
            URL::forceRootUrl('https://'.$host);
            URL::forceScheme('https');
        }

        if (app()->isProduction() && $appUrl && ! str_contains($appUrl, 'localhost') && ! str_contains($appUrl, '127.0.0.1')) {
            URL::forceRootUrl($appUrl);

            if (str_starts_with($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }

        // Share active period globally
        view()->composer('*', function ($view) {
            $activePeriode = \App\Models\Periode::where('is_active', true)->first();
            $view->with('globalActivePeriode', $activePeriode);
        });
    }
}
