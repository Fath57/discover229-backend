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
        // Add CSRF token to response headers for SPA/Swagger compatibility
        \Illuminate\Support\Facades\Response::macro('withCsrfToken', function ($data = []) {
            return response()->json($data)->header('X-CSRF-Token', csrf_token());
        });
    }
}
