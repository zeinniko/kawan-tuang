<?php

namespace App\Filament\Admin\Resources\Orders\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Jobs\CreateBiteshipOrderJob;
use App\Models\Order;
use Filament\Notifications\Notification;

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
                    ->icon('heroicon-o-play')
                    ->color('info')
                    ->visible(fn (Order $record) => $record->status === Order::STATUS_PAID)
                    ->requiresConfirmation()
                    ->modalHeading('Proses Pesanan?')
                    ->modalDescription(fn (Order $record) => $record->fulfillment_type === 'delivery'
                        ? 'Pesanan Delivery akan didaftarkan pengirimannya ke Biteship (jika toko sedang buka).'
                        : 'Pesanan Pick Up akan diubah statusnya menjadi Diproses.')
                    ->action(function (Order $record): void {
                        if ($record->fulfillment_type === 'delivery') {
                            // Dispatch job panggil kurir Biteship
                            CreateBiteshipOrderJob::dispatch($record);

                            Notification::make()
                                ->title('Permintaan Delivery Diproses')
                                ->body("Pesanan #{$record->order_number} sedang didaftarkan ke Biteship.")
                                ->info()
                                ->send();
                        } else {
                            // Update manual untuk Pick Up
                            $record->update([
                                'status' => Order::STATUS_PROCESSING,
                            ]);

                            Notification::make()
                                ->title('Pesanan Pick Up Diproses')
                                ->body("Status pesanan #{$record->order_number} berhasil diubah ke Diproses.")
                                ->success()
                                ->send();
                        }
                    }),

                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}