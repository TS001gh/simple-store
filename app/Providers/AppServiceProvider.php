<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Gate;
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
        // Define gates for editing and deleting products
        Gate::define('update-product', function (Product $product): bool {
            return backpack_user() === $product->created_by;
        });
        Gate::define('update-category', function ($user, Category $category): bool {
            return $user->id === $category->created_by;
        });

        // For detecting images
        Product::observe(ProductObserver::class);
    }
}
