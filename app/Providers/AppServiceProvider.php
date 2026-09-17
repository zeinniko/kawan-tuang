<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Order;
use App\Models\KtpVerification;
use App\Observers\UserObserver;
use App\Observers\OrderObserver;
use App\Observers\KtpVerificationObserver;

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
        User::observe(UserObserver::class);
        Order::observe(OrderObserver::class);
        KtpVerification::observe(KtpVerificationObserver::class);
    }
}
