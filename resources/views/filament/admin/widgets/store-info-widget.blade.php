<x-filament-widgets::widget>
    <x-filament::section>
        @php
            $user = auth()->user();
            $store = $user?->store;
        @endphp

        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h14M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    @if($store)
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $store->name }}</h3>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                {{ $store->store_code }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                             Jam Buka: <span class="font-bold text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($store->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($store->close_time)->format('H:i') }} WIB</span>
                        </p>
                    @else
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Akses Superadmin</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Menampilkan agregasi data dari seluruh cabang toko.</p>
                    @endif
                </div>
            </div>

            <div class="text-right shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ ($store ? $store->is_active : true) ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800' }}">
                    <span class="w-2 h-2 rounded-full {{ ($store ? $store->is_active : true) ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                    {{ ($store ? $store->is_active : true) ? 'Cabang Operasional' : 'Cabang Non-Aktif' }}
                </span>
            </div>
        </div>
    </x-filament-widgets::widget>
</x-filament-widgets::widget>