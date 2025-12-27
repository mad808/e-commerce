<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Dashboard - Open to all Staff (Admin/Operator)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// --- PERMISSION: users ---
Route::middleware(['check_perm:users'])->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::post('/{id}/block', [UserController::class, 'toggleBlock'])->name('block');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });
});

// --- PERMISSION: products ---
Route::middleware(['check_perm:products'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('attributes', AttributeController::class);
});

// --- PERMISSION: sliders (Includes News and Locations) ---
Route::middleware(['check_perm:sliders'])->group(function () {
    Route::resource('sliders', SliderController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('news', NewsController::class);
    Route::patch('locations/{location}/toggle', [LocationController::class, 'toggleStatus'])->name('locations.toggle');
    Route::post('news/{news}/toggle-status', [NewsController::class, 'toggleStatus'])->name('news.toggle-status');
});

// --- PERMISSION: orders (Includes Financials) ---
Route::middleware(['check_perm:orders'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/financial-report', [FinancialController::class, 'index'])->name('financial.index');
    Route::get('/financial-chart', [FinancialController::class, 'getChartData'])->name('financial.chart');
});

// --- PERMISSION: messages ---
Route::middleware(['check_perm:messages'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{userId}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::delete('/messages/delete/{id}', [MessageController::class, 'deleteMessage'])->name('messages.delete');
});

// --- PERMISSION: settings ---
Route::middleware(['check_perm:settings'])->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// Profile Management (Self-service for all admin/operators)
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Search and Global Helpers
Route::get('/global-search', [SearchController::class, 'index'])->name('global.search');
Route::get('/search/ajax', [SearchController::class, 'searchAjax'])->name('search.ajax');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
