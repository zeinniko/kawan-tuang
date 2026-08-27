<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;
    
    protected ?string $heading = '📈 Grafik Tren Penjualan 7 Hari Terakhir';
    
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $dates = collect();
        $totals = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates->push($date->format('d M'));

            $sum = Order::whereDate('created_at', $date)
                ->whereIn('status', [
                    Order::STATUS_PAID,
                    Order::STATUS_PROCESSING,
                    Order::STATUS_DELIVERING,
                    Order::STATUS_COMPLETED,
                ])
                ->sum('total_amount');

            $totals->push($sum);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Rp)',
                    'data' => $totals->toArray(),
                    'fill' => 'start',
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $dates->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}