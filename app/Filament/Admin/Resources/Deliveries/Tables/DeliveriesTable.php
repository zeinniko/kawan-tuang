<?php

namespace App\Filament\Admin\Resources\Deliveries\Tables;

use Filament\Actions\Action;
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
                        'pending'    => 'Pending',
                        'allocated'  => 'Allocated',
                        'picking_up' => 'Picking Up',
                        'delivering' => 'Delivering',
                        'delivered'  => 'Delivered',
                        'failed'     => 'Failed',
                    ]),
            ])
            ->actions([
                // ACTION MEMBUKA LINK LIVE TRACKING BITESHIP DI TAB BARU
                Action::make('live_tracking')
                    ->label('Lacak Live')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('success')
                    ->url(fn ($record) => $record->live_tracking_url)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->live_tracking_url)),

                // EDIT ACTION: Superadmin OR Admin Cabang/Staff yang sesuai dengan store_id
                EditAction::make()
                    ->visible(function ($record) {
                        /** @var User|null $user */
                        $user = auth()->user();

                        if (! $user) return false;
                        if ($user->isSuperAdmin()) return true;

                        $storeId = $record->store_id ?? $record->order?->store_id;

                        return ($user->isAdmin() || $user->isWarehouseStaff()) && $user->store_id === $storeId;
                    }),

                // DELETE ACTION: Hanya Superadmin
                DeleteAction::make()
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                ]),
            ]);
    }
}