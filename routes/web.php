<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Marketplace Frontend)
|--------------------------------------------------------------------------
| Catatan: Route '/admin' dikelola secara otomatis oleh Filament Admin Panel.
*/

// --- Public & Catalog Routes ---
Route::get('/', function () {
    return view('marketplace.index');
})->name('home');

Route::get('/catalog', function () {
    return view('marketplace.catalog');
})->name('catalog.index');

Route::get('/products/{slug}', function ($slug) {
    return view('marketplace.product-detail', compact('slug'));
})->name('catalog.show');

// --- Guest / Auth Routes ---
Route::get('/login', function () {
    return view('marketplace.auth.login');
})->name('login');

Route::get('/register', function () {
    return view('marketplace.auth.register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('marketplace.auth.forgot-password');
})->name('forgot-password');

// --- Customer Account & Transaksi ---
Route::get('/cart', function () {
    return view('marketplace.cart');
})->name('cart.index');

Route::get('/orders', function () {
    return view('marketplace.orders');
})->name('orders.index');

Route::get('/profile', function () {
    return view('marketplace.profile');
})->name('profile.index');