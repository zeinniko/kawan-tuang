<x-filament-panels::page>
    <div class="max-w-4xl mx-auto space-y-6 font-sans">
        
        <!-- PRINT TRIGGER BAR -->
        <div class="flex justify-end print:hidden">
            <x-filament::button 
                onclick="window.print()" 
                icon="heroicon-m-printer" 
                color="gray">
                Cetak Invoice
            </x-filament::button>
        </div>

        <!-- DIGITAL INVOICE CONTAINER -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 shadow-sm space-y-8 print:shadow-none print:border-none print:p-0 transition-colors">
            
            <!-- INVOICE HEADER -->
            <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-800 pb-6">
                <div>
                    <h1 class="text-2xl font-black text-rose-800 dark:text-rose-500 tracking-tight">TEMAN TUANG</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Official Store Invoice</p>
                </div>
                
                <div class="text-right">
                    <span class="px-3 py-1 text-xs font-mono font-bold uppercase rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                        {{ strtoupper($record->status ?? 'PAID') }}
                    </span>
                    <p class="text-xs font-mono text-gray-400 mt-2">No. Invoice: INV/{{ $record->created_at->format('Ymd') }}/{{ $record->id }}</p>
                </div>
            </div>

            <!-- INVOICE META GRID -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 uppercase font-semibold text-[10px]">No. Order</span>
                    <p class="font-mono font-bold text-gray-900 dark:text-white mt-0.5">#{{ $record->order?->order_number }}</p>
                </div>
                <div>
                    <span class="text-gray-400 uppercase font-semibold text-[10px]">Tanggal Pembayaran</span>
                    <p class="font-semibold text-gray-900 dark:text-white mt-0.5">{{ $record->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-gray-400 uppercase font-semibold text-[10px]">Metode Pembayaran</span>
                    <p class="font-semibold text-gray-900 dark:text-white mt-0.5 uppercase">{{ $record->payment_method ?? 'QRIS / Online' }}</p>
                </div>
                <div>
                    <span class="text-gray-400 uppercase font-semibold text-[10px]">ID Transaksi Gateway</span>
                    <p class="font-mono text-gray-600 dark:text-gray-400 mt-0.5 truncate">{{ $record->transaction_id ?? '-' }}</p>
                </div>
            </div>

            <!-- BILLED TO & FROM -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-gray-800 text-xs">
                <div>
                    <span class="text-gray-400 font-semibold uppercase text-[10px]">Ditagihkan Kepada:</span>
                    <p class="font-bold text-gray-900 dark:text-white text-sm mt-1">{{ $record->order?->user?->name ?? 'Customer' }}</p>
                    <p class="text-gray-500 dark:text-gray-400 mt-0.5">{{ $record->order?->user?->email }}</p>
                </div>
                <div class="sm:text-right">
                    <span class="text-gray-400 font-semibold uppercase text-[10px]">Cabang Penjual:</span>
                    <p class="font-bold text-gray-900 dark:text-white text-sm mt-1">{{ $record->order?->store?->name ?? 'Teman Tuang Central' }}</p>
                    <p class="text-gray-500 dark:text-gray-400 mt-0.5">{{ $record->order?->store?->address }}</p>
                </div>
            </div>

            <!-- INVOICE ITEMS TABLE -->
            <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
                <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="p-3.5 pl-4">Deskripsi Produk</th>
                            <th class="p-3.5 text-center">Qty</th>
                            <th class="p-3.5 text-right">Harga</th>
                            <th class="p-3.5 pr-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($record->order?->items ?? [] as $item)
                            <tr>
                                <td class="p-3.5 pl-4 font-semibold text-gray-900 dark:text-white">{{ $item->product_name_snapshot }}</td>
                                <td class="p-3.5 text-center font-bold text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                                <td class="p-3.5 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="p-3.5 pr-4 text-right font-mono font-bold text-gray-900 dark:text-white">Rp {{ number_format($item->subtotal_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TOTAL AMOUNT & STAMP -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                <div class="text-xs text-gray-400 italic">
                    Terima kasih telah berbelanja di Teman Tuang.
                </div>
                
                <div class="w-full sm:w-64 p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 space-y-1 text-right">
                    <span class="text-[11px] font-medium text-gray-400 uppercase">Total Lunas</span>
                    <p class="text-xl font-bold font-mono text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($record->amount ?? $record->order?->total_amount ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>