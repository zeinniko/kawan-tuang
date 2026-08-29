<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar Produk')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('sku')
                            ->label('SKU (Stock Keeping Unit)')
                            ->required()
                            ->maxLength(50)
                            ->unique(Product::class, 'sku', ignoreRecord: true)
                            ->placeholder('Contoh: JW-BLK-750'),

                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('brand_id')
                            ->label('Brand')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('vibes')
                            ->label('Vibes / Occasion')
                            ->relationship('vibes', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),

                        Textarea::make('description')
                            ->label('Deskripsi Produk')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Spesifikasi & Harga')
                    ->schema([
                        TextInput::make('abv')
                            ->label('ABV (%)')
                            ->numeric()
                            ->step('0.1')
                            ->required()
                            ->suffix('%')
                            ->placeholder('40.0'),

                        TextInput::make('volume_ml')
                            ->label('Volume (ml)')
                            ->numeric()
                            ->required()
                            ->suffix('ml')
                            ->placeholder('750'),

                        TextInput::make('price')
                            ->label('Harga Utama (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('strike_price')
                            ->label('Harga Coret / Diskon (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->nullable(),

                        TextInput::make('stock')
                            ->label('Total Stok Global')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->columnSpanFull(),

                        Toggle::make('is_cold_ready')
                            ->label('Siap Dingin (Cold-Ready)')
                            ->default(true),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ])->columns(2),

                Section::make('Galeri Foto Produk')
                    ->schema([
                        Repeater::make('images')
                            ->relationship('images')
                            ->schema([
                                FileUpload::make('image_url')
                                    ->label('Foto Produk')
                                    ->image()
                                    ->directory('products')
                                    ->imageEditor()
                                    ->required()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->columnSpanFull(),

                                Toggle::make('is_primary')
                                    ->label('Set Sebagai Foto Utama')
                                    ->default(false),

                                TextInput::make('display_order')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->reorderable('display_order')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}