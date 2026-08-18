<?php

namespace App\Filament\Admin\Resources\ProductReviews\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Ulasan Produk')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pengguna')
                            ->relationship('user', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('product_id')
                            ->label('Produk')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('order_id')
                            ->label('Pesanan (Order ID)')
                            ->relationship('order', 'id')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Select::make('rating')
                            ->label('Rating Bintang')
                            ->options([
                                5 => '⭐⭐⭐⭐⭐ (5 Stars)',
                                4 => '⭐⭐⭐⭐ (4 Stars)',
                                3 => '⭐⭐⭐ (3 Stars)',
                                2 => '⭐⭐ (2 Stars)',
                                1 => '⭐ (1 Star)',
                            ])
                            ->required(),

                        Textarea::make('review_text')
                            ->label('Isi Ulasan')
                            ->rows(4)
                            ->columnSpanFull(),

                        FileUpload::make('photo_url')
                            ->label('Foto Ulasan (Opsional)')
                            ->image()
                            ->directory('reviews')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}