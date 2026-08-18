<x-filament-panels::page>
    <div x-data="{ activeTab: 'kyc' }" class="space-y-6 font-sans">
        
        <!-- HEADER PROFIL (LIGHT & DARK ADAPTIVE) -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 md:p-8 shadow-sm transition-colors">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                
                <!-- Info Utama User -->
                <div class="flex items-start sm:items-center space-x-5">
                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200/60 dark:border-rose-900/60 flex items-center justify-center font-semibold text-2xl text-rose-800 dark:text-rose-300 shadow-xs">
                        {{ strtoupper(substr($record->name, 0, 2)) }}
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $record->name }}</h1>
                            
                            <!-- Role Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium tracking-wide bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                {{ ucfirst($record->role ?? 'Customer') }}
                            </span>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $record->email }}
                            </span>
                            <span class="text-slate-300 dark:text-slate-700">•</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $record->phone ?? 'Nomor telepon tidak diisi' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Status KYC Badge -->
                @php
                    $kycStatus = optional($record->ktpVerification)->status ?? 'unverified';
                    $kycBadgeStyles = [
                        'approved'   => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'border' => 'border-emerald-200 dark:border-emerald-800/60', 'text' => 'text-emerald-700 dark:text-emerald-400', 'dot' => 'bg-emerald-500', 'label' => 'Terverifikasi (21+)'],
                        'pending'    => ['bg' => 'bg-amber-50 dark:bg-amber-950/40', 'border' => 'border-amber-200 dark:border-amber-800/60', 'text' => 'text-amber-700 dark:text-amber-400', 'dot' => 'bg-amber-500', 'label' => 'Menunggu Verifikasi'],
                        'rejected'   => ['bg' => 'bg-rose-50 dark:bg-rose-950/40', 'border' => 'border-rose-200 dark:border-rose-800/60', 'text' => 'text-rose-700 dark:text-rose-400', 'dot' => 'bg-rose-500', 'label' => 'Ditolak'],
                        'unverified' => ['bg' => 'bg-slate-50 dark:bg-slate-800/40', 'border' => 'border-slate-200 dark:border-slate-700', 'text' => 'text-slate-600 dark:text-slate-400', 'dot' => 'bg-slate-400', 'label' => 'Belum Upload KTP'],
                    ][$kycStatus];
                @endphp

                <div class="inline-flex items-center gap-3 px-4 py-3 rounded-xl border {{ $kycBadgeStyles['bg'] }} {{ $kycBadgeStyles['border'] }}">
                    <span class="w-2.5 h-2.5 rounded-full {{ $kycBadgeStyles['dot'] }} animate-pulse"></span>
                    <div>
                        <span class="block text-[11px] font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">Status Verifikasi</span>
                        <span class="text-xs font-semibold {{ $kycBadgeStyles['text'] }}">{{ $kycBadgeStyles['label'] }}</span>
                    </div>
                </div>
            </div>

            <!-- RINGKASAN METRIK -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                <div class="p-4 rounded-xl bg-slate-50/60 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800/80">
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Belanja</span>
                    <p class="text-lg font-bold text-slate-900 dark:text-white mt-1">
                        Rp {{ number_format($record->orders->where('status', 'paid')->sum('total_amount'), 0, ',', '.') }}
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50/60 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800/80">
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Transaksi</span>
                    <p class="text-lg font-bold text-slate-900 dark:text-white mt-1">{{ $record->orders->count() }} Pesanan</p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50/60 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800/80">
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Usia Customer</span>
                    <p class="text-lg font-bold text-slate-900 dark:text-white mt-1">
                        {{ $record->birth_date ? \Carbon\Carbon::parse($record->birth_date)->age . ' Tahun' : 'Belum diisi' }}
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50/60 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800/80">
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Tanggal Bergabung</span>
                    <p class="text-lg font-bold text-slate-900 dark:text-white mt-1">{{ $record->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- TAB NAVIGASI -->
        <div class="border-b border-slate-200 dark:border-slate-800">
            <nav class="flex space-x-6" aria-label="Tabs">
                <button 
                    @click="activeTab = 'kyc'" 
                    :class="activeTab === 'kyc' ? 'border-rose-800 dark:border-rose-500 text-rose-900 dark:text-rose-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700'"
                    class="py-3 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6"/></svg>
                    Verifikasi KYC (21+)
                </button>

                <button 
                    @click="activeTab = 'address'" 
                    :class="activeTab === 'address' ? 'border-rose-800 dark:border-rose-500 text-rose-900 dark:text-rose-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700'"
                    class="py-3 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Daftar Alamat ({{ $record->addresses->count() }})
                </button>

                <button 
                    @click="activeTab = 'orders'" 
                    :class="activeTab === 'orders' ? 'border-rose-800 dark:border-rose-500 text-rose-900 dark:text-rose-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700'"
                    class="py-3 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Riwayat Transaksi ({{ $record->orders->count() }})
                </button>
            </nav>
        </div>

        <!-- TAB CONTENT 1: VERIFIKASI KYC -->
        <div x-show="activeTab === 'kyc'" class="space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-6">Dokumen Identitas Diri</h2>

                @if($record->ktpVerification)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card Foto KTP -->
                        <div class="space-y-2">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Foto KTP</span>
                            <div class="relative group rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-slate-50 dark:bg-slate-800/50">
                                <img 
                                    src="{{ Storage::url($record->ktpVerification->ktp_photo_path) }}" 
                                    alt="KTP {{ $record->name }}" 
                                    class="w-full h-56 object-cover group-hover:scale-102 transition-transform duration-300"
                                >
                            </div>
                        </div>

                        <!-- Card Foto Selfie -->
                        <div class="space-y-2">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Selfie Memegang KTP</span>
                            <div class="relative group rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-slate-50 dark:bg-slate-800/50">
                                <img 
                                    src="{{ Storage::url($record->ktpVerification->selfie_photo_path) }}" 
                                    alt="Selfie {{ $record->name }}" 
                                    class="w-full h-56 object-cover group-hover:scale-102 transition-transform duration-300"
                                >
                            </div>
                        </div>
                    </div>

                    @if($record->ktpVerification->status === 'rejected')
                        <div class="mt-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <span class="text-xs font-bold text-rose-900 dark:text-rose-300 uppercase">Catatan Penolakan KYC</span>
                                    <p class="text-xs text-rose-700 dark:text-rose-400 mt-0.5">{{ $record->ktpVerification->rejection_reason }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="py-12 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                        <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Belum ada dokumen KYC yang diunggah.</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Customer belum melakukan verifikasi identitas (21+) melalui aplikasi/web.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- TAB CONTENT 2: DAFTAR ALAMAT -->
        <div x-show="activeTab === 'address'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($record->addresses as $address)
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-3 relative">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-sm text-slate-900 dark:text-white">{{ $address->label }}</span>
                        @if($address->is_primary)
                            <span class="px-2.5 py-0.5 text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-full">
                                Utama
                            </span>
                        @endif
                    </div>
                    
                    <div class="text-xs space-y-1 text-slate-600 dark:text-slate-400">
                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ $address->recipient_name }} • {{ $address->phone }}</p>
                        <p class="text-slate-500 dark:text-slate-400 leading-relaxed">{{ $address->full_address }}</p>
                    </div>

                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500 font-mono">
                        <span>Lat: {{ $address->latitude }}</span>
                        <span>Lng: {{ $address->longitude }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white dark:bg-gray-900 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada alamat pengiriman tersimpan.</p>
                </div>
            @endforelse
        </div>

        <!-- TAB CONTENT 3: RIWAYAT TRANSAKSI -->
        <div x-show="activeTab === 'orders'" x-cloak class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">10 Transaksi Terakhir</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-100 dark:border-slate-800 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Kode Order</th>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Total Belanja</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($record->orders as $order)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900 dark:text-white">#{{ $order->order_number ?? $order->id }}</td>
                                <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-medium capitalize bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-slate-400 dark:text-slate-500">Belum ada riwayat pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-filament-panels::page>