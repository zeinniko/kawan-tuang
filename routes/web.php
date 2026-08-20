<?php

use App\Http\Controllers\Marketplace\AuthController;
use App\Http\Controllers\Marketplace\HomeController;
use App\Http\Controllers\Marketplace\CatalogController;
use App\Http\Controllers\Marketplace\CartController;
use App\Http\Controllers\Marketplace\ProfileController;
use App\Http\Controllers\Marketplace\OrderController; // <-- Tambahkan Import Ini
use App\Http\Controllers\Marketplace\StoreController;
use App\Http\Controllers\Marketplace\UserAddressWebController;
use Illuminate\Support\Facades\Route;

// --- Public & Catalog Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// --- Guest / Auth Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
});

// --- Customer Account & Transaksi (Harus Login) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/items', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/items/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/voucher/apply', [CartController::class, 'applyVoucher'])->name('cart.voucher.apply');

    // Orders Routes (Daftar & Tracking Pesanan) <-- TAMBAHKAN DI SINI
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store'); // <-- Tambahkan baris ini

    Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');

    Route::prefix('profile/addresses')->as('profile.addresses.')->group(function () {
        Route::get('/', [UserAddressWebController::class, 'index'])->name('index');
        Route::get('/create', [UserAddressWebController::class, 'create'])->name('create');
        Route::post('/', [UserAddressWebController::class, 'store'])->name('store');
        Route::get('/{address}/edit', [UserAddressWebController::class, 'edit'])->name('edit');
        Route::put('/{address}', [UserAddressWebController::class, 'update'])->name('update');
        Route::delete('/{address}', [UserAddressWebController::class, 'destroy'])->name('destroy');
        Route::patch('/{address}/set-primary', [UserAddressWebController::class, 'setPrimary'])->name('set-primary');
    });
});
