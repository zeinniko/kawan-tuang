<?php

namespace App\Filament\Admin\Resources\Vibes\Pages;

use App\Filament\Admin\Resources\Vibes\VibeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVibe extends EditRecord
{
    protected static string $resource = VibeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
