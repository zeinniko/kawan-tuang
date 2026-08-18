<x-filament-panels::page>
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Input Form Scanner -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Verifikasi Kode Pickup Pelanggan</h2>
            
            <form wire:submit.prevent="searchOrder" class="flex gap-4">
                <input 
                    type="text" 
                    wire:model.defer="pickupCodeInput" 
                    placeholder="Masukkan atau Scan PIN Pickup (misal: 88921)" 
                    class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-lg p-3 focus:border-primary-500 focus:ring-primary-500"
                    autofocus
                />
                <x-filament::button type="submit" icon="heroicon-m-magnifying-glass" size="lg">
                    Cari Order
                </x-filament::button>
            </form>
        </div>

        <!-- Detail Hasil Scan -->
        @if($scannedOrder)
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 space-y-6">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase">No. Order</span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $scannedOrder->order_number }}</h3>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Status</span>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-info-100 text-info-800">
                                {{ ucfirst($scannedOrder->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Nama Pelanggan:</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $scannedOrder->user?->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Cabang Toko:</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $scannedOrder->store?->name }}</p>
                    </div>
                </div>

                <!-- Daftar Produk yang Diambil -->
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-2">Item yang Harus Disiapkan:</h4>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 border rounded-lg overflow-hidden">
                        @foreach($scannedOrder->items as $item)
                            <div class="p-3 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $item->product_name_snapshot }}</p>
                                    <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}x @ Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                </div>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->subtotal_price, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Serahkan Barang -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <x-filament::button 
                        wire:click="completeHandover" 
                        color="success" 
                        size="xl" 
                        icon="heroicon-m-check-circle"
                    >
                        Handover & Selesaikan Pesanan
                    </x-filament::button>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>