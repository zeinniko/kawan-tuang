<?php

namespace App\Filament\Admin\Resources\StockMovements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu Mutasi')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('store.name')
                    ->label('Cabang')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => "SKU: {$record->product?->sku}"),

                TextColumn::make('type')
                    ->label('Tipe Mutasi')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'restock' => 'success',
                        'order_sale' => 'info',
                        'damaged', 'expired' => 'danger',
                        'transfer' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('change_amount')
                    ->label('Perubahan')
                    ->sortable()
                    ->weight('bold')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state > 0 ? "+{$state}" : "{$state}"),

                TextColumn::make('reference_id')
                    ->label('No. Referensi')
                    ->searchable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('store_id')
                    ->label('Cabang Toko')
                    ->relationship('store', 'name'),

                SelectFilter::make('type')
                    ->label('Tipe Mutasi')
                    ->options([
                        'restock' => 'Restock',
                        'order_sale' => 'Order Sale',
                        'damaged' => 'Damaged',
                        'expired' => 'Expired',
                        'transfer' => 'Transfer',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}