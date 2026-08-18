<?php

namespace App\Filament\Admin\Resources\Payments\Pages;

use App\Filament\Admin\Resources\Payments\PaymentResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected string $view = 'filament.admin.resources.payments.pages.view-payment';

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $this->record->load([
            'order.user',
            'order.store',
            'order.items',
        ]);
    }
}