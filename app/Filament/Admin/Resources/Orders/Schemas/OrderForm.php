<?php

namespace App\Filament\Admin\Resources\Orders\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama Pesanan')
                    ->schema([
                        TextInput::make('order_number')
                            ->label('Nomor Pesanan')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('Contoh: ORD-20260815-001'),

                        Select::make('user_id')
                            ->label('Pelanggan')
                            ->relationship('user', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('store_id')
                            ->label('Cabang Toko')
                            ->relationship('store', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('voucher_id')
                            ->label('Voucher Diskon')
                            ->relationship('voucher', 'code')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Select::make('fulfillment_type')
                            ->label('Tipe Fulfillment')
                            ->options([
                                'delivery' => 'Instant Delivery (Kurir)',
                                'pickup' => 'Pick Up in Store (Ambil di Tempat)',
                            ])
                            ->required()
                            ->live(),

                        Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'pending_payment' => 'Pending Payment',
                                'paid' => 'Paid',
                                'processing' => 'Processing (Mempersiapkan Pesanan)',
                                'delivering' => 'Delivering / Ready for Pickup',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])->columns(3),

                Section::make('Informasi Pick Up in Store')
                    ->schema([
                        TextInput::make('pickup_code')
                            ->label('Kode / PIN Pickup')
                            ->maxLength(10)
                            ->placeholder('Contoh: 88921'),

                        TextInput::make('pickup_qr_url')
                            ->label('URL QR Code Pickup')
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('fulfillment_type') === 'pickup'),

                Section::make('Rincian Pembayaran')
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal Produk')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('discount_amount')
                            ->label('Potongan Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        TextInput::make('delivery_fee')
                            ->label('Ongkos Pengiriman')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        TextInput::make('admin_fee')
                            ->label('Biaya Layanan / Admin')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        TextInput::make('total_amount')
                            ->label('Total Pembayaran')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                    ])->columns(3),

                Section::make('Snapshot Alamat Pengiriman')
                    ->schema([
                        KeyValue::make('address_snapshot')
                            ->label('Detail Alamat Penerima')
                            ->keyLabel('Atribut')
                            ->valueLabel('Nilai')
                            ->columnSpanFull(),
                    ]),

                Section::make('Item Pesanan')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('product_name_snapshot')
                                    ->label('Nama Produk (Snapshot)')
                                    ->required(),

                                TextInput::make('unit_price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('subtotal_price')
                                    ->label('Subtotal Item')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}