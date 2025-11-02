<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\CsrfCookieController;
use App\Http\Controllers\Api\Auth\ProvidersAuthController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Auth\UserProfileController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// ============ Authentication Routes ============

// Public authentication routes
Route::post('/auth/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('/auth/register', [RegisteredUserController::class, 'store']);
Route::get('/auth/countries', [RegisteredUserController::class, 'create'])->name('countries.create');


// Sanctum CSRF cookie endpoint (initialize session for SPA)
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'index']);

// ============ Protected Routes ============

Route::middleware(['auth:sanctum'])->group(function () {
    // Authentication
    Route::post('/auth/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::get('/user', [UserProfileController::class, 'show']);

    // User CRUD routes
    Route::apiResource('users', UserController::class);

    // Role & Permission Management
    Route::apiResource('roles', RoleController::class);
    Route::post('/roles/{id}/permissions', [RoleController::class, 'assignPermissions']);
    Route::get('/roles/{id}/users', [RoleController::class, 'getUsersByRole']);

    // Modules
    Route::get('/modules', [ModuleController::class, 'index']);

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::get('/permissions/grouped', [PermissionController::class, 'grouped']);

    // User Role Assignment
    Route::post('/users/{userId}/assign-role', [PermissionController::class, 'assignRoleToUser']);
    Route::delete('/users/{userId}/remove-role', [PermissionController::class, 'removeRoleFromUser']);
});
