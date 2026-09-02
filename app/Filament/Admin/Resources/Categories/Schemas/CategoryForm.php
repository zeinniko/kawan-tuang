<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->schema([
                        Select::make('parent_id')
                            ->label('Parent Category')
                            ->placeholder('Pilih jika ini adalah sub-kategori')
                            ->relationship(
                                name: 'parent',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query, ?Category $record) => $query
                                    ->whereNull('parent_id')
                                    ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(50),


                        FileUpload::make('icon_url')
                            ->label('Icon Kategori')
                            ->image()
                            ->disk('s3')
                            ->visibility('public')
                            ->directory('categories')
                            ->helperText(function ($get) {
                                $name = $get('name');
                                $query = urlencode($name ?? '');
                                
                                $url = $query 
                                    ? "https://www.flaticon.com/search?word={$query}" 
                                    : "https://www.flaticon.com";

                                $labelText = $name 
                                    ? "Cari icon untuk <strong>\"" . e($name) . "\"</strong> di Flaticon ↗" 
                                    : "Cari icon di Flaticon ↗";

                                return new HtmlString(
                                    '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">' 
                                    . $labelText . 
                                    '</a>'
                                );
                            })
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}