<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
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
                            ->maxLength(50)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(50)
                            ->unique(Category::class, 'slug', ignoreRecord: true),

                        FileUpload::make('icon_url')
                            ->label('Icon Kategori')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('categories')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}