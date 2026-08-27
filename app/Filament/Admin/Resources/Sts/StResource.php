<?php

namespace App\Filament\Admin\Resources\Sts;

use App\Filament\Admin\Resources\Sts\Pages\CreateSt;
use App\Filament\Admin\Resources\Sts\Pages\EditSt;
use App\Filament\Admin\Resources\Sts\Pages\ListSts;
use App\Filament\Admin\Resources\Sts\Schemas\StForm;
use App\Filament\Admin\Resources\Sts\Tables\StsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Enums\NavigationGroup;
use App\Filament\Admin\Resources\Sts\Pages\BranchInventoryManager;
use App\Models\Store;
use UnitEnum;

class StResource extends Resource
{
    protected static ?string $model = Store::class;

    public static function getNavigationIcon(): ?string
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 48" width="24" height="48">
                <line x1="12" y1="0" x2="12" y2="48" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="12" cy="24" r="8" fill="#9ca3af" />
            </svg>
        ');
    }
    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::StoreInventory;
    protected static ?string $navigationLabel = 'Stores'; // Mengubah 'Sts' menjadi 'Stores'
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return StForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StsTable::configure($table);
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
            'index' => ListSts::route('/'),
            'create' => CreateSt::route('/create'),
            'edit' => EditSt::route('/{record}/edit'),
            'branch-inventory-manager' => BranchInventoryManager::route('/{record}/inventory'),
        ];
    }
}
