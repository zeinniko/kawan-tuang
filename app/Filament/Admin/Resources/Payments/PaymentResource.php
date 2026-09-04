<?php

namespace App\Filament\Admin\Resources\Payments;

use App\Filament\Admin\Resources\Payments\Pages\CreatePayment;
use App\Filament\Admin\Resources\Payments\Pages\EditPayment;
use App\Filament\Admin\Resources\Payments\Pages\ListPayments;
use App\Filament\Admin\Resources\Payments\Schemas\PaymentForm;
use App\Filament\Admin\Resources\Payments\Tables\PaymentsTable;
use App\Models\Payment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Enums\NavigationGroup;
use App\Filament\Admin\Resources\Payments\Pages\ViewPayment;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    public static function getNavigationIcon(): ?string
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 48" width="24" height="48">
                <line x1="12" y1="0" x2="12" y2="48" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="12" cy="24" r="8" fill="#9ca3af" />
            </svg>
        ');
    }    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::OrderFulfillment;
    protected static ?string $navigationLabel = 'Payments';
    protected static ?int $navigationSort = 3;
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

        // Superadmin: Akses seluruh data pembayaran dari semua toko
        if ($user->isSuperAdmin()) {
            return $query;
        }

        // Admin Cabang: Hanya pembayaran dari tokonya sendiri
        if ($user->isAdmin() && $user->store_id) {
            return $query->where(function (Builder $q) use ($user) {
                $q->where('store_id', $user->store_id)
                  ->orWhereHas('order', function (Builder $orderQuery) use ($user) {
                      $orderQuery->where('store_id', $user->store_id);
                  });
            });
        }

        // Role lain (termasuk Warehouse Staff): Tidak mendapatkan data
        return $query->whereRaw('1 = 0');
    }

    /**
     * Otorisasi Menampilkan Menu & Halaman List Payments
     */
    public static function canViewAny(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // Superadmin & Admin Cabang diizinkan melihat Payments. Staff tidak boleh.
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Otorisasi Menampilkan Detail Record Payment
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

        if ($user->isAdmin() && $user->store_id) {
            $paymentStoreId = $record->store_id ?? $record->order?->store_id;
            return $paymentStoreId === $user->store_id;
        }

        return false;
    }
    public static function form(Schema $schema): Schema
    {
        return PaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
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
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'view'  => ViewPayment::route('/{record}'),
            'edit' => EditPayment::route('/{record}/edit'),
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

    public static function canDelete($record): bool
    {
        return false;
    }
}
