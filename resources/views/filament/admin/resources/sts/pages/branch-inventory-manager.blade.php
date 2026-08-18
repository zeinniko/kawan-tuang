<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $record->name }} ({{ $record->store_code }})</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->address }}</p>
            </div>
            <x-filament::button wire:click="save" icon="heroicon-m-check">
                Simpan Perubahan Stok
            </x-filament::button>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                        <th class="p-4">Produk</th>
                        <th class="p-4">SKU</th>
                        <th class="p-4">Cold-Ready</th>
                        <th class="p-4 w-44">Total Stok</th>
                        <th class="p-4 w-44">Stok Dingin (Cold Stock)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    @foreach($stocksData as $productId => $item)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                            <td class="p-4 font-medium text-gray-900 dark:text-white">
                                {{ $item['product_name'] }}
                            </td>
                            <td class="p-4 text-gray-500 dark:text-gray-400">
                                {{ $item['sku'] }}
                            </td>
                            <td class="p-4">
                                @if($item['is_cold_ready'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                        ❄️ Cold Ready
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        Room Temp
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <input 
                                    type="number" 
                                    min="0"
                                    wire:model.defer="stocksData.{{ $productId }}.stock" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-sm focus:border-primary-500 focus:ring-primary-500"
                                />
                            </td>
                            <td class="p-4">
                                <input 
                                    type="number" 
                                    min="0"
                                    wire:model.defer="stocksData.{{ $productId }}.cold_stock" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-sm focus:border-primary-500 focus:ring-primary-500"
                                    @if(!$item['is_cold_ready']) disabled title="Produk tidak mendukung cold stock" @endif
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>