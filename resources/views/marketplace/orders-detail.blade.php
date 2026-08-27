@extends('welcome')

@section('title', 'Detail Pesanan #' . ($order['order_number'] ?? '---') . ' - Tipsy More')

@section('content')
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- BREADCRUMB / BACK LINK -->
    <div class="mb-4">
      <a href="{{ route('orders.index') }}" class="text-xs text-amber-500 hover:text-amber-600 font-bold flex items-center gap-1 transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pesanan
      </a>
    </div>

    @if(!$order)
      <div class="bg-white dark:bg-slate-900 rounded-2xl p-12 text-center border border-slate-200 dark:border-slate-800 shadow-sm">
        <i class="fa-solid fa-circle-exclamation text-amber-500 text-4xl mb-3"></i>
        <h3 class="text-base font-bold text-slate-900 dark:text-white">Pesanan Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Data pesanan tidak dapat dimuat atau belum tersedia.</p>
      </div>
    @else
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- LEFT COLUMN: TRACKING, MAP & COURIER INFO (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">

          <!-- ORDER HEADER CARD -->
          <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
            <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-slate-200 dark:border-slate-800">
              <div>
                <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-semibold">ID Pesanan</span>
                <span class="text-sm font-extrabold text-slate-900 dark:text-white font-mono">#{{ $order['order_number'] ?? $order['id'] }}</span>
              </div>
              <div class="flex items-center gap-2">
                @php
                  $status = strtolower($order['status'] ?? 'pending_payment');
                  $statusConfig = [
                      'pending_payment' => ['label' => 'Menunggu Pembayaran', 'color' => 'bg-amber-500/10 text-amber-600 border-amber-500/20'],
                      'processing'      => ['label' => 'Diproses Penjual', 'color' => 'bg-blue-500/10 text-blue-600 border-blue-500/20'],
                      'shipped'         => ['label' => 'Dalam Pengiriman', 'color' => 'bg-indigo-500/10 text-indigo-600 border-indigo-500/20'],
                      'completed'       => ['label' => 'Pesanan Selesai', 'color' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20'],
                      'cancelled'       => ['label' => 'Dibatalkan', 'color' => 'bg-rose-500/10 text-rose-600 border-rose-500/20'],
                  ];
                  $currStatus = $statusConfig[$status] ?? ['label' => ucfirst(str_replace('_', ' ', $status)), 'color' => 'bg-slate-500/10 text-slate-600 border-slate-500/20'];
                @endphp
                <span class="px-3 py-1 text-xs font-bold rounded-full border flex items-center gap-1.5 {{ $currStatus['color'] }}">
                  <span class="w-2 h-2 rounded-full bg-current animate-ping"></span> {{ $currStatus['label'] }}
                </span>
              </div>
            </div>

            <!-- STEPPER STATUS TRACKER -->
            <div class="py-6">
              <div class="relative flex items-center justify-between">
                <!-- Line Connector Background -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 w-full bg-slate-200 dark:bg-slate-800 z-0"></div>
                <!-- Active Line Connector -->
                @php
                  $lineWidth = match($status) {
                      'completed' => 'w-full',
                      'shipped' => 'w-3/4',
                      'processing' => 'w-1/2',
                      default => 'w-1/4'
                  };
                @endphp
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 {{ $lineWidth }} bg-amber-500 z-0 transition-all duration-300"></div>

                <!-- Step 1: Diterima -->
                <div class="relative z-10 flex flex-col items-center">
                  <div class="w-8 h-8 rounded-full bg-amber-500 text-slate-950 font-bold flex items-center justify-center text-xs shadow-md">
                    <i class="fa-solid fa-check"></i>
                  </div>
                  <span class="text-[10px] font-bold text-slate-900 dark:text-white mt-2">Dibuat</span>
                </div>

                <!-- Step 2: Diproses -->
                <div class="relative z-10 flex flex-col items-center">
                  <div class="w-8 h-8 rounded-full {{ in_array($status, ['processing', 'shipped', 'completed']) ? 'bg-amber-500 text-slate-950' : 'bg-slate-200 dark:bg-slate-800 text-slate-400' }} font-bold flex items-center justify-center text-xs">
                    <i class="fa-solid fa-box"></i>
                  </div>
                  <span class="text-[10px] font-bold text-slate-900 dark:text-white mt-2">Diproses</span>
                </div>

                <!-- Step 3: Dikirim -->
                <div class="relative z-10 flex flex-col items-center">
                  <div class="w-8 h-8 rounded-full {{ in_array($status, ['shipped', 'completed']) ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/30 ring-4 ring-amber-500/20' : 'bg-slate-200 dark:bg-slate-800 text-slate-400' }} font-bold flex items-center justify-center text-xs">
                    <i class="fa-solid fa-motorcycle"></i>
                  </div>
                  <span class="text-[10px] font-bold text-slate-900 dark:text-white mt-2">Dikirim</span>
                </div>

                <!-- Step 4: Selesai -->
                <div class="relative z-10 flex flex-col items-center">
                  <div class="w-8 h-8 rounded-full {{ $status === 'completed' ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-400' }} font-bold flex items-center justify-center text-xs">
                    <i class="fa-solid fa-house"></i>
                  </div>
                  <span class="text-[10px] font-medium text-slate-400 mt-2">Selesai</span>
                </div>
              </div>
            </div>

            <!-- ESTIMATED ARRIVAL & COURIER INFO -->
            <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3 flex items-center justify-between">
              <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-truck-fast text-amber-500 text-base"></i>
                <div>
                  <span class="text-xs font-bold text-slate-900 dark:text-white block">Metode Pengiriman</span>
                  <span class="text-[11px] text-slate-500 dark:text-slate-400">
                    {{ strtoupper($order['courier_company'] ?? 'Gojek') }} ({{ ucfirst($order['courier_type'] ?? 'Instant') }})
                  </span>
                </div>
              </div>
              @if(!empty($order['waybill_number']))
                <span class="text-xs font-bold font-mono bg-amber-500/20 text-amber-600 dark:text-amber-400 px-2.5 py-1 rounded-lg">
                  Resi: {{ $order['waybill_number'] }}
                </span>
              @endif
            </div>
          </div>

          <!-- LIVE MAP TRACKING MOCKUP -->
          <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="p-3 bg-slate-100 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
              <span class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-amber-500"></i> Peta Lokasi Pengiriman
              </span>
              <span class="text-[10px] bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold px-2 py-0.5 rounded">GPS Aktif</span>
            </div>
            
            <div class="relative h-56 bg-slate-200 dark:bg-slate-950 flex items-center justify-center overflow-hidden">
              <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:16px_16px]"></div>

              <svg class="absolute inset-0 w-full h-full stroke-amber-500" stroke-width="3" stroke-dasharray="6,6">
                <path d="M 80 160 Q 180 80 320 120" fill="none" />
              </svg>

              <!-- Store Marker -->
              <div class="absolute left-16 bottom-10 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full bg-slate-900 text-amber-400 flex items-center justify-center text-xs font-bold shadow-lg border border-amber-500">
                  <i class="fa-solid fa-store"></i>
                </div>
                <span class="text-[9px] font-bold bg-slate-900/90 text-white px-2 py-0.5 rounded mt-1 shadow truncate max-w-[100px]">
                  {{ $order['store']['name'] ?? 'Toko' }}
                </span>
              </div>

              <!-- Driver Marker -->
              <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center animate-bounce">
                <div class="w-10 h-10 rounded-full bg-amber-500 text-slate-950 flex items-center justify-center text-sm font-bold shadow-xl ring-4 ring-amber-500/30">
                  <i class="fa-solid fa-motorcycle"></i>
                </div>
                <span class="text-[9px] font-bold bg-amber-500 text-slate-950 px-2 py-0.5 rounded mt-1 shadow">Kurir</span>
              </div>

              <!-- Destination Marker -->
              <div class="absolute right-16 top-16 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center text-xs font-bold shadow-lg">
                  <i class="fa-solid fa-location-dot"></i>
                </div>
                <span class="text-[9px] font-bold bg-slate-900/90 text-white px-2 py-0.5 rounded mt-1 shadow truncate max-w-[100px]">
                  {{ $order['address']['recipient_name'] ?? 'Lokasi Anda' }}
                </span>
              </div>
            </div>
          </div>

          <!-- OUTLET & STORE INFO CARD -->
          @if(!empty($order['store']))
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between gap-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-500 flex items-center justify-center font-bold text-base border border-amber-500/30">
                  <i class="fa-solid fa-store"></i>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-slate-900 dark:text-white">{{ $order['store']['name'] }}</h4>
                  <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-1">{{ $order['store']['address'] ?? '-' }}</p>
                </div>
              </div>
              @if(!empty($order['store']['phone_number']))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order['store']['phone_number']) }}" target="_blank" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-emerald-500 hover:text-white text-slate-700 dark:text-slate-200 flex items-center justify-center transition-colors flex-shrink-0" title="Hubungi Toko">
                  <i class="fa-solid fa-phone text-xs"></i>
                </a>
              @endif
            </div>
          @endif

        </div>

        <!-- RIGHT COLUMN: ITEM DETAILS & RECEIPT (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">

          <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 transition-colors">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-3 flex items-center gap-2">
              <i class="fa-solid fa-receipt text-amber-500"></i> Detail Rincian Pesanan
            </h3>

            <!-- ITEMS LOOP -->
            <div class="space-y-3">
              @forelse($order['items'] ?? [] as $item)
                <div class="flex items-center gap-3">
                  <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if(!empty($item['thumbnail_url']))
                      <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['product_name'] ?? 'Produk' }}" class="w-full h-full object-cover">
                    @else
                      <i class="fa-solid fa-wine-bottle text-xl text-amber-500"></i>
                    @endif
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $item['product_name'] ?? 'Produk Tipsy More' }}</h4>
                    <p class="text-[10px] text-slate-400">
                      {{ $item['quantity'] ?? 1 }}x @ Rp {{ number_format($item['unit_price'] ?? 0, 0, ',', '.') }}
                    </p>
                  </div>
                  <span class="text-xs font-bold text-slate-900 dark:text-white">
                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                  </span>
                </div>
              @empty
                <p class="text-xs text-slate-400 text-center py-2">Tidak ada rincian item produk.</p>
              @endforelse
            </div>

            <!-- PAYMENT SUMMARY -->
            <div class="border-t border-slate-200 dark:border-slate-800 pt-3 space-y-2 text-xs text-slate-600 dark:text-slate-400">
              <div class="flex justify-between">
                <span>Subtotal Produk</span>
                <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($order['total_items_price'] ?? 0, 0, ',', '.') }}</span>
              </div>
              
              <div class="flex justify-between">
                <span>Biaya Pengiriman</span>
                <span class="font-medium text-slate-900 dark:text-white">Rp {{ number_format($order['shipping_cost'] ?? 0, 0, ',', '.') }}</span>
              </div>

              @if(!empty($order['discount_amount']) && $order['discount_amount'] > 0)
                <div class="flex justify-between text-emerald-500">
                  <span>Diskon Voucher</span>
                  <span>- Rp {{ number_format($order['discount_amount'], 0, ',', '.') }}</span>
                </div>
              @endif

              <div class="flex justify-between text-amber-600 dark:text-amber-400 font-extrabold text-sm pt-2 border-t border-slate-100 dark:border-slate-800">
                <span>Total Akhir</span>
                <span>Rp {{ number_format($order['total_amount'] ?? $order['grand_total'] ?? 0, 0, ',', '.') }}</span>
              </div>
            </div>

            <!-- SHIPPING ADDRESS SNAPSHOT -->
            @if(!empty($order['addresses']))
              <div class="border-t border-slate-200 dark:border-slate-800 pt-3 text-xs space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Alamat Pengiriman</span>
                <p class="font-bold text-slate-900 dark:text-white">
                  {{ $order['address']['recipient_name'] ?? '' }} 
                  <span class="text-slate-400 font-normal">({{ $order['address']['recipient_phone'] ?? '' }})</span>
                </p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                  {{ $order['address']['full_address'] ?? '' }}
                </p>
              </div>
            @endif

            <!-- ACTION BUTTONS -->
            <div class="pt-2 space-y-2">
              <a href="{{ route('catalog.index') }}" class="block w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold py-2.5 rounded-xl text-center text-xs transition-all shadow-md shadow-amber-500/10">
                Belanja Lagi
              </a>
            </div>
          </div>

        </div>

      </div>
    @endif
  </div>
@endsection