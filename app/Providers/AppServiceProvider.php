<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\View\Composers\CategoryComposer;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Check if table exists to avoid errors on fresh migration
        if (Schema::hasTable('settings')) {
            $globalSettings = Setting::pluck('value', 'key')->toArray();
            View::share('globalSettings', $globalSettings);
        }

        // Share Cart Count (Existing code)
        View::composer('*', function ($view) {
            $cartCount = 0;
            if (Auth::check()) {
                $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
