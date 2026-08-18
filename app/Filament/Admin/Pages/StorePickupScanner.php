<?php

namespace App\Filament\Admin\Pages;

use App\Enums\NavigationGroup;
use App\Models\Order;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class StorePickupScanner extends Page
{
    protected string $view = 'filament.admin.pages.store-pickup-scanner';


    public static function getNavigationIcon(): ?string
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 48" width="24" height="48">
                <line x1="12" y1="0" x2="12" y2="48" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="12" cy="24" r="8" fill="#9ca3af" />
            </svg>
        ');
    } 

    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::OrderFulfillment ?? 'Orders & Fulfillment';
    protected static ?string $navigationLabel = 'Store Pickup Scanner';
    protected static ?int $navigationSort = 4;

    public string $pickupCodeInput = '';
    public ?Order $scannedOrder = null;

    public function searchOrder(): void
    {
        $this->scannedOrder = null;

        if (blank($this->pickupCodeInput)) {
            return;
        }

        $input = trim($this->pickupCodeInput);

        // Cari berdasarkan PIN Pickup ATAU Nomor Order
        $order = Order::with(['user', 'items.product', 'store'])
            ->where('fulfillment_type', 'pickup')
            ->where(function ($query) use ($input) {
                $query->where('pickup_code', $input)
                      ->orWhere('order_number', $input);
            })
            ->first();

        if (! $order) {
            Notification::make()
                ->title('Pesanan Tidak Ditemukan')
                ->body("Kode/No. Order '{$input}' tidak ditemukan atau bukan pesanan Pick Up.")
                ->danger()
                ->send();
            return;
        }

        // Validasi jika pesanan sudah diserahkan / selesai
        if ($order->status === 'completed') {
            Notification::make()
                ->title('Pesanan Sudah Selesai')
                ->body("Pesanan #{$order->order_number} sudah pernah diserahkan/selesai sebelumnya.")
                ->warning()
                ->send();
            return;
        }

        // Validasi jika pesanan dibatalkan
        if ($order->status === 'cancelled') {
            Notification::make()
                ->title('Pesanan Dibatalkan')
                ->body("Pesanan #{$order->order_number} telah dibatalkan.")
                ->danger()
                ->send();
            return;
        }

        $this->scannedOrder = $order;
    }

    public function completeHandover(): void
    {
        if (! $this->scannedOrder) {
            return;
        }

        $this->scannedOrder->update([
            'status' => 'completed',
        ]);

        Notification::make()
            ->title('Pesanan Berhasil Diserahkan!')
            ->body("Pesanan #{$this->scannedOrder->order_number} telah ditandai Selesai (Completed).")
            ->success()
            ->send();

        $this->scannedOrder = null;
        $this->pickupCodeInput = '';
    }
}