<?php

namespace App\Filament\Admin\Resources\StockMovements;

use App\Enums\NavigationGroup;
use App\Filament\Admin\Resources\StockMovements\Pages\CreateStockMovement;
use App\Filament\Admin\Resources\StockMovements\Pages\EditStockMovement;
use App\Filament\Admin\Resources\StockMovements\Pages\ListStockMovements;
use App\Filament\Admin\Resources\StockMovements\Schemas\StockMovementForm;
use App\Filament\Admin\Resources\StockMovements\Tables\StockMovementsTable;
use App\Models\StockMovement;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;

    public static function getNavigationIcon(): ?string
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 48" width="24" height="48">
                <line x1="12" y1="0" x2="12" y2="48" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="12" cy="24" r="8" fill="#9ca3af" />
            </svg>
        ');
    }

    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::StoreInventory ?? 'Stores & Inventory';
    protected static ?string $navigationLabel = 'Stock Movements';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'reference_id';

    /**
     * Scoping Eloquent Query berdasarkan Role User
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        // Superadmin: Akses seluruh pergerakan stok dari semua toko
        if ($user->isSuperAdmin()) {
            return $query;
        }

        // Admin Cabang & Warehouse Staff: Hanya pergerakan stok di tokonya sendiri
        if (($user->isAdmin() || $user->isWarehouseStaff()) && $user->store_id) {
            return $query->where('store_id', $user->store_id);
        }

        return $query->whereRaw('1 = 0');
    }

    public static function canViewAny(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        // Superadmin, Admin Cabang, dan Warehouse Staff diizinkan mengakses menu ini
        return $user?->isSuperAdmin() || $user?->isAdmin() || $user?->isWarehouseStaff();
    }

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
            return $record->store_id === $user->store_id;
        }

        return false;
    }

    public static function canCreate(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        // Superadmin, Admin, dan Staff dapat mencatat penyesuaian/mutasi stok
        return $user?->isSuperAdmin() || $user?->isAdmin() || $user?->isWarehouseStaff();
    }
    public static function form(Schema $schema): Schema
    {
        return StockMovementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockMovementsTable::configure($table);
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
            'index' => ListStockMovements::route('/'),
            'create' => CreateStockMovement::route('/create'),
            'edit' => EditStockMovement::route('/{record}/edit'),
        ];
    }
        
    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}