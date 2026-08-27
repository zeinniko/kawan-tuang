<?php

namespace App\Filament\Admin\Resources\Vibes\Schemas;

use App\Models\Vibe;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
                            ->placeholder('Contoh: Party Night, Fine Dining')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(50)
                            ->unique(Vibe::class, 'slug', ignoreRecord: true),

                        FileUpload::make('icon_url')
                            ->label('Icon Custom Vibe (SVG / PNG)')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('vibes/icons')
                            ->columnSpanFull(),

                        FileUpload::make('image_url')
                            ->label('Gambar Banner Vibe (Background)')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('vibes/banners')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}