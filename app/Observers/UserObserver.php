<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\UserPushNotification;

class UserObserver
{
    /**
     * Trigger saat User Baru Berhasil Register
     */
    public function created(User $user): void
    {
        // Hanya kirim Notifikasi jika role-nya Customer
        if ($user->isCustomer()) {
            $user->notify(new UserPushNotification(
                title: 'Selamat Datang! 🎉',
                body: 'Registrasi berhasil. Selamat berbelanja di Tipsy More!',
                data: ['type' => 'register']
            ));
        }
    }
}