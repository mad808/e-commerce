<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FrontNewsController;
use App\Http\Controllers\LocationController;
use App\Livewire\ShopComponent;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Public Routes (Everyone can see these)
|--------------------------------------------------------------------------
*/

// Homepage & Shop
Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/shop', ShopComponent::class)->name('shop'); // Moved here (Public)
Route::get('/product/{slug}', [FrontController::class, 'product'])->name('product.show');

// News & Search
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [FrontNewsController::class, 'index'])->name('index');
    Route::get('/{news}', [FrontNewsController::class, 'show'])->name('show');
});
Route::get('/ajax/search', [FrontController::class, 'searchAjax'])->name('search.ajax');

// Language & Location
Route::get('/language/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'tm', 'ru'])) abort(400);
    Session::put('locale', $locale);
    return redirect()->back();
})->name('locale.switch');
Route::post('/set-location', [LocationController::class, 'setLocation'])->name('set.location');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Must Login + Not Blocked)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.blocked'])->group(function () {

    // Cart (Viewing and Editing)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Account & Orders
    Route::get('/my-orders', [ClientController::class, 'orders'])->name('client.orders.index');
    Route::get('/my-orders/{id}', [ClientController::class, 'orderDetail'])->name('client.orders.show');
    Route::get('/profile', [ClientController::class, 'profile'])->name('client.profile');
    Route::put('/profile', [ClientController::class, 'updateProfile'])->name('client.profile.update');

    // Favorites & Contact
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle/{id}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/contact', [ClientController::class, 'chat'])->name('contact.index');
    Route::post('/contact', [ClientController::class, 'sendMessage'])->name('contact.send')->middleware('throttle:5,1');
});

require __DIR__ . '/auth.php';
