<x-filament-panels::page>
    <div class="space-y-6 font-sans">
        
        <!-- HEADER CABANG TOKO & SUMMARY ACTION -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm transition-colors">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h14M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $record->name }}</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20">
                                {{ $record->store_code }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $record->address }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <x-filament::button 
                        wire:click="loadStocks" 
                        color="gray"
                        icon="heroicon-m-arrow-path">
                        Reset
                    </x-filament::button>

                    <x-filament::button 
                        wire:click="save" 
                        icon="heroicon-m-check-badge" 
                        color="warning">
                        Simpan Perubahan Stok
                    </x-filament::button>
                </div>
            </div>
        </div>

        <!-- KPI STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total SKU -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block">Total Terdaftar</span>
                    <span class="text-xl font-extrabold text-gray-900 dark:text-white">{{ number_format($this->stats['total_sku']) }} <span class="text-xs font-normal text-gray-400">SKU</span></span>
                </div>
            </div>

            <!-- Total Unit Fisik -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block">Total Unit Fisik</span>
                    <span class="text-xl font-extrabold text-gray-900 dark:text-white">{{ number_format($this->stats['total_items']) }} <span class="text-xs font-normal text-gray-400">Pcs</span></span>
                </div>
            </div>

            <!-- Total Stok Cold -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m9-9H3m15.364 6.364l-12.728-12.728m12.728 0L6.364 18.364"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block">Stok Dingin Ready</span>
                    <span class="text-xl font-extrabold text-gray-900 dark:text-white">{{ number_format($this->stats['total_cold_items']) }} <span class="text-xs font-normal text-gray-400">Pcs</span></span>
                </div>
            </div>

            <!-- Warning Low Stock -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border {{ $this->stats['low_stock_count'] > 0 ? 'border-rose-300 dark:border-rose-900/80 bg-rose-50/20' : 'border-gray-200 dark:border-gray-800' }} p-4 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl {{ $this->stats['low_stock_count'] > 0 ? 'bg-rose-500/20 text-rose-600 dark:text-rose-400 animate-pulse' : 'bg-gray-100 dark:bg-gray-800 text-gray-400' }} flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block">Stok Kritis (≤ 5)</span>
                    <span class="text-xl font-extrabold {{ $this->stats['low_stock_count'] > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-900 dark:text-white' }}">{{ number_format($this->stats['low_stock_count']) }} <span class="text-xs font-normal text-gray-400">SKU</span></span>
                </div>
            </div>
        </div>

        <!-- FILTER & SEARCH CONTROL BAR -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm flex flex-col md:flex-row items-center gap-3">
            <!-- Search Box -->
            <div class="relative flex-1 w-full">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari Produk atau SKU..." 
                    class="w-full text-xs pl-9 pr-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl outline-none focus:ring-2 focus:ring-amber-500 text-gray-900 dark:text-white"
                />
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Filter Penyimpanan -->
            <div class="w-full md:w-auto flex items-center gap-2">
                <select wire:model.live="filterStorage" class="w-full md:w-auto text-xs bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="all">Semua Tipe Simpan</option>
                    <option value="cold">❄️ Cold Ready</option>
                    <option value="room">🌡️ Room Temp</option>
                </select>

                <!-- Filter Status Stok -->
                <select wire:model.live="filterStatus" class="w-full md:w-auto text-xs bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="all">Semua Status Stok</option>
                    <option value="out_of_stock">🔴 Stok Habis (0)</option>
                    <option value="low_stock">🟡 Stok Kritis (1-5)</option>
                    <option value="safe">🟢 Stok Aman (> 5)</option>
                </select>
            </div>
        </div>

        <!-- TABEL INVENTARIS PRODUK -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/60 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">
                            <th class="p-4 pl-6">Produk & Info SKU</th>
                            <th class="p-4">Tipe Penyimpanan</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 w-44">Total Stok</th>
                            <th class="p-4 pr-6 w-44">Stok Dingin (Cold)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800/70 text-gray-700 dark:text-gray-300">
                        @forelse($this->filteredStocks as $productId => $item)
                            @php
                                $stockVal = (int) $item['stock'];
                                $coldVal = (int) $item['cold_stock'];
                            @endphp
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition-colors">
                                <!-- Info Produk & SKU -->
                                <td class="p-4 pl-6">
                                    <div class="font-bold text-gray-900 dark:text-white text-sm">
                                        {{ $item['product_name'] }}
                                    </div>
                                    <div class="text-[11px] font-mono text-gray-400 mt-0.5">
                                        SKU: {{ $item['sku'] }}
                                    </div>
                                </td>

                                <!-- Badge Cold-Ready -->
                                <td class="p-4">
                                    @if($item['is_cold_ready'])
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-cyan-50 dark:bg-cyan-950/50 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span>
                                            ❄️ Cold Ready
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                            🌡️ Room Temp
                                        </span>
                                    @endif
                                </td>

                                <!-- Status Indikator Stok -->
                                <td class="p-4">
                                    @if($stockVal <= 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                                            Habis
                                        </span>
                                    @elseif($stockVal <= 5)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900">
                                            Menipis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900">
                                            Aman
                                        </span>
                                    @endif
                                </td>

                                <!-- Input Total Stok -->
                                <td class="p-4">
                                    <input 
                                        type="number" 
                                        min="0"
                                        wire:model.live.debounce.300ms="stocksData.{{ $productId }}.stock" 
                                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs p-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none font-bold"
                                        placeholder="0"
                                    />
                                </td>

                                <!-- Input Stok Dingin (Cold) -->
                                <td class="p-4 pr-6">
                                    <input 
                                        type="number" 
                                        min="0"
                                        wire:model.live.debounce.300ms="stocksData.{{ $productId }}.cold_stock" 
                                        class="w-full rounded-xl text-xs p-2.5 font-bold transition-all outline-none {{ $item['is_cold_ready'] ? ($coldVal > $stockVal ? 'border-2 border-rose-500 bg-rose-50 dark:bg-rose-950/40 text-rose-600' : 'border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20') : 'border border-gray-200 dark:border-gray-800 bg-gray-100 dark:bg-gray-800/40 text-gray-400 cursor-not-allowed' }}"
                                        @if(!$item['is_cold_ready']) disabled title="Produk tidak mendukung penyimpanan dingin" @endif
                                        placeholder="0"
                                    />
                                    @if($item['is_cold_ready'] && $coldVal > $stockVal)
                                        <span class="text-[10px] text-rose-500 font-bold block mt-1">Melebihi total stok!</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400 dark:text-gray-500 text-xs">
                                    Tidak ada produk yang cocok dengan pencarian / filter Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-filament-panels::page>