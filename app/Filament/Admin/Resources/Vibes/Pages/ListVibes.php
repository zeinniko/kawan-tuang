<?php

namespace App\Filament\Admin\Resources\Vibes\Pages;

use App\Filament\Admin\Resources\Vibes\VibeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVibes extends ListRecords
{
    protected static string $resource = VibeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
