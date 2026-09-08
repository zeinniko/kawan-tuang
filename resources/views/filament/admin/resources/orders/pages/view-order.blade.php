<x-filament-panels::page>
    <div x-data="{
        showModal: false,
        targetStatus: '',
        targetLabel: '',
        targetIcon: '',
        openConfirm(status, label, icon) {
            this.targetStatus = status;
            this.targetLabel = label;
            this.targetIcon = icon;
            this.showModal = true;
        },
        submitUpdate() {
            $wire.updateStatus(this.targetStatus);
            this.showModal = false;
        }
    }" class="max-w-5xl mx-auto space-y-6 font-sans relative">

        <!-- HEADER BANNER ORDER -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm transition-colors">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold font-mono text-gray-900 dark:text-white">#{{ $record->order_number }}</h1>
                        
                        <!-- Badge Fulfillment Type -->
                        @if($record->fulfillment_type === 'pickup')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 border border-purple-200 dark:border-purple-800/60">
                                🏪 PICKUP TOKO
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60">
                                🚚 DELIVERY
                            </span>
                        @endif

                        @php
                            $statusBadge = match($record->status) {
                                'completed', 'paid' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-700 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-800/60', 'label' => 'Selesai'],
                                'ready_for_pickup'  => ['bg' => 'bg-purple-50 dark:bg-purple-950/40', 'text' => 'text-purple-700 dark:text-purple-400', 'border' => 'border-purple-200 dark:border-purple-800/60', 'label' => 'Siap Pickup'],
                                'processing'       => ['bg' => 'bg-amber-50 dark:bg-amber-950/40', 'text' => 'text-amber-700 dark:text-amber-400', 'border' => 'border-amber-200 dark:border-amber-800/60', 'label' => 'Diproses'],
                                'cancelled'        => ['bg' => 'bg-rose-50 dark:bg-rose-950/40', 'text' => 'text-rose-700 dark:text-rose-400', 'border' => 'border-rose-200 dark:border-rose-800/60', 'label' => 'Dibatalkan'],
                                default            => ['bg' => 'bg-gray-100 dark:bg-gray-800', 'text' => 'text-gray-700 dark:text-gray-300', 'border' => 'border-gray-200 dark:border-gray-700', 'label' => ucfirst(str_replace('_', ' ', $record->status))],
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

                <!-- Kode Pickup Toko -->
                @if($record->pickup_code || $record->pickup_pin)
                    <div class="flex items-center gap-3 p-3 bg-purple-50/60 dark:bg-purple-950/30 rounded-xl border border-purple-200 dark:border-purple-800/50">
                        <div class="text-right">
                            <span class="block text-[10px] font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider">Kode Pickup Pemesan</span>
                            <span class="text-xl font-mono font-black text-purple-700 dark:text-purple-300 tracking-widest">{{ $record->pickup_code ?? $record->pickup_pin }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- CONTROL PANEL (LIGHT THEME): UBAH STATUS PICKUP -->
        @if($record->fulfillment_type === 'pickup' || empty($record->fulfillment_type))
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-amber-200/80 dark:border-amber-500/20 shadow-sm transition-all">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="p-1 rounded-md bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 text-xs">⚡</span> Update Status Pickup Toko
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pilih status terbaru pesanan untuk diperbarui di aplikasi pelanggan.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
                    <!-- Status 1: Diproses -->
                    <button type="button" 
                            @click="openConfirm('processing', 'Diproses Penjual', '📦')"
                            class="flex-1 sm:flex-initial px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 {{ $record->status === 'processing' ? 'bg-amber-500 text-gray-950 shadow-md shadow-amber-500/20 ring-2 ring-amber-400' : 'bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">
                        <span>📦</span> 1. Diproses
                    </button>

                    <!-- Status 2: Siap Pickup -->
                    <button type="button" 
                            @click="openConfirm('ready_for_pickup', 'Siap Pickup di Outlet', '🏪')"
                            class="flex-1 sm:flex-initial px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 {{ $record->status === 'ready_for_pickup' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 ring-2 ring-purple-400' : 'bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">
                        <span>🏪</span> 2. Siap Pickup
                    </button>

                    <!-- Status 3: Selesai -->
                    <button type="button" 
                            @click="openConfirm('completed', 'Selesai / Sudah Diambil', '✅')"
                            class="flex-1 sm:flex-initial px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 {{ $record->status === 'completed' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20 ring-2 ring-emerald-400' : 'bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">
                        <span>✅</span> 3. Selesai
                    </button>
                </div>
            </div>
        </div>
        @endif

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

            <!-- Card Store Pickup -->
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
                        <span class="font-mono">Rp {{ number_format($record->service_fee ?? $record->admin_fee ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700 flex justify-between text-sm font-bold text-gray-900 dark:text-white">
                        <span>Total Pembayaran</span>
                        <span class="font-mono text-rose-600 dark:text-rose-400">Rp {{ number_format($record->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL KONFIRMASI MODERN (ALPINE.JS) -->
        <template x-teleport="body">
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
                 style="display: none;">
                
                <div @click.away="showModal = false"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white dark:bg-gray-900 rounded-3xl max-w-md w-full p-6 border border-gray-200 dark:border-gray-800 shadow-2xl space-y-5 text-center">
                    
                    <!-- Icon Modal -->
                    <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-500 flex items-center justify-center text-3xl mx-auto shadow-inner">
                        <span x-text="targetIcon"></span>
                    </div>

                    <!-- Detail Info -->
                    <div class="space-y-2">
                        <h3 class="text-base font-extrabold text-gray-900 dark:text-white">
                            Ubah Status Pesanan?
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Apakah Anda yakin ingin mengubah status pesanan <strong class="text-gray-900 dark:text-white font-mono">#{{ $record->order_number }}</strong> menjadi:
                        </p>
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-2xl">
                            <span class="text-sm font-extrabold text-amber-700 dark:text-amber-400" x-text="targetLabel"></span>
                        </div>
                    </div>

                    <!-- Tombol Aksi Modal -->
                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" 
                                @click="showModal = false"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold py-3 rounded-2xl text-xs transition-all">
                            Batal
                        </button>
                        <button type="button" 
                                @click="submitUpdate()"
                                class="flex-1 bg-amber-500 hover:bg-amber-600 text-gray-950 font-extrabold py-3 rounded-2xl text-xs transition-all shadow-lg shadow-amber-500/20">
                            Ya, Ubah Status
                        </button>
                    </div>
                </div>
            </div>
        </template>

    </div>
</x-filament-panels::page>