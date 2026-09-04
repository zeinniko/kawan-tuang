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
          ''                => ['label' => 'Semua Pesanan', 'icon' => 'fa-list'],
          'pending_payment' => ['label' => 'Menunggu Bayar', 'icon' => 'fa-clock'],
          'processing'      => ['label' => 'Diproses', 'icon' => 'fa-boxes-packing'],
          'shipped'         => ['label' => 'Dalam Pengiriman', 'icon' => 'fa-truck-fast'],
          'completed'       => ['label' => 'Selesai', 'icon' => 'fa-circle-check'],
          'cancelled'       => ['label' => 'Dibatalkan', 'icon' => 'fa-circle-xmark'],
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
    <div class="space-y-6">
      @forelse($orders as $item)
        @php
          $rawStatus = strtolower($item['status'] ?? 'pending_payment');
          $statusConfig = [
              'pending_payment' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400', 'border' => 'border-amber-500/20', 'label' => 'Menunggu Pembayaran', 'icon' => 'fa-clock'],
              'processing'      => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'border-blue-500/20', 'label' => 'Diproses Penjual', 'icon' => 'fa-box-open'],
              'shipped'         => ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-600 dark:text-indigo-400', 'border' => 'border-indigo-500/20', 'label' => 'Dalam Pengiriman', 'icon' => 'fa-motorcycle'],
              'delivering'      => ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-600 dark:text-indigo-400', 'border' => 'border-indigo-500/20', 'label' => 'Dalam Pengiriman', 'icon' => 'fa-motorcycle'],
              'completed'       => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400', 'border' => 'border-emerald-500/20', 'label' => 'Selesai', 'icon' => 'fa-circle-check'],
              'cancelled'       => ['bg' => 'bg-rose-500/10', 'text' => 'text-rose-600 dark:text-rose-400', 'border' => 'border-rose-500/20', 'label' => 'Dibatalkan', 'icon' => 'fa-circle-xmark'],
          ];
          $badge = $statusConfig[$rawStatus] ?? ['bg' => 'bg-slate-500/10', 'text' => 'text-slate-600 dark:text-slate-400', 'border' => 'border-slate-500/20', 'label' => ucfirst(str_replace('_', ' ', $rawStatus)), 'icon' => 'fa-info-circle'];

          $items = $item['items'] ?? [];
          $totalItemsCount = count($items);
          $displayLimit = 3; // Maksimal produk yang ditampilkan langsung di kartu
          $visibleItems = array_slice($items, 0, $displayLimit);
          $remainingCount = $totalItemsCount - $displayLimit;
        @endphp

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-amber-500/40 dark:hover:border-amber-500/30 transition-all overflow-hidden">
          
          <!-- CARD HEADER: STORE NAME, DATE & STATUS -->
          <div class="p-4 bg-slate-50/70 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 text-xs">
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

          <!-- CARD BODY: MULTI-PRODUCT LIST & SUMMARY -->
          <div class="p-5 flex flex-col lg:flex-row items-stretch justify-between gap-6">
            
            <!-- LEFT: PRODUCT LIST (MENAMPILKAN HINGGA 3 PRODUK) -->
            <div class="flex-1 min-w-0 space-y-3 divide-y divide-slate-100 dark:divide-slate-800/60">
              @forelse($visibleItems as $index => $prod)
                @php
                  $prodName = $prod['product_name_snapshot'] ?? $prod['product_name'] ?? ($prod['product']['name'] ?? 'Produk Tipsy More');
                  $unitPrice = $prod['unit_price'] ?? 0;
                  $qty = $prod['quantity'] ?? 1;
                  $subtotal = $prod['subtotal_price'] ?? $prod['subtotal'] ?? ($unitPrice * $qty);
                  $thumbnail = $prod['thumbnail_url'] ?? $prod['product']['image_url'] ?? null;
                @endphp

                <div class="{{ $index > 0 ? 'pt-3' : '' }} flex items-center gap-3.5">
                  <!-- Foto / Thumbnail Produk -->
                  <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if(!empty($thumbnail))
                      <img src="{{ $thumbnail }}" alt="{{ $prodName }}" class="w-full h-full object-cover">
                    @else
                      <i class="fa-solid fa-wine-bottle text-amber-500 text-lg"></i>
                    @endif
                  </div>

                  <!-- Informasi Produk -->
                  <div class="flex-1 min-w-0">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate" title="{{ $prodName }}">
                      {{ $prodName }}
                    </h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                      {{ $qty }} barang x <span class="font-medium text-slate-700 dark:text-slate-300">Rp {{ number_format($unitPrice, 0, ',', '.') }}</span>
                    </p>
                  </div>

                  <!-- Subtotal Per Produk (Desktop View) -->
                  <div class="text-right hidden sm:block">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                      Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </span>
                  </div>
                </div>
              @empty
                <div class="flex items-center gap-3">
                  <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-amber-500">
                    <i class="fa-solid fa-wine-bottle"></i>
                  </div>
                  <div>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">Rincian Produk Pesanan</h4>
                    <p class="text-[11px] text-slate-400">Klik detail untuk melihat item produk</p>
                  </div>
                </div>
              @endforelse

              <!-- INDIKATOR JIKA PRODUK LEBIH DARI 3 -->
              @if($remainingCount > 0)
                <div class="pt-2">
                  <a href="{{ route('orders.show', $item['id']) }}" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-600 dark:text-amber-400 hover:underline">
                    <i class="fa-solid fa-layer-group"></i>
                    +{{ $remainingCount }} produk lainnya dalam pesanan ini
                  </a>
                </div>
              @endif
            </div>

            <!-- RIGHT: TOTAL HARGA & TOMBOL AKSI -->
            <div class="flex flex-row lg:flex-col items-center lg:items-end justify-between w-full lg:w-52 pt-4 lg:pt-0 border-t lg:border-t-0 lg:border-l border-slate-100 dark:border-slate-800 lg:pl-6 gap-3 flex-shrink-0">
              <div class="text-left lg:text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Total Belanja</span>
                <span class="text-base font-black text-amber-600 dark:text-amber-400">
                  Rp {{ number_format($item['total_amount'] ?? 0, 0, ',', '.') }}
                </span>
                <span class="text-[10px] text-slate-400 block mt-0.5">({{ $totalItemsCount }} {{ $totalItemsCount > 1 ? 'Produk' : 'Item' }})</span>
              </div>

              <a href="{{ route('orders.show', $item['id']) }}" 
                 class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold rounded-xl text-xs transition-all shadow-md shadow-amber-500/10 flex items-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-map-location-dot"></i> Detail Pesanan
              </a>
            </div>

          </div>

          <!-- CARD FOOTER: COURIER & WAYBILL INFO (JIKA TERSEDIA) -->
          @if(!empty($item['courier_company']) || !empty($item['waybill_number']))
            <div class="px-5 py-2.5 bg-slate-50/80 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
              <span class="flex items-center gap-1.5">
                <i class="fa-solid fa-truck text-amber-500"></i>
                Pengiriman: <strong class="text-slate-700 dark:text-slate-300">{{ strtoupper($item['courier_company'] ?? 'Kurir') }} ({{ ucfirst($item['courier_type'] ?? 'Reguler') }})</strong>
              </span>
              @if(!empty($item['waybill_number']))
                <span class="font-mono bg-slate-200/70 dark:bg-slate-800 px-2 py-0.5 rounded text-slate-800 dark:text-slate-200">
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