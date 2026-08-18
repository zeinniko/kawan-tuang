<?php

namespace App\Filament\Admin\Resources\Sts\Pages;

use App\Filament\Admin\Resources\Sts\StResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSts extends ListRecords
{
    protected static string $resource = StResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
