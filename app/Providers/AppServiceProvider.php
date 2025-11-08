<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Debugging DB listener removed — logging was temporary during diagnosis

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
        // No-op: diagnostic DB listener previously used for profiling has been removed.
    }
}
