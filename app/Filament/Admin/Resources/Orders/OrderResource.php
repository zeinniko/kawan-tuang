<?php

namespace App\Filament\Admin\Resources\Orders;

use App\Filament\Admin\Resources\Orders\Pages\CreateOrder;
use App\Filament\Admin\Resources\Orders\Pages\EditOrder;
use App\Filament\Admin\Resources\Orders\Pages\ListOrders;
use App\Filament\Admin\Resources\Orders\Schemas\OrderForm;
use App\Filament\Admin\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Table;
use App\Enums\NavigationGroup;
use App\Filament\Admin\Resources\Orders\Pages\ViewOrder;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    public static function getNavigationIcon(): ?string
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 48" width="24" height="48">
                <line x1="12" y1="0" x2="12" y2="48" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="12" cy="24" r="8" fill="#9ca3af" />
            </svg>
        ');
    }

    protected static ?string $recordTitleAttribute = 'order_number';
    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::OrderFulfillment;
    protected static ?string $navigationLabel = 'Orders';

    public static function getRecordTitle(?Model $record): string
    {
        return $record ? "Pesanan #{$record->order_number}" : 'Detail Pesanan';
    }

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

        // Superadmin: Akses seluruh data dari semua toko
        if ($user->isSuperAdmin()) {
            return $query;
        }

        // Admin Cabang: Hanya data order dari tokonya sendiri
        if ($user->isAdmin()) {
            return $query->where('store_id', $user->store_id);
        }

        // Role lain (misal: Warehouse Staff): Tidak mendapatkan data
        return $query->whereRaw('1 = 0');
    }

    /**
     * Otorisasi Menampilkan Menu & Halaman List Order
     */
    public static function canViewAny(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // Superadmin & Admin Cabang boleh melihat menu Orders. Staff tidak boleh.
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Otorisasi Menampilkan Detail Record Order
     */
    public static function canView(Model $record): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // Superadmin: Boleh lihat semua detail order
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin Cabang: Boleh lihat jika order berasal dari tokonya
        if ($user->isAdmin()) {
            return $record->store_id === $user->store_id;
        }

        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
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
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'view'  => ViewOrder::route('/{record}'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        // Hanya Superadmin yang diizinkan menghapus data order (jika diperlukan)
        return $user?->isSuperAdmin() ?? false;
    }
}
