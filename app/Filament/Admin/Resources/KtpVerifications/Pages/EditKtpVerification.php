<?php

namespace App\Filament\Admin\Resources\KtpVerifications\Pages;

use App\Filament\Admin\Resources\KtpVerifications\KtpVerificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKtpVerification extends EditRecord
{
    protected static string $resource = KtpVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
