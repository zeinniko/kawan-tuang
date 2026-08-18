<?php

namespace App\Filament\Admin\Resources\Orders\Pages;

use App\Filament\Admin\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected string $view = 'filament.admin.resources.orders.pages.view-order';

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $this->record->load([
            'user',
            'store',
            'items.product',
            'payment',
        ]);
    }
}