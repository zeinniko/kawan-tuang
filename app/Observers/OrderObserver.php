<?php

namespace App\Observers;

use App\Filament\Admin\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Notifications\UserPushNotification;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class OrderObserver
{
    /**
     * Trigger saat Status Order Berubah (Push Notification ke User HP)
     */
    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            $user = $order->user;

            if (! $user) return;

            match ($order->status) {
                // 1. Order Pesanan Berhasil
                Order::STATUS_PAID => $user->notify(new UserPushNotification(
                    title: 'Order Berhasil 🛒',
                    body: "Pembayaran pesanan #{$order->order_number} telah diterima.",
                    data: ['type' => 'order_detail', 'order_id' => (string) $order->id]
                )),

                // 2. Pesanan Diproses
                Order::STATUS_PROCESSING => $user->notify(new UserPushNotification(
                    title: 'Pesanan Diproses 👨‍🍳',
                    body: "Pesanan #{$order->order_number} sedang disiapkan oleh tim kami.",
                    data: ['type' => 'order_detail', 'order_id' => (string) $order->id]
                )),

                // 3. Pesanan Delivery Dikirim
                Order::STATUS_DELIVERING => $user->notify(new UserPushNotification(
                    title: 'Pesanan Dikirim 🚚',
                    body: "Pesanan #{$order->order_number} dalam perjalanan menuju lokasimu.",
                    data: ['type' => 'order_detail', 'order_id' => (string) $order->id]
                )),

                // 4. Pesanan Pickup Siap
                Order::STATUS_READY_FOR_PICKUP => $user->notify(new UserPushNotification(
                    title: 'Pesanan Siap Diambil 🛍️',
                    body: "Pesanan #{$order->order_number} sudah siap diambil di outlet.",
                    data: ['type' => 'order_detail', 'order_id' => (string) $order->id]
                )),

                // 5. Pesanan Completed & Minta Review
                Order::STATUS_COMPLETED => $user->notify(new UserPushNotification(
                    title: 'Pesanan Selesai ⭐',
                    body: "Pesanan #{$order->order_number} telah selesai. Berikan ulasan terbaikmu!",
                    data: ['type' => 'order_review', 'order_id' => (string) $order->id]
                )),

                default => null,
            };
        }
    }

    /**
     * Trigger saat Pesanan Baru Dibuat/Diterima (Lonceng Notifikasi ke Admin Web)
     */
    public function created(Order $order): void
    {
        // Ambil semua user Admin, Superadmin, & Warehouse Staff
        $admins = User::whereIn('role', [
            User::ROLE_SUPERADMIN,
            User::ROLE_ADMIN,
            User::ROLE_WAREHOUSE_STAFF,
        ])->get();

        $customerName = $order->user?->full_name ?? $order->user?->name ?? 'Customer';
        
        // Gunakan URL string langsung (Tanpa Closure fn() => ...) agar aman diserialisasi ke DB
        $orderUrl = "/admin/orders/{$order->id}";

        Notification::make()
            ->title('Pesanan Baru Masuk! 🛒')
            ->body("Pesanan #{$order->order_number} dari {$customerName} sebesar Rp " . number_format($order->total_amount, 0, ',', '.'))
            ->icon('heroicon-o-shopping-bag')
            ->color('success')
            ->actions([
                Action::make('view')
                    ->label('Lihat Pesanan')
                    ->url($orderUrl),
            ])
            ->sendToDatabase($admins);
    }
}