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
    
    // Property untuk Filter & Search
    public string $search = '';
    public string $filterStorage = 'all'; // all, cold, room
    public string $filterStatus = 'all';  // all, out_of_stock, low_stock, safe

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

        $this->stocksData = [];

        foreach ($products as $product) {
            $stockItem = $existingStocks->get($product->id);

            $this->stocksData[$product->id] = [
                'product_name' => $product->name,
                'sku' => $product->sku,
                'is_cold_ready' => $product->is_cold_ready,
                'stock' => (int) ($stockItem->stock ?? 0),
                'cold_stock' => (int) ($stockItem->cold_stock ?? 0),
            ];
        }
    }

    // Computed Property untuk memfilter data secara dinamis tanpa merusak state input
    public function getFilteredStocksProperty(): array
    {
        return collect($this->stocksData)->filter(function ($item) {
            // Filter Search (Nama atau SKU)
            $matchesSearch = empty($this->search) || 
                str_contains(strtolower($item['product_name']), strtolower($this->search)) ||
                str_contains(strtolower($item['sku']), strtolower($this->search));

            // Filter Tipe Penyimpanan
            $matchesStorage = match ($this->filterStorage) {
                'cold' => $item['is_cold_ready'] === true,
                'room' => $item['is_cold_ready'] === false,
                default => true,
            };

            // Filter Status Stok
            $matchesStatus = match ($this->filterStatus) {
                'out_of_stock' => $item['stock'] <= 0,
                'low_stock' => $item['stock'] > 0 && $item['stock'] <= 5, // Threshold stok kritis <= 5
                'safe' => $item['stock'] > 5,
                default => true,
            };

            return $matchesSearch && $matchesStorage && $matchesStatus;
        })->toArray();
    }

    // Computed Property untuk Ringkasan Statistik KPI
    public function getStatsProperty(): array
    {
        $totalSku = count($this->stocksData);
        $totalItems = collect($this->stocksData)->sum('stock');
        $totalColdItems = collect($this->stocksData)->sum('cold_stock');
        $lowStockCount = collect($this->stocksData)->filter(fn($i) => $i['stock'] <= 5)->count();

        return [
            'total_sku' => $totalSku,
            'total_items' => $totalItems,
            'total_cold_items' => $totalColdItems,
            'low_stock_count' => $lowStockCount,
        ];
    }

    public function save(): void
    {
        // Validasi: Cold Stock tidak boleh melebihi Total Stock
        foreach ($this->stocksData as $productId => $data) {
            $stock = (int) $data['stock'];
            $coldStock = (int) $data['cold_stock'];

            if ($coldStock > $stock) {
                Notification::make()
                    ->title('Gagal Menyimpan!')
                    ->body("Stok dingin untuk SKU '{$data['sku']}' tidak boleh melebihi Total Stok.")
                    ->danger()
                    ->send();
                return;
            }
        }

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
            ->title('Inventaris Berhasil Diperbarui')
            ->body('Seluruh data stok cabang telah berhasil disinkronkan.')
            ->success()
            ->send();
    }
}