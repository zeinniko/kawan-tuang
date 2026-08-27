<?php

namespace App\Filament\Admin\Resources\Vouchers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Voucher')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->color('info'),

                TextColumn::make('discount_type')
                    ->label('Tipe Diskon')
                    ->badge()
                    ->color(fn ($state) => $state === 'percentage' ? 'warning' : 'success')
                    ->formatStateUsing(fn ($state) => $state === 'percentage' ? 'Persentase' : 'Fixed Nominal'),

                TextColumn::make('discount_value')
                    ->label('Nilai Diskon')
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        return $record->discount_type === 'percentage'
                            ? "{$record->discount_value}%"
                            : "Rp " . number_format($record->discount_value, 0, ',', '.');
                    }),

                TextColumn::make('min_order_amount')
                    ->label('Min. Transaksi')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),

                TextColumn::make('valid_from')
                    ->label('Berlaku')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('valid_until')
                    ->label('Kadaluwarsa')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('orders_count')
                    ->label('Terpakai / Quota')
                    ->counts('orders')
                    ->formatStateUsing(fn ($state, $record) => "{$state} / " . ($record->usage_limit ?? '∞'))
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('discount_type')
                    ->label('Tipe Diskon')
                    ->options([
                        'fixed_amount' => 'Fixed Nominal',
                        'percentage' => 'Persentase',
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