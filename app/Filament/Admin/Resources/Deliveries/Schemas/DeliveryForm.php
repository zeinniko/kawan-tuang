<?php

namespace App\Filament\Admin\Resources\Deliveries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeliveryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengiriman & Kurir')
                    ->schema([
                        Select::make('order_id')
                            ->label('Pesanan (Order)')
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('courier_provider')
                            ->label('Penyedia Kurir')
                            ->placeholder('Contoh: GrabExpress, GoSend, Lalamove')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('service_type')
                            ->label('Tipe Layanan')
                            ->placeholder('Contoh: Instant / SameDay')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('waybill_number')
                            ->label('Nomor Resi / Waybill')
                            ->maxLength(100),

                        Select::make('status')
                            ->label('Status Pengiriman')
                            ->options([
                                'pending' => 'Pending (Mencari Kurir)',
                                'allocated' => 'Allocated (Kurir Ditemukan)',
                                'picking_up' => 'Picking Up (Menuju Toko)',
                                'delivering' => 'Delivering (Mengantar Pesanan)',
                                'delivered' => 'Delivered (Sampai Tujuan)',
                                'failed' => 'Failed (Gagal Kirim)',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),

                Section::make('Informasi Driver & Tracking')
                    ->schema([
                        TextInput::make('driver_name')
                            ->label('Nama Driver')
                            ->maxLength(100),

                        TextInput::make('driver_phone')
                            ->label('Telepon Driver')
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('live_tracking_url')
                            ->label('URL Live Tracking')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}