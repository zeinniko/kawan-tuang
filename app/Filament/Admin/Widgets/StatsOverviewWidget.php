<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\StoreStock;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $totalRevenue = Order::whereIn('status', [
            Order::STATUS_PAID,
            Order::STATUS_PROCESSING,
            Order::STATUS_DELIVERING,
            Order::STATUS_COMPLETED
        ])->sum('total_amount');

        $ordersToday = Order::whereDate('created_at', Carbon::today())->count();
        $revenueToday = Order::whereDate('created_at', Carbon::today())
            ->whereIn('status', [
                Order::STATUS_PAID,
                Order::STATUS_PROCESSING,
                Order::STATUS_DELIVERING,
                Order::STATUS_COMPLETED
            ])->sum('total_amount');

        $lowStockCount = StoreStock::where('stock', '<=', 5)->count();

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total akumulasi penjualan sukses')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pesanan Hari Ini', $ordersToday . ' Transaksi')
                ->description('Omset hari ini: Rp ' . number_format($revenueToday, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Stok Kritis Cabang', $lowStockCount . ' Item')
                ->description('Produk dengan stok <= 5 pcs')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success'),
        ];
    }
}