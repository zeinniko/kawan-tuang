<x-filament-panels::page>
    <div class="max-w-5xl mx-auto space-y-6 font-sans">

        <!-- HEADER BANNER ORDER & ACTIONS -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm transition-colors">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold font-mono text-gray-900 dark:text-white">#{{ $record->order_number }}</h1>
                        @php
                            $statusBadge = match($record->status) {
                                'completed', 'paid' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-700 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-800/60', 'label' => 'Selesai'],
                                'ready_for_pickup'  => ['bg' => 'bg-cyan-50 dark:bg-cyan-950/40', 'text' => 'text-cyan-700 dark:text-cyan-400', 'border' => 'border-cyan-200 dark:border-cyan-800/60', 'label' => 'Siap Pickup'],
                                'processing'       => ['bg' => 'bg-amber-50 dark:bg-amber-950/40', 'text' => 'text-amber-700 dark:text-amber-400', 'border' => 'border-amber-200 dark:border-amber-800/60', 'label' => 'Diproses'],
                                'cancelled'        => ['bg' => 'bg-rose-50 dark:bg-rose-950/40', 'text' => 'text-rose-700 dark:text-rose-400', 'border' => 'border-rose-200 dark:border-rose-800/60', 'label' => 'Dibatalkan'],
                                default            => ['bg' => 'bg-gray-100 dark:bg-gray-800', 'text' => 'text-gray-700 dark:text-gray-300', 'border' => 'border-gray-200 dark:border-gray-700', 'label' => ucfirst($record->status)],
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} {{ $statusBadge['border'] }}">
                            {{ $statusBadge['label'] }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Dibuat pada {{ $record->created_at->format('d F Y, H:i') }} WIB
                    </p>
                </div>

                <!-- PIN Pickup Badge -->
                @if($record->pickup_pin)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800">
                        <div class="text-right">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider">PIN Pickup Toko</span>
                            <span class="text-lg font-mono font-bold text-rose-600 dark:text-rose-400 tracking-widest">{{ $record->pickup_pin }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- INFORMASI PELANGGAN & TOKO PICKUP -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card Customer -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm space-y-3">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Informasi Pemesan</h3>
                <div class="flex items-center space-x-3.5 pt-1">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center font-bold text-gray-700 dark:text-gray-300">
                        {{ strtoupper(substr($record->user?->name ?? 'G', 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $record->user?->name ?? 'Guest User' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->user?->email }} • {{ $record->user?->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Store Pickup / Shipping -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm space-y-3">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Lokasi Penyerahan (Store Pickup)</h3>
                <div class="pt-1">
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $record->store?->name ?? 'Pusat Teman Tuang' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">{{ $record->store?->address ?? 'Alamat toko tidak diset' }}</p>
                </div>
            </div>
        </div>

        <!-- DAFTAR ITEM PESANAN -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Rincian Produk</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-3.5">Produk</th>
                            <th class="px-6 py-3.5">Suhu Storage</th>
                            <th class="px-6 py-3.5 text-center">Jumlah</th>
                            <th class="px-6 py-3.5 text-right">Harga Satuan</th>
                            <th class="px-6 py-3.5 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($record->items as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $item->product_name_snapshot ?? $item->product?->name }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->is_cold)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-cyan-50 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800">
                                            ❄️ Cold Stock
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                            Room Temp
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white">{{ $item->quantity }}x</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white font-mono">
                                    Rp {{ number_format($item->subtotal_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TOTAL SUMMARY -->
            <div class="p-6 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                <div class="w-full sm:w-72 space-y-2 text-xs">
                    <div class="flex justify-between text-gray-500 dark:text-gray-400">
                        <span>Subtotal Produk</span>
                        <span class="font-mono">Rp {{ number_format($record->items->sum('subtotal_price'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500 dark:text-gray-400">
                        <span>Biaya Layanan / Packaging</span>
                        <span class="font-mono">Rp {{ number_format($record->service_fee ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700 flex justify-between text-sm font-bold text-gray-900 dark:text-white">
                        <span>Total Pembayaran</span>
                        <span class="font-mono text-rose-600 dark:text-rose-400">Rp {{ number_format($record->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>