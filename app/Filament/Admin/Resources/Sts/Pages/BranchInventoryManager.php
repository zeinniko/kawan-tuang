<?php

namespace App\Filament\Admin\Resources\Sts\Pages;

use App\Filament\Admin\Resources\Sts\StResource;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreStock;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class BranchInventoryManager extends Page
{
    protected static string $resource = StResource::class;

    protected string $view = 'filament.admin.resources.sts.pages.branch-inventory-manager';

    public Store $record;

    public array $stocksData = [];

    public function mount(Store $record): void
    {
        $this->record = $record;
        $this->loadStocks();
    }

    public function loadStocks(): void
    {
        $products = Product::where('is_active', true)->get();
        $existingStocks = StoreStock::where('store_id', $this->record->id)
            ->get()
            ->keyBy('product_id');

        foreach ($products as $product) {
            $stockItem = $existingStocks->get($product->id);

            $this->stocksData[$product->id] = [
                'product_name' => $product->name,
                'sku' => $product->sku,
                'is_cold_ready' => $product->is_cold_ready,
                'stock' => $stockItem->stock ?? 0,
                'cold_stock' => $stockItem->cold_stock ?? 0,
            ];
        }
    }

    public function save(): void
    {
        foreach ($this->stocksData as $productId => $data) {
            StoreStock::updateOrCreate(
                [
                    'store_id' => $this->record->id,
                    'product_id' => $productId,
                ],
                [
                    'stock' => (int) $data['stock'],
                    'cold_stock' => (int) $data['cold_stock'],
                ]
            );
        }

        Notification::make()
            ->title('Stok Cabang Berhasil Diperbarui')
            ->success()
            ->send();
    }
}