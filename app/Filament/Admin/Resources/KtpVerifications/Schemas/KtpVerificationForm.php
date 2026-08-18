<?php

namespace App\Filament\Admin\Resources\KtpVerifications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KtpVerificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pemohon & Status Verifikasi')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pengguna')
                            ->relationship('user', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('nik_encrypted')
                            ->label('NIK / Encrypted NIK')
                            ->required()
                            ->maxLength(255),

                        Select::make('status')
                            ->label('Status Verifikasi')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),

                        DateTimePicker::make('verified_at')
                            ->label('Waktu Diverifikasi')
                            ->nullable(),

                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan (Jika Rejected)')
                            ->placeholder('Contoh: Foto KTP buram, NIK tidak terbaca jelas, atau foto selfie tidak cocok.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Dokumen Identitas')
                    ->schema([
                        FileUpload::make('ktp_image_url')
                            ->label('Foto KTP')
                            ->image()
                            ->directory('kyc/ktp')
                            ->required(),

                        FileUpload::make('selfie_image_url')
                            ->label('Foto Selfie Memegang KTP')
                            ->image()
                            ->directory('kyc/selfie')
                            ->required(),
                    ])->columns(2),
            ]);
    }
}