<?php

namespace App\Filament\Admin\Resources\Vibes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VibeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Vibe / Occasion')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Vibe')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('Contoh: Party Night, Fine Dining'),

                        TextInput::make('icon_emoji')
                            ->label('Emoji Icon')
                            ->maxLength(10)
                            ->placeholder('Contoh: 🎉, 🍷, 🍻'),
                    ])->columns(2),
            ]);
    }
}