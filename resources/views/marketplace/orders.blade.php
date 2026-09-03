@extends('welcome')

@section('title', 'Daftar Riwayat Pesanan - Tipsy More')

@section('content')
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- PAGE TITLE & HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Pesanan Saya</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pantau status transaksi, pengiriman, dan riwayat belanja Anda</p>
      </div>
    </div>

    <!-- MODERN STATUS TABS FILTER -->
    @php
      $currentStatus = request('status');
      $tabs = [
          ''                 => ['label' => 'Semua Pesanan', 'icon' => 'fa-list'],
          'pending_payment'  => ['label' => 'Menunggu Bayar', 'icon' => 'fa-clock'],
          'processing'       => ['label' => 'Diproses', 'icon' => 'fa-boxes-packing'],
          'shipped'          => ['label' => 'Dalam Pengiriman', 'icon' => 'fa-truck-fast'],
          'completed'        => ['label' => 'Selesai', 'icon' => 'fa-circle-check'],
          'cancelled'        => ['label' => 'Dibatalkan', 'icon' => 'fa-circle-xmark'],
      ];
    @endphp

    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-3 mb-8 overflow-x-auto scrollbar-none">
      @foreach($tabs as $statusKey => $tab)
        @php
          $isActive = $currentStatus === $statusKey || (empty($currentStatus) && empty($statusKey));
        @endphp
        <a href="{{ empty($statusKey) ? route('orders.index') : route('orders.index', ['status' => $statusKey]) }}" 
           class="px-4 py-2.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 {{ $isActive ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/10 ring-2 ring-amber-500/20' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
          <i class="fa-solid {{ $tab['icon'] }} text-[11px]"></i>
          {{ $tab['label'] }}
        </a>
      @endforeach
    </div>

    <!-- LIST ORDERS CONTAINER -->
    <div class="space-y-5">
      @forelse($orders as $item)
        @php
          $rawStatus = strtolower($item['status'] ?? 'pending_payment');
          $statusConfig = [
              'pending_payment' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400', 'border' => 'border-amber-500/20', 'label' => 'Menunggu Pembayaran', 'icon' => 'fa-clock'],
              'processing'      => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'border-blue-500/20', 'label' => 'Diproses Penjual', 'icon' => 'fa-box-open'],
              'shipped'         => ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-600 dark:text-indigo-400', 'border' => 'border-indigo-500/20', 'label' => 'Dalam Pengiriman', 'icon' => 'fa-motorcycle'],
              'completed'       => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400', 'border' => 'border-emerald-500/20', 'label' => 'Selesai', 'icon' => 'fa-circle-check'],
              'cancelled'       => ['bg' => 'bg-rose-500/10', 'text' => 'text-rose-600 dark:text-rose-400', 'border' => 'border-rose-500/20', 'label' => 'Dibatalkan', 'icon' => 'fa-circle-xmark'],
          ];
          $badge = $statusConfig[$rawStatus] ?? ['bg' => 'bg-slate-500/10', 'text' => 'text-slate-600 dark:text-slate-400', 'border' => 'border-slate-500/20', 'label' => ucfirst($rawStatus), 'icon' => 'fa-info-circle'];

          $items = $item['items'] ?? [];
          $firstItem = $items[0] ?? null;
          $remainingItemsCount = count($items) - 1;
        @endphp

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all overflow-hidden">
          
          <!-- CARD HEADER: STORE NAME, DATE & STATUS -->
          <div class="p-4 bg-slate-50/50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-3">
              <!-- Outlet / Store Badge -->
              <span class="font-extrabold text-slate-900 dark:text-white flex items-center gap-1.5">
                <i class="fa-solid fa-store text-amber-500"></i>
                {{ $item['store']['name'] ?? 'Tipsy More Outlet' }}
              </span>
              <span class="text-slate-300 dark:text-slate-700">•</span>
              <!-- Order Date -->
              <span class="text-slate-500 dark:text-slate-400">
                {{ isset($item['created_at']) ? date('d M Y, H:i', strtotime($item['created_at'])) : '-' }}
              </span>
              <span class="text-slate-300 dark:text-slate-700 hidden sm:inline">•</span>
              <!-- Order Number -->
              <span class="font-mono text-slate-400 dark:text-slate-500 hidden sm:inline">
                #{{ $item['order_number'] ?? $item['id'] }}
              </span>
            </div>

            <!-- Status Badge -->
            <span class="px-3 py-1 rounded-full text-[11px] font-bold border flex items-center gap-1.5 {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
              <i class="fa-solid {{ $badge['icon'] }}"></i>
              {{ $badge['label'] }}
            </span>
          </div>

          <!-- CARD BODY: PRODUCT PREVIEW & TOTAL -->
          <div class="p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            
            <!-- LEFT: PRODUCT INFORMATION -->
            <div class="flex items-start gap-4 flex-1 min-w-0">
              <!-- Item Icon Snapshot -->
              <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-500 border border-amber-500/20 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                <i class="fa-solid fa-wine-bottle"></i>
              </div>

              <div class="space-y-1 min-w-0 flex-1">
                @if($firstItem)
                  <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                    {{ $firstItem['product_name_snapshot'] ?? ($firstItem['product']['name'] ?? 'Produk Tipsy More') }}
                  </h3>
                  <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ $firstItem['quantity'] }} barang x Rp {{ number_format($firstItem['unit_price'] ?? 0, 0, ',', '.') }}
                  </p>
                @else
                  <h3 class="text-sm font-bold text-slate-900 dark:text-white">Rincian Produk Pesanan</h3>
                  <p class="text-xs text-slate-500">Lihat detail untuk daftar lengkap</p>
                @endif

                @if($remainingItemsCount > 0)
                  <span class="inline-block text-[11px] font-semibold text-amber-600 dark:text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-md mt-1">
                    +{{ $remainingItemsCount }} produk lainnya
                  </span>
                @endif
              </div>
            </div>

            <!-- RIGHT: TOTAL PRICE & ACTION BUTTON -->
            <div class="flex flex-row md:flex-col items-center md:items-end justify-between w-full md:w-auto pt-4 md:pt-0 border-t md:border-t-0 border-slate-100 dark:border-slate-800 gap-3">
              <div class="text-left md:text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Total Belanja</span>
                <span class="text-base font-black text-amber-600 dark:text-amber-400">
                  Rp {{ number_format($item['total_amount'] ?? 0, 0, ',', '.') }}
                </span>
              </div>

              <a href="{{ route('orders.show', $item['id']) }}" 
                 class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold rounded-xl text-xs transition-all shadow-md shadow-amber-500/10 flex items-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-map-location-dot"></i> Lihat Status & Tracking
              </a>
            </div>

          </div>

          <!-- CARD FOOTER: COURIER & WAYBILL INFO (IF AVAILABLE) -->
          @if(!empty($item['courier_company']) || !empty($item['waybill_number']))
            <div class="px-5 py-2.5 bg-slate-50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
              <span class="flex items-center gap-1.5">
                <i class="fa-solid fa-truck text-amber-500"></i>
                Pengiriman: <strong class="text-slate-700 dark:text-slate-300">{{ strtoupper($item['courier_company'] ?? 'Null') }} ({{ ucfirst($item['courier_type'] ?? 'Instant') }})</strong>
              </span>
              @if(!empty($item['waybill_number']))
                <span class="font-mono bg-slate-200 dark:bg-slate-800 px-2 py-0.5 rounded text-slate-800 dark:text-slate-200">
                  Resi: {{ $item['waybill_number'] }}
                </span>
              @endif
            </div>
          @endif

        </div>
      @empty
        <!-- EMPTY STATE DESIGN -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-12 text-center border border-slate-200 dark:border-slate-800 shadow-sm">
          <div class="w-20 h-20 bg-amber-500/10 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
            <i class="fa-solid fa-box-open"></i>
          </div>
          <h3 class="text-base font-bold text-slate-900 dark:text-white">Tidak Ada Pesanan Ditemukan</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">
            @if(request('status'))
              Belum ada transaksi dengan status <strong>"{{ ucfirst(str_replace('_', ' ', request('status'))) }}"</strong>.
            @else
              Anda belum melakukan transaksi pembelian produk apapun.
            @endif
          </p>
          <div class="mt-6">
            <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-md transition-all inline-flex items-center gap-2">
              <i class="fa-solid fa-store"></i> Jelajahi Produk Sekarang
            </a>
          </div>
        </div>
      @endforelse
    </div>

  </div>
@endsection