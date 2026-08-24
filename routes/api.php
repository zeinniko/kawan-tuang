<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\KycController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserAddressController;
use App\Http\Controllers\Api\V1\StoreController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\VibeController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\VoucherController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\WebhookController;
use App\Http\Controllers\Api\V1\ShippingController;
use App\Http\Controllers\Api\V1\ProductReviewController;

Route::prefix('v1')->group(function () {
    // Guest / Public Routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);

    // Protected Routes (Sanctum Authenticated)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Profile
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::put('/profile/change-password', [ProfileController::class, 'changePassword']);

        // KYC (Compliance 21+)
        Route::post('/kyc/upload', [KycController::class, 'upload']);
        Route::get('/kyc/status', [KycController::class, 'status']);

        Route::apiResource('/addresses', UserAddressController::class);
        Route::patch('/addresses/{address}/set-primary', [UserAddressController::class, 'setPrimary']);

        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart/items', [CartController::class, 'store']);
        Route::put('/cart/items/{cartItem}', [CartController::class, 'update']);
        Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy']);
        Route::delete('/cart/clear', [CartController::class, 'clear']);

        Route::get('/vouchers', [VoucherController::class, 'index']);
        Route::post('/vouchers/apply', [VoucherController::class, 'apply']);

        Route::post('/checkout/preview', [CheckoutController::class, 'preview']);
        Route::post('/checkout/process', [CheckoutController::class, 'process']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
        
        Route::post('/payments/snap-token', [PaymentController::class, 'generateSnapToken']);
        Route::get('/payments/{order}/status', [PaymentController::class, 'checkStatus']);
        
        Route::post('/shipping/rates', [ShippingController::class, 'getRates']);
        Route::get('/shipping/orders/{order}/track', [ShippingController::class, 'trackOrder']);
        
        Route::post('/products/{product}/reviews', [ProductReviewController::class, 'store']);
        Route::get('/user/reviews', [ProductReviewController::class, 'userReviews']);
    });


    Route::get('/stores', [StoreController::class, 'index']);
    Route::get('/stores/nearest', [StoreController::class, 'nearest']);
    Route::get('/stores/{store}', [StoreController::class, 'show']);
    Route::get('/home', [HomeController::class, 'index']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);

    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brands/{brand:slug}', [BrandController::class, 'show']);

    Route::get('/vibes', [VibeController::class, 'index']);
    Route::get('/vibes/{vibe:slug}', [VibeController::class, 'show']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product:slug}', [ProductController::class, 'show']);
    Route::get('/products/{product:slug}/reviews', [ProductReviewController::class, 'index']);
});

Route::post('/v1/webhooks/notification', [WebhookController::class, 'handleMidtrans']);
Route::post('/v1/webhooks/biteship', [WebhookController::class, 'handleBiteship']);