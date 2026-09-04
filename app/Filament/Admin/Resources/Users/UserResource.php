<?php

namespace App\Filament\Admin\Resources\Users;

use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Pages\ViewUser;
use App\Filament\Admin\Resources\Users\Schemas\UserForm;
use App\Filament\Admin\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Enums\NavigationGroup;
use UnitEnum;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getNavigationIcon(): ?string
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 48" width="24" height="48">
                <line x1="12" y1="0" x2="12" y2="48" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="12" cy="24" r="8" fill="#9ca3af" />
            </svg>
        ');
    }
    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::Compliance;
    protected static ?string $navigationLabel = 'Users';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'email';
    /**
     * Menampilkan menu di sidebar & izin akses list page
     */
    public static function canViewAny(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $user?->isSuperAdmin() ?? false;
    }

    /**
     * Menampilkan detail record produk
     */
    public static function canView(Model $record): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $user?->isSuperAdmin() ?? false;
    }

    /**
     * Izin membuat produk baru
     */
    public static function canCreate(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $user?->isSuperAdmin() ?? false;
    }

    /**
     * Izin mengedit produk
     */
    public static function canEdit(Model $record): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $user?->isSuperAdmin() ?? false;
    }

    /**
     * Izin menghapus produk
     */
    public static function canDelete(Model $record): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $user?->isSuperAdmin() ?? false;
    }
    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view'   => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
