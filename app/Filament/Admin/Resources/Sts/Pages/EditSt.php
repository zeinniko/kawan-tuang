<?php

namespace App\Filament\Admin\Resources\Sts\Pages;

use App\Filament\Admin\Resources\Sts\StResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSt extends EditRecord
{
    protected static string $resource = StResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn() => auth()->user()?->isSuperAdmin() ?? false),
        ];
    }
}
