<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\NewsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================
// 1. PUBLIC ROUTES (No Login Required)
// ==========================

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Home Data (Sliders, Categories, Popular Products)
Route::get('/home', [HomeController::class, 'index']); // For the main dashboard screen
Route::get('/settings', [HomeController::class, 'settings']); // Contact info, etc.

// Products & Categories
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/products', [ProductController::class, 'index']); // Search & Filter here
Route::get('/products/{id}', [ProductController::class, 'show']); // Product Details

// News
Route::get('/news', [NewsController::class, 'index']);


// ==========================
// 2. PROTECTED ROUTES (Login Required)
// ==========================
Route::middleware('auth:sanctum')->group(function () {

    // Auth Actions
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']); // Get current user info

    // Profile Management
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::post('/profile/password', [ProfileController::class, 'updatePassword']);

    // Cart (Database based, since you have CartItem model)
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'store']);
    Route::post('/cart/update-qty', [CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);

    // Favorites / Wishlist
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']); // Add or Remove

    // Checkout & Orders
    Route::post('/checkout', [OrderController::class, 'store']); // Place Order
    Route::get('/orders', [OrderController::class, 'index']); // Order History
    Route::get('/orders/{id}', [OrderController::class, 'show']); // Order Details

    // Chat / Contact Admin
    Route::get('/messages', [MessageController::class, 'index']); // Get chat history
    Route::post('/messages', [MessageController::class, 'store']); // Send message
});