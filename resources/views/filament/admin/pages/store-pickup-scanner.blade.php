<x-filament-panels::page>
    <div class="max-w-4xl mx-auto space-y-6 font-sans">
        
        <!-- CARD INPUT FORM SCANNER -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 md:p-8 shadow-sm transition-colors">
            <div class="flex items-center space-x-3.5 mb-5">
                <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200/60 dark:border-rose-900/60 flex items-center justify-center text-rose-700 dark:text-rose-400 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Verifikasi Kode Pickup Pelanggan</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Arahkan QR Scanner atau ketik PIN Pickup pelanggan secara manual</p>
                </div>
            </div>

            <form wire:submit.prevent="searchOrder" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        wire:model.defer="pickupCodeInput" 
                        placeholder="Masukkan PIN / Scan Kode QR (misal: 88921)" 
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-base font-mono font-bold tracking-wider p-3.5 pl-11 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all outline-none"
                        autofocus
                    />
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                    </div>
                </div>

                <x-filament::button 
                    type="submit" 
                    icon="heroicon-m-magnifying-glass" 
                    size="lg"
                    color="primary"
                    class="shadow-sm flex-shrink-0">
                    Cari Pesanan
                </x-filament::button>
            </form>
        </div>

        <!-- DETAIL HASIL SCAN -->
        @if($scannedOrder)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 md:p-8 shadow-sm space-y-6 transition-colors">
                
                <!-- Header Order Status -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <span class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">No. Pesanan</span>
                        <h3 class="text-2xl font-bold font-mono text-gray-900 dark:text-white mt-0.5">#{{ $scannedOrder->order_number }}</h3>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="text-left sm:text-right">
                            <span class="block text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Status Order</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold capitalize bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                {{ str_replace('_', ' ', $scannedOrder->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pelanggan & Cabang Toko -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 space-y-1">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Nama Pelanggan</span>
                        <p class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $scannedOrder->user?->name ?? $scannedOrder->user?->full_name ?? 'Guest Customer' }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 space-y-1">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Cabang Toko Pickup</span>
                        <p class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h14M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            {{ $scannedOrder->store?->name ?? 'Semua Cabang' }}
                        </p>
                    </div>
                </div>

                <!-- Daftar Item yang Harus Disiapkan -->
                <div class="space-y-3 pt-2">
                    <h4 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Daftar Produk yang Harus Disiapkan:
                    </h4>

                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($scannedOrder->items as $item)
                            <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/50 dark:bg-gray-800/30 hover:bg-gray-100/50 dark:hover:bg-gray-800/60 transition-colors">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $item->product_name_snapshot }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        Jumlah: <span class="font-bold text-gray-700 dark:text-gray-200">{{ $item->quantity }}x</span> @ Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <span class="font-bold text-sm text-gray-900 dark:text-white font-mono">
                                        Rp {{ number_format($item->subtotal_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Handover -->
                <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        * Pastikan fisik barang dan jumlah produk sudah diperiksa sebelum penyerahan.
                    </div>

                    <x-filament::button 
                        wire:click="completeHandover" 
                        color="success" 
                        size="lg" 
                        icon="heroicon-m-check-circle"
                        class="w-full sm:w-auto shadow-sm">
                        Handover & Selesaikan Pesanan
                    </x-filament::button>
                </div>

            </div>
        @endif

    </div>
</x-filament-panels::page>