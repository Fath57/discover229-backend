<?php

use App\Http\Controllers\Api\Auth\ProvidersAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// OAuth provider routes
Route::get('/auth/providers/{provider}/redirect', [ProvidersAuthController::class, 'redirectToProvider']);
Route::get('/auth/providers/{provider}/callback', [ProvidersAuthController::class, 'handleProviderCallback']);
