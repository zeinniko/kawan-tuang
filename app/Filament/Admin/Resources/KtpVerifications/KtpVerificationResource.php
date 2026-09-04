<?php

namespace App\Filament\Admin\Resources\KtpVerifications;

use App\Filament\Admin\Resources\KtpVerifications\Pages\CreateKtpVerification;
use App\Filament\Admin\Resources\KtpVerifications\Pages\EditKtpVerification;
use App\Filament\Admin\Resources\KtpVerifications\Pages\ListKtpVerifications;
use App\Filament\Admin\Resources\KtpVerifications\Schemas\KtpVerificationForm;
use App\Filament\Admin\Resources\KtpVerifications\Tables\KtpVerificationsTable;
use App\Models\KtpVerification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Enums\NavigationGroup;
use UnitEnum;
use Illuminate\Database\Eloquent\Model;

class KtpVerificationResource extends Resource
{
    protected static ?string $model = KtpVerification::class;

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
    protected static ?string $navigationLabel = 'KTP Verifications';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'id';

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
        return KtpVerificationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KtpVerificationsTable::configure($table);
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
            'index' => ListKtpVerifications::route('/'),
            'create' => CreateKtpVerification::route('/create'),
            'edit' => EditKtpVerification::route('/{record}/edit'),
        ];
    }
}
