<?php

namespace App\Filament\Admin\Resources\Orders\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->color('info'),

                TextColumn::make('user.full_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('store.name')
                    ->label('Cabang Toko')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fulfillment_type')
                    ->label('Fulfillment')
                    ->badge()
                    ->color(fn ($state) => $state === 'delivery' ? 'warning' : 'success')
                    ->formatStateUsing(fn ($state) => $state === 'delivery' ? 'Delivery' : 'Pick Up'),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'completed' => 'success',
                        'paid', 'processing', 'delivering' => 'info',
                        'pending_payment' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Waktu Pesan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending_payment' => 'Pending Payment',
                        'paid' => 'Paid',
                        'processing' => 'Processing',
                        'delivering' => 'Delivering / Ready Pickup',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('fulfillment_type')
                    ->label('Tipe Fulfillment')
                    ->options([
                        'delivery' => 'Delivery',
                        'pickup' => 'Pick Up',
                    ]),

                SelectFilter::make('store_id')
                    ->label('Cabang Toko')
                    ->relationship('store', 'name'),
            ])
            ->actions([
                Action::make('process')
                    ->label('Proses')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'paid')
                    ->action(fn ($record) => $record->update(['status' => 'processing'])),

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