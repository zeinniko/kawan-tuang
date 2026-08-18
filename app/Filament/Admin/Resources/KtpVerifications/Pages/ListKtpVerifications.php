<?php

namespace App\Filament\Admin\Resources\KtpVerifications\Pages;

use App\Filament\Admin\Resources\KtpVerifications\KtpVerificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKtpVerifications extends ListRecords
{
    protected static string $resource = KtpVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
