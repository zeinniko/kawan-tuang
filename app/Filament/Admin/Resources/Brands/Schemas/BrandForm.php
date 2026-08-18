<?php

namespace App\Filament\Admin\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Brand')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Brand')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Contoh: Jack Daniel\'s'),

                        TextInput::make('country_origin')
                            ->label('Negara Asal')
                            ->maxLength(50)
                            ->placeholder('Contoh: United States'),

                        FileUpload::make('logo_url')
                            ->label('Logo Brand')
                            ->image()
                            ->directory('brands')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}