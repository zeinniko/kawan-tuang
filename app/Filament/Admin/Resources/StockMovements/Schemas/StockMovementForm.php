<?php

namespace App\Filament\Admin\Resources\StockMovements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pencatatan Pergerakan Stok (Audit Log)')
                    ->schema([
                        Select::make('store_id')
                            ->label('Cabang Toko')
                            ->relationship('store', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('product_id')
                            ->label('Produk')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('type')
                            ->label('Tipe Mutasi Stok')
                            ->options([
                                'restock' => 'Restock (+ Penambahan Stok Supplier)',
                                'order_sale' => 'Order Sale (- Penjualan)',
                                'damaged' => 'Damaged (- Barang Rusak / Pecah)',
                                'expired' => 'Expired (- Kedaluwarsa)',
                                'transfer' => 'Transfer (Mutasi Antar Cabang)',
                            ])
                            ->required(),

                        TextInput::make('change_amount')
                            ->label('Jumlah Perubahan Stok')
                            ->helperText('Gunakan angka positif untuk penambahan (misal: 20) dan negatif untuk pengurangan (misal: -5).')
                            ->numeric()
                            ->required()
                            ->placeholder('20 atau -5'),

                        TextInput::make('reference_id')
                            ->label('Kode Referensi / No. Nota / Order ID')
                            ->placeholder('Contoh: PO-2026-08-001 / ORD-9921')
                            ->maxLength(100)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}