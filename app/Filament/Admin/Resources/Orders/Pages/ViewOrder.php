<?php

namespace App\Filament\Admin\Resources\Orders\Pages;

use App\Filament\Admin\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Notifications\Notification;
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

    public function updateStatus(string $newStatus): void
    {
        $allowedStatuses = [
            Order::STATUS_PROCESSING,
            Order::STATUS_READY_FOR_PICKUP,
            Order::STATUS_COMPLETED,
        ];

        if (! in_array($newStatus, $allowedStatuses)) {
            Notification::make()
                ->title('Status tidak valid')
                ->danger()
                ->send();
            return;
        }

        $this->record->update([
            'status' => $newStatus,
        ]);

        $statusLabels = [
            Order::STATUS_PROCESSING       => 'Diproses Penjual',
            Order::STATUS_READY_FOR_PICKUP => 'Siap Pickup',
            Order::STATUS_COMPLETED        => 'Selesai / Sudah Diambil',
        ];

        Notification::make()
            ->title('Status Pesanan Diperbarui')
            ->body('Status berhasil diubah menjadi: ' . ($statusLabels[$newStatus] ?? $newStatus))
            ->success()
            ->send();
    }
}