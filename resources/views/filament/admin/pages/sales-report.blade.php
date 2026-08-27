<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Panel Form Filter -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <x-heroicon-m-funnel class="w-5 h-5 text-amber-500"/> Filter Laporan Penjualan
            </h3>
            {{ $this->form }}
        </div>

        <!-- Tabel Hasil Laporan Penjualan -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>