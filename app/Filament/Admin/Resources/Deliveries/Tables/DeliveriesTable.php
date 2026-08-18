<?php

namespace App\Filament\Admin\Resources\Deliveries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DeliveriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('courier_provider')
                    ->label('Kurir')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('service_type')
                    ->label('Layanan'),

                TextColumn::make('waybill_number')
                    ->label('No. Resi')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('driver_name')
                    ->label('Driver')
                    ->description(fn ($record) => $record->driver_phone)
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'delivered' => 'success',
                        'delivering', 'picking_up' => 'info',
                        'pending', 'allocated' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pengiriman')
                    ->options([
                        'pending' => 'Pending',
                        'allocated' => 'Allocated',
                        'picking_up' => 'Picking Up',
                        'delivering' => 'Delivering',
                        'delivered' => 'Delivered',
                        'failed' => 'Failed',
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