<?php

namespace App\Filament\Admin\Resources\Sts\Tables;

use App\Filament\Admin\Resources\Sts\StResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use App\Models\User;

class StsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('store_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('name')
                    ->label('Nama Cabang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn($record) => $record->phone_number),

                TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(40)
                    ->tooltip(fn($record) => $record->address),

                TextColumn::make('open_time')
                    ->label('Jam Buka')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('close_time')
                    ->label('Jam Tutup')
                    ->time('H:i')
                    ->sortable(),

                IconColumn::make('is_pickup_active')
                    ->label('Pick Up')
                    ->boolean()
                    ->trueIcon('heroicon-o-shopping-bag')
                    ->falseIcon('heroicon-o-x-mark'),

                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),

                TernaryFilter::make('is_pickup_active')
                    ->label('Status Pick Up'),
            ])
            ->actions([
                // Kelola Stok: Superadmin, Admin Cabang, & Warehouse Staff
                Action::make('manage_inventory')
                    ->label('Kelola Stok')
                    ->icon('heroicon-o-cube')
                    ->color('warning')
                    ->url(fn ($record) => StResource::getUrl('branch-inventory-manager', ['record' => $record]))
                    ->visible(function ($record) {
                        /** @var User|null $user */
                        $user = auth()->user();

                        if (! $user) return false;
                        if ($user->isSuperAdmin()) return true;

                        return ($user->isAdmin() || $user->isWarehouseStaff()) && $user->store_id === $record->id;
                    }),

                // Edit Toko: Superadmin & Admin Cabang (Staff disembunyikan)
                EditAction::make()
                    ->visible(function ($record) {
                        /** @var User|null $user */
                        $user = auth()->user();

                        if (! $user) return false;
                        if ($user->isSuperAdmin()) return true;

                        return $user->isAdmin() && $user->store_id === $record->id;
                    }),

                // Hapus Toko: Hanya Superadmin
                DeleteAction::make()
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
