<?php

namespace App\Filament\Admin\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rincian Transaksi Pembayaran')
                    ->schema([
                        Select::make('order_id')
                            ->label('Pesanan (Order)')
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->placeholder('Contoh: QRIS, VA_BCA, GoPay')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('gateway_transaction_id')
                            ->label('ID Transaksi Gateway')
                            ->maxLength(100),

                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid (Lunas)',
                                'expired' => 'Expired (Kadaluwarsa)',
                                'failed' => 'Failed (Gagal)',
                            ])
                            ->default('pending')
                            ->required(),

                        DateTimePicker::make('paid_at')
                            ->label('Waktu Pelunasan')
                            ->nullable(),
                    ])->columns(2),

                Section::make('Raw Response Gateway (Webhook Log)')
                    ->schema([
                        KeyValue::make('raw_response')
                            ->label('Respons Callback JSON')
                            ->keyLabel('Parameter')
                            ->valueLabel('Nilai')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}