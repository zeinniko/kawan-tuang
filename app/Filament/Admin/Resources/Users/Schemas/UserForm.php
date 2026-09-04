<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun & Profil')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->maxLength(150)
                            ->unique(User::class, 'email', ignoreRecord: true),

                        TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->unique(User::class, 'phone_number', ignoreRecord: true),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Hak Akses & Penugasan Cabang')
                    ->schema([
                        Select::make('role')
                            ->label('Role Akses')
                            ->options([
                                'admin'           => 'Admin Panel',
                                'warehouse_staff' => 'Warehouse Staff',
                            ])
                            ->default('admin')
                            ->live()
                            ->required(),

                        // SELECT PENUGASAN CABANG (STORE)
                        Select::make('store_id')
                            ->label('Cabang / Store Penugasan')
                            ->relationship('store', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Wajib dipilih jika pengguna ini bertindak sebagai Admin Cabang.')
                            ->required(fn($get) => $get('role') === 'admin'),
                    ])->columns(2),

                Section::make('Verifikasi Usia (KYC 21+)')
                    ->schema([
                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->maxDate(now()->subYears(21))
                            ->validationMessages([
                                'max_date' => 'Pengguna harus berusia minimal 21 tahun untuk mendaftar.',
                            ]),

                        Toggle::make('is_age_verified')
                            ->label('Status Terverifikasi (KYC 21+)')
                            ->helperText('Aktifkan jika dokumen KTP pengguna telah disetujui.')
                            ->default(false),
                    ])->columns(2),
            ]);
    }
}
