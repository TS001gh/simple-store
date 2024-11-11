<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailVerificationController;
use App\Http\Middleware\AdminRoleMiddleware;
use App\Http\Middleware\CheckOwner;
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


// Display the email verification notice
// Route::get('/admin/email/verify', [EmailVerificationController::class, 'showVerificationNotice'])
//     ->middleware('auth')
//     ->name('verification.notice');

// // Handle the email verification link
// Route::get('/admin/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
//     ->middleware(['auth', 'signed'])
//     ->name('verification.verify');

// // Resend the email verification notification
// Route::post('/admin/email/resend', [EmailVerificationController::class, 'resend'])
//     ->middleware(['auth', 'throttle:6,1'])
//     ->name('verification.resend');
/**
 * DO NOT ADD ANYTHING HERE.
 */
