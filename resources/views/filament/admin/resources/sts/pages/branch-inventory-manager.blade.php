<x-filament-panels::page>
    <div class="space-y-6 font-sans">
        
        <!-- HEADER CABANG TOKO & ACTION -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm transition-colors">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200/60 dark:border-rose-900/60 flex items-center justify-center text-rose-700 dark:text-rose-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h14M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $record->name }}</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                {{ $record->store_code }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $record->address }}
                        </p>
                    </div>
                </div>

                <div class="flex-shrink-0">
                    <x-filament::button 
                        wire:click="save" 
                        icon="heroicon-m-check" 
                        color="primary">
                        Simpan Perubahan Stok
                    </x-filament::button>
                </div>
            </div>
        </div>

        <!-- TABEL KELOLA STOK PRODUK -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">
                            <th class="p-4 pl-6">Produk</th>
                            <th class="p-4">SKU</th>
                            <th class="p-4">Tipe Penyimpanan</th>
                            <th class="p-4 w-48">Total Stok</th>
                            <th class="p-4 pr-6 w-48">Stok Dingin (Cold Stock)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                        @forelse($stocksData as $productId => $item)
                            <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-800/40 transition-colors">
                                <!-- Nama Produk -->
                                <td class="p-4 pl-6 font-semibold text-gray-900 dark:text-white">
                                    {{ $item['product_name'] }}
                                </td>

                                <!-- SKU -->
                                <td class="p-4 font-mono text-gray-500 dark:text-gray-400">
                                    {{ $item['sku'] }}
                                </td>

                                <!-- Badge Cold-Ready -->
                                <td class="p-4">
                                    @if($item['is_cold_ready'])
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-cyan-50 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span>
                                            ❄️ Cold Ready
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                            Room Temp
                                        </span>
                                    @endif
                                </td>

                                <!-- Input Total Stok -->
                                <td class="p-4">
                                    <input 
                                        type="number" 
                                        min="0"
                                        wire:model.defer="stocksData.{{ $productId }}.stock" 
                                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs p-2.5 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all outline-none"
                                        placeholder="0"
                                    />
                                </td>

                                <!-- Input Stok Dingin -->
                                <td class="p-4 pr-6">
                                    <input 
                                        type="number" 
                                        min="0"
                                        wire:model.defer="stocksData.{{ $productId }}.cold_stock" 
                                        class="w-full rounded-xl text-xs p-2.5 transition-all outline-none {{ $item['is_cold_ready'] ? 'border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20' : 'border border-gray-200 dark:border-gray-800 bg-gray-100 dark:bg-gray-800/40 text-gray-400 dark:text-gray-600 cursor-not-allowed' }}"
                                        @if(!$item['is_cold_ready']) disabled title="Produk tidak mendukung penyimpanan dingin" @endif
                                        placeholder="0"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 dark:text-gray-500 text-xs">
                                    Belum ada data produk untuk cabang toko ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-filament-panels::page>