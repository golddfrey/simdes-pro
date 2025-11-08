<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Temporary DB query listener for performance diagnosis.
        // This logs every executed query with its duration. Remove after debugging.
        DB::listen(function ($query) {
            try {
                Log::debug('[DB] sql', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                ]);
            } catch (\Throwable $e) {
                // avoid letting logging cause errors
            }
        });
    }
}
