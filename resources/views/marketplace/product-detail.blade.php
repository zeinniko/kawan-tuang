@extends('welcome')

@section('title', 'Detail Pesanan ' . ($order['order_number'] ?? '') . ' - Tipsy More')

@section('content')
  @php
    $status = strtolower($order['status'] ?? 'pending_payment');
    $fulfillment = strtolower($order['fulfillment_type'] ?? 'delivery');

    // Color mapper untuk status pesanan
    $statusBadge = match($status) {
        'completed' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
        'delivering', 'dropping_off', 'picking_up' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20',
        'processing', 'allocated' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
        'cancelled', 'rejected', 'failed' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20',
        default => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/20',
    };

    $statusLabel = match($status) {
        'completed' => 'Selesai',
        'delivering' => 'Dalam Pengiriman',
        'dropping_off' => 'Kurir Menuju Lokasi',
        'picking_up' => 'Kurir Menjemput Barang',
        'allocated' => 'Kurir Dialokasikan',
        'processing' => 'Diproses Outlet',
        'cancelled' => 'Dibatalkan',
        'pending_payment' => 'Menunggu Pembayaran',
        default => ucfirst($status),
    };
  @endphp

  <!-- BREADCRUMB -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <nav class="flex text-xs text-slate-500 dark:text-slate-400 gap-2 items-center">
      <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
      <span>/</span>
      <a href="{{ route('orders.index') }}" class="hover:underline">Pesanan Saya</a>
      <span>/</span>
      <span class="text-slate-900 dark:text-slate-200 font-medium truncate">{{ $order['order_number'] ?? 'Detail' }}</span>
    </nav>
  </div>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="space-y-6">

      <!-- HEADER STATUS ORDER -->
      <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-3 flex-wrap">
            <h1 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white">
              {{ $order['order_number'] ?? '-' }}
            </h1>
            <span class="px-3 py-1 rounded-xl text-xs font-bold border backdrop-blur-md {{ $statusBadge }}">
              {{ $statusLabel }}
            </span>
          </div>
          <p class="text-xs text-slate-400 mt-1">
            Waktu Transaksi: {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}
          </p>
        </div>

        <div class="flex items-center gap-2 self-start md:self-auto">
          <a href="{{ route('orders.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- LEFT: ITEMS & TRACKING INFO -->
        <div class="lg:col-span-8 space-y-6">

          <!-- TRACKING & KURIR BANNER -->
          @if($fulfillment === 'delivery')
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
              <h2 class="text-sm font-bold uppercase text-amber-600 dark:text-amber-400 tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-truck-fast"></i> Informasi Pengiriman
              </h2>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/50 border border-slate-100 dark:border-slate-800/80 text-xs">
                <div>
                  <span class="text-slate-400 block mb-0.5">Kurir & Layanan</span>
                  <span class="font-extrabold text-slate-900 dark:text-white uppercase">
                    {{ $order['courier_company'] ?? '-' }} ({{ $order['courier_type'] ?? '-' }})
                  </span>
                </div>
                <div>
                  <span class="text-slate-400 block mb-0.5">Nomor Resi / Waybill</span>
                  <span class="font-mono font-bold text-slate-900 dark:text-white">
                    {{ $order['waybill_number'] ?? 'Belum terbit' }}
                  </span>
                </div>
                <div>
                  <span class="text-slate-400 block mb-0.5">Driver</span>
                  <span class="font-bold text-slate-900 dark:text-white">
                    {{ $order['driver_name'] ?? 'Mencari driver...' }}
                  </span>
                </div>
              </div>

              @if(!empty($order['live_tracking_url']))
                <a href="{{ $order['live_tracking_url'] }}" target="_blank" rel="noopener noreferrer" 
                   class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 px-4 rounded-xl text-xs transition-all flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20">
                  <i class="fa-solid fa-map-location-dot"></i> Lacak Pengiriman Live
                </a>
              @endif
            </div>
          @endif

          <!-- DAFTAR ITEM PRODUK -->
          <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase text-slate-900 dark:text-white tracking-wider flex items-center gap-2">
              <i class="fa-solid fa-wine-bottle text-amber-500"></i> Rincian Produk
            </h2>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
              @forelse($order['items'] ?? [] as $item)
                @php
                  // Menggunakan fallback subtotal_price / subtotal agar tidak crash
                  $itemPrice = $item['unit_price'];
                  $itemQty = $item['quantity'];
                  $itemSubtotal = $item['subtotal_price'];
                  $itemName = $item['product_name_snapshot'];
                @endphp
                <div class="py-4 flex items-center justify-between gap-4 first:pt-0 last:pb-0">
                  <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center flex-shrink-0 text-amber-500">
                      <i class="fa-solid fa-glass-water text-lg"></i>
                    </div>
                    <div>
                      <h3 class="text-xs md:text-sm font-bold text-slate-900 dark:text-white line-clamp-1">
                        {{ $itemName }}
                      </h3>
                      <p class="text-xs text-slate-400">
                        {{ $itemQty }} x Rp {{ number_format($itemPrice, 0, ',', '.') }}
                      </p>
                    </div>
                  </div>
                  <div class="text-right">
                    <span class="text-xs md:text-sm font-extrabold text-amber-600 dark:text-amber-400">
                      Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                    </span>
                  </div>
                </div>
              @empty
                <div class="py-8 text-center text-xs text-slate-400">
                  Tidak ada item produk pada pesanan ini.
                </div>
              @endforelse
            </div>
          </div>

          <!-- DETAIL LOKASI PENGIRIMAN & OUTLET -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- ALAMAT PENERIMA -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-2">
              <h3 class="text-xs font-bold uppercase text-slate-400 flex items-center gap-2">
                <i class="fa-solid fa-location-dot text-rose-500"></i> Alamat Tujuan
              </h3>
              <p class="text-xs font-bold text-slate-900 dark:text-white">
                {{ $order['address']['recipient_name'] ?? '-' }} 
                <span class="font-normal text-slate-400">({{ $order['address']['recipient_phone'] ?? '-' }})</span>
              </p>
              <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                {{ $order['address']['full_address'] ?? 'Alamat tidak tersedia' }}
              </p>
            </div>

            <!-- OUTLET TOKO -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-2">
              <h3 class="text-xs font-bold uppercase text-slate-400 flex items-center gap-2">
                <i class="fa-solid fa-store text-amber-500"></i> Outlet Pengirim
              </h3>
              <p class="text-xs font-bold text-slate-900 dark:text-white">
                {{ $order['store']['name'] ?? '-' }}
              </p>
              <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                {{ $order['store']['address'] ?? 'Alamat outlet tidak tersedia' }}
              </p>
              <p class="text-xs text-slate-400">
                Telp: {{ $order['store']['phone_number'] ?? '-' }}
              </p>
            </div>

          </div>

        </div>

        <!-- RIGHT: RINGKASAN PEMBAYARAN -->
        <div class="lg:col-span-4 space-y-6">
          <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 sticky top-24">
            <h2 class="text-sm font-bold uppercase text-slate-900 dark:text-white tracking-wider flex items-center gap-2">
              <i class="fa-solid fa-receipt text-amber-500"></i> Ringkasan Pembayaran
            </h2>

            @php
              $subtotal = $order['subtotal'] ?? 0;
              $deliveryFee = $order['delivery_fee'] ?? 0;
              $adminFee = $order['admin_fee'] ?? 0;
              $discount = $order['discount_amount'] ?? 0;
              $totalAmount = $order['total_amount'] ?? ($subtotal + $deliveryFee + $adminFee - $discount);
            @endphp

            <div class="space-y-2.5 text-xs">
              <div class="flex justify-between text-slate-600 dark:text-slate-400">
                <span>Subtotal Produk</span>
                <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
              </div>

              <div class="flex justify-between text-slate-600 dark:text-slate-400">
                <span>Ongkos Kirim</span>
                <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
              </div>

              @if($adminFee > 0)
                <div class="flex justify-between text-slate-600 dark:text-slate-400">
                  <span>Biaya Layanan / Admin</span>
                  <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($adminFee, 0, ',', '.') }}</span>
                </div>
              @endif

              @if($discount > 0)
                <div class="flex justify-between text-emerald-600 dark:text-emerald-400">
                  <span>Diskon Voucher</span>
                  <span class="font-bold">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                </div>
              @endif

              <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-between items-baseline">
                <span class="text-sm font-black text-slate-900 dark:text-white">Total Bayar</span>
                <span class="text-lg font-black text-amber-600 dark:text-amber-400">
                  Rp {{ number_format($totalAmount, 0, ',', '.') }}
                </span>
              </div>
            </div>

            <!-- CARA / METODE PEMBAYARAN -->
            <div class="p-4 rounded-2xl bg-amber-500/5 border border-amber-500/20 text-xs text-amber-700 dark:text-amber-300">
              <p class="font-bold mb-0.5"><i class="fa-solid fa-shield-check me-1"></i> Pembayaran Aman via Midtrans</p>
              <p class="text-[11px] opacity-80">Transaksi dikonfirmasi secara otomatis oleh sistem.</p>
            </div>
          </div>
        </div>

      </div>

    </div>
  </main>
@endsection