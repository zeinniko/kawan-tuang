<?php

namespace App\Filament\Admin\Resources\Orders\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends BaseWidget
{
    // Auto-refresh statistik tiap 15 detik
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        /** @var User|null $user */
        $user = auth()->user();

        // Base query sesuai role/store admin
        $query = Order::query();

        if ($user && $user->isAdmin()) {
            $query->where('store_id', $user->store_id);
        }

        // 1. Pesanan Perlu Diproses (Paid & Processing)
        $pendingCount = (clone $query)
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_PROCESSING])
            ->count();

        // 2. Pesanan Dalam Pengiriman / Siap Pick Up
        $readyOrShippingCount = (clone $query)
            ->whereIn('status', [Order::STATUS_DELIVERING, Order::STATUS_READY_FOR_PICKUP])
            ->count();

        // 3. Pesanan Selesai Hari Ini
        $completedTodayCount = (clone $query)
            ->where('status', Order::STATUS_COMPLETED)
            ->whereDate('updated_at', today())
            ->count();

        // 4. Total Omset Hari Ini
        $todayRevenue = (clone $query)
            ->whereNotIn('status', [Order::STATUS_CANCELLED, Order::STATUS_PENDING_PAYMENT])
            ->whereDate('created_at', today())
            ->sum('total_amount');

        return [
            Stat::make('Perlu Diproses', $pendingCount . ' Order')
                ->description('Menunggu penyiapan & pengemasan')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'gray'),

            Stat::make('Dikirim / Siap Pickup', $readyOrShippingCount . ' Order')
                ->description('Dalam perjalanan / siap diambil')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),

            Stat::make('Selesai Hari Ini', $completedTodayCount . ' Order')
                ->description('Transaksi sukses hari ini')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Omset Hari Ini', 'Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->description('Total penjualan hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}