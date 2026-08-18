<?php

namespace App\Filament\Admin\Resources\Vibes;

use App\Filament\Admin\Resources\Vibes\Pages\CreateVibe;
use App\Filament\Admin\Resources\Vibes\Pages\EditVibe;
use App\Filament\Admin\Resources\Vibes\Pages\ListVibes;
use App\Filament\Admin\Resources\Vibes\Schemas\VibeForm;
use App\Filament\Admin\Resources\Vibes\Tables\VibesTable;
use App\Models\Vibe;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Enums\NavigationGroup;
use UnitEnum;

class VibeResource extends Resource
{
    protected static ?string $model = Vibe::class;

    public static function getNavigationIcon(): ?string
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 48" width="24" height="48">
                <line x1="12" y1="0" x2="12" y2="48" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="12" cy="24" r="8" fill="#9ca3af" />
            </svg>
        ');
    }    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::Catalog;
    protected static ?string $navigationLabel = 'Vibes';
    protected static ?int $navigationSort = 4;
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return VibeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VibesTable::configure($table);
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
            'index' => ListVibes::route('/'),
            'create' => CreateVibe::route('/create'),
            'edit' => EditVibe::route('/{record}/edit'),
        ];
    }
}
