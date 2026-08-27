<?php

namespace App\Filament\Admin\Widgets;

use App\Models\OrderItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopSellingProductsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '🔥 5 Produk Terlaris (Top-Selling Products)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderItem::query()
                    ->select(
                        DB::raw('MAX(id) as id'),
                        'product_id',
                        'product_name_snapshot',
                        DB::raw('SUM(quantity) as total_qty'),
                        DB::raw('SUM(subtotal_price) as total_sales')
                    )
                    ->whereHas('order', function (Builder $query) {
                        $query->whereIn('status', [
                            \App\Models\Order::STATUS_PAID,
                            \App\Models\Order::STATUS_PROCESSING,
                            \App\Models\Order::STATUS_DELIVERING,
                            \App\Models\Order::STATUS_COMPLETED
                        ]);
                    })
                    ->groupBy('product_id', 'product_name_snapshot')
                    ->orderByDesc('total_qty')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('product_name_snapshot')
                    ->label('Nama Produk')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('product.sku')
                    ->label('SKU')
                    ->fontFamily('mono')
                    ->default('-'),

                Tables\Columns\TextColumn::make('total_qty')
                    ->label('Total Terjual')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => number_format($state) . ' Botol/Unit'),

                Tables\Columns\TextColumn::make('total_sales')
                    ->label('Total Penjualan')
                    ->money('IDR', true)
                    ->color('success'),
            ])
            ->paginated(false);
    }
}