<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Middleware\AdminRoleMiddleware;
use App\Http\Middleware\CheckOwner;
use App\Http\Middleware\CheckProductOwner;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['verified'])->group(function () {
        Route::middleware(AdminRoleMiddleware::class)->group(function () {
            Route::crud('user', 'UserCrudController');
        });
        Route::middleware(CheckOwner::class)->group(function () {
            Route::crud('category', 'CategoryCrudController');
            Route::crud('product', 'ProductCrudController');
        });
    });
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
