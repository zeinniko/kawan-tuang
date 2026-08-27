<?php

namespace App\Filament\Admin\Resources\Vouchers\Schemas;

use App\Models\Voucher;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar Kupon')
                    ->schema([
                        FileUpload::make('banner')
                            ->label('Banner Voucher')
                            ->image()
                            ->directory('vouchers')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->columnSpanFull(),
                        TextInput::make('code')
                            ->label('Kode Voucher')
                            ->required()
                            ->maxLength(50)
                            ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                            ->unique(Voucher::class, 'code', ignoreRecord: true)
                            ->placeholder('Contoh: TEMAN21'),
                        Select::make('discount_type')
                            ->label('Tipe Diskon')
                            ->options([
                                'fixed_amount' => 'Nominal Tetap (Rp)',
                                'percentage' => 'Persentase (%)',
                            ])
                            ->required()
                            ->live(),

                        TextInput::make('discount_value')
                            ->label('Nilai Diskon')
                            ->numeric()
                            ->required()
                            ->prefix(fn($get) => $get('discount_type') === 'percentage' ? '%' : 'Rp')
                            ->placeholder('25000 atau 10'),

                        TextInput::make('min_order_amount')
                            ->label('Minimal Pembelian (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->required(),

                        TextInput::make('max_discount_amount')
                            ->label('Maksimum Potongan Diskon (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->helperText('Batas maksimal diskon khusus untuk tipe persentase.')
                            ->nullable(),

                        TextInput::make('usage_limit')
                            ->label('Batas Total Penggunaan (Quota)')
                            ->numeric()
                            ->placeholder('Kosongkan jika tidak terbatas')
                            ->nullable(),
                    ])->columns(2),

                Section::make('Masa Berlaku Voucher')
                    ->schema([
                        DateTimePicker::make('valid_from')
                            ->label('Berlaku Mulai')
                            ->required()
                            ->default(now()),

                        DateTimePicker::make('valid_until')
                            ->label('Berlaku Sampai')
                            ->required()
                            ->after('valid_from'),
                    ])->columns(2),
            ]);
    }
}
