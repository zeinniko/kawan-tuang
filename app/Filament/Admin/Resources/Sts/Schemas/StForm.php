<?php

namespace App\Filament\Admin\Resources\Sts\Schemas;

use App\Models\Store;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama Cabang')
                    ->schema([
                        TextInput::make('store_code')
                            ->label('Kode Toko / Cabang')
                            ->required()
                            ->maxLength(20)
                            ->unique(Store::class, 'store_code', ignoreRecord: true)
                            ->placeholder('Contoh: TT-JKT-01'),

                        TextInput::make('name')
                            ->label('Nama Cabang Toko')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Contoh: Teman Tuang - Kemang'),

                        TextInput::make('phone_number')
                            ->label('Nomor Telepon Operasional')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->placeholder('081234567890'),

                        Textarea::make('address')
                            ->label('Alamat Lengkap Cabang')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(3),

                    Section::make('Koordinat Lokasi GPS')
                    ->schema([
                        ViewField::make('location_picker')
                            ->label('Penentuan Lokasi GPS & Koordinat')
                            ->view('filament.forms.components.location-picker')
                            ->columnSpanFull(),

                            Hidden::make('latitude')
            ->default('-2.99090000')
            ->required(),

        Hidden::make('longitude')
            ->default('104.75650000')
            ->required(),
                    ]),

                Section::make('Jam Operasional & Status Cabang')
                    ->schema([
                        TimePicker::make('open_time')
                            ->label('Jam Buka')
                            ->required()
                            ->seconds(false)
                            ->default('10:00'),

                        TimePicker::make('close_time')
                            ->label('Jam Tutup')
                            ->required()
                            ->seconds(false)
                            ->default('23:00'),

                        Toggle::make('is_pickup_active')
                            ->label('Layanan Pick Up Aktif')
                            ->helperText('Pelanggan dapat mengambil pesanan langsung di lokasi ini.')
                            ->default(true),

                        Toggle::make('is_active')
                            ->label('Status Cabang Aktif')
                            ->helperText('Cabang aktif untuk pemesanan & pengiriman.')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}