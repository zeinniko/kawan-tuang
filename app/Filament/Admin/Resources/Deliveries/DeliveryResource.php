<?php

namespace App\Filament\Admin\Resources\Deliveries;

use App\Filament\Admin\Resources\Deliveries\Pages\CreateDelivery;
use App\Filament\Admin\Resources\Deliveries\Pages\EditDelivery;
use App\Filament\Admin\Resources\Deliveries\Pages\ListDeliveries;
use App\Filament\Admin\Resources\Deliveries\Schemas\DeliveryForm;
use App\Filament\Admin\Resources\Deliveries\Tables\DeliveriesTable;
use App\Models\Delivery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Enums\NavigationGroup;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    public static function getNavigationIcon(): ?string
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 48" width="24" height="48">
                <line x1="12" y1="0" x2="12" y2="48" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="12" cy="24" r="8" fill="#9ca3af" />
            </svg>
        ');
    }
    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::OrderFulfillment;
    protected static ?string $navigationLabel = 'Deliveries';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'id';
    /**
     * Scope Eloquent Query berdasarkan Role User
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        // Superadmin: Akses seluruh data pengiriman dari semua toko
        if ($user->isSuperAdmin()) {
            return $query;
        }

        // Admin Cabang & Warehouse Staff: Hanya pengiriman dari tokonya sendiri
        if (($user->isAdmin() || $user->isWarehouseStaff()) && $user->store_id) {
            return $query->where(function (Builder $q) use ($user) {
                $q->where('store_id', $user->store_id)
                    ->orWhereHas('order', function (Builder $orderQuery) use ($user) {
                        $orderQuery->where('store_id', $user->store_id);
                    });
            });
        }

        return $query->whereRaw('1 = 0');
    }

    /**
     * Otorisasi Menampilkan Menu & Halaman List Deliveries
     */
    public static function canViewAny(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // Superadmin, Admin Cabang, dan Warehouse Staff diizinkan melihat Deliveries
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isWarehouseStaff();
    }

    /**
     * Otorisasi Menampilkan Detail Record Delivery
     */
    public static function canView(Model $record): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        if (($user->isAdmin() || $user->isWarehouseStaff()) && $user->store_id) {
            $deliveryStoreId = $record->store_id ?? $record->order?->store_id;
            return $deliveryStoreId === $user->store_id;
        }

        return false;
    }

    /**
     * Otorisasi Mengedit Delivery (misal: memperbarui nomor resi / status pengiriman)
     */
    public static function canEdit(Model $record): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        if (($user->isAdmin() || $user->isWarehouseStaff()) && $user->store_id) {
            $deliveryStoreId = $record->store_id ?? $record->order?->store_id;
            return $deliveryStoreId === $user->store_id;
        }

        return false;
    }
    public static function form(Schema $schema): Schema
    {
        return DeliveryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeliveries::route('/'),
            'create' => CreateDelivery::route('/create'),
            'edit' => EditDelivery::route('/{record}/edit'),
        ];
    }
    public static function canDelete(Model $record): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        // Hanya Superadmin yang diizinkan menghapus data pengiriman
        return $user?->isSuperAdmin() ?? false;
    }
}
