@extends('welcome')

@section('title', 'Status Pesanan & Tracking - Kawan Tuang')

@section('content')
  <!-- MAIN CONTAINER -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- ORDER HISTORY TABS -->
    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-3 mb-6 overflow-x-auto scrollbar-none">
      <button class="px-4 py-2 rounded-xl bg-amber-500 dark:bg-amber-400 text-slate-950 font-bold text-xs whitespace-nowrap shadow-md shadow-amber-500/10">
        Berlangsung (1)
      </button>
      <button class="px-4 py-2 rounded-xl bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold whitespace-nowrap transition-colors">
        Selesai (8)
      </button>
      <button class="px-4 py-2 rounded-xl bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold whitespace-nowrap transition-colors">
        Dibatalkan (0)
      </button>
    </div>

    <!-- ACTIVE ORDER SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

      <!-- LEFT COLUMN: STATUS & DRIVER INFO (7 Cols) -->
      <div class="lg:col-span-7 space-y-6">

        <!-- ORDER HEADER CARD -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
          <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
              <span class="text-[10px] text-slate-400 uppercase tracking-wider block">ID Pesanan</span>
              <span class="text-sm font-extrabold text-slate-900 dark:text-white font-mono">#TT-89302194</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="px-3 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 text-xs font-bold rounded-full flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span> Dalam Pengiriman
              </span>
            </div>
          </div>

          <!-- STEPPER STATUS TRACKER -->
          <div class="py-6">
            <div class="relative flex items-center justify-between">
              <!-- Line Connector Background -->
              <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 w-full bg-slate-200 dark:bg-slate-800 z-0"></div>
              <!-- Active Line Connector -->
              <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 w-3/4 bg-amber-500 z-0"></div>

              <!-- Step 1: Diterima -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full bg-amber-500 text-slate-950 font-bold flex items-center justify-center text-xs shadow-md">
                  <i class="fa-solid fa-check"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-900 dark:text-white mt-2">Diterima</span>
                <span class="text-[9px] text-slate-400">14:10</span>
              </div>

              <!-- Step 2: Diproses -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full bg-amber-500 text-slate-950 font-bold flex items-center justify-center text-xs shadow-md">
                  <i class="fa-solid fa-check"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-900 dark:text-white mt-2">Diproses</span>
                <span class="text-[9px] text-slate-400">14:15</span>
              </div>

              <!-- Step 3: Dikirim (Active) -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full bg-amber-500 text-slate-950 font-bold flex items-center justify-center text-xs shadow-lg shadow-amber-500/30 ring-4 ring-amber-500/20">
                  <i class="fa-solid fa-motorcycle"></i>
                </div>
                <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 mt-2">Dikirim</span>
                <span class="text-[9px] text-amber-500 font-semibold">14:28</span>
              </div>

              <!-- Step 4: Selesai -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-400 font-bold flex items-center justify-center text-xs">
                  <i class="fa-solid fa-house"></i>
                </div>
                <span class="text-[10px] font-medium text-slate-400 mt-2">Selesai</span>
                <span class="text-[9px] text-slate-400">--:--</span>
              </div>
            </div>
          </div>

          <!-- ESTIMATED ARRIVAL -->
          <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <i class="fa-solid fa-clock text-amber-500 text-base"></i>
              <div>
                <span class="text-xs font-bold text-slate-900 dark:text-white block">Estimasi Tiba: 15-20 Menit</span>
                <span class="text-[11px] text-slate-500 dark:text-slate-400">Driver sedang menuju lokasi pengiriman</span>
              </div>
            </div>
            <span class="text-xs font-bold text-amber-600 dark:text-amber-400">Gojek Instant</span>
          </div>
        </div>

        <!-- LIVE MAP TRACKING MOCKUP -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
          <div class="p-3 bg-slate-100 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-2">
              <i class="fa-solid fa-map-location-dot text-amber-500"></i> Peta Lokasi Kurir
            </span>
            <span class="text-[10px] bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold px-2 py-0.5 rounded">GPS Aktif</span>
          </div>
          <!-- Map Visual Representation -->
          <div class="relative h-56 bg-slate-200 dark:bg-slate-950 flex items-center justify-center overflow-hidden">
            <!-- Grid lines background for map look -->
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:16px_16px]"></div>

            <!-- Route line visual -->
            <svg class="absolute inset-0 w-full h-full stroke-amber-500" stroke-width="3" stroke-dasharray="6,6">
              <path d="M 80 160 Q 180 80 320 120" fill="none" />
            </svg>

            <!-- Store Marker -->
            <div class="absolute left-16 bottom-10 flex flex-col items-center">
              <div class="w-8 h-8 rounded-full bg-slate-900 text-amber-400 flex items-center justify-center text-xs font-bold shadow-lg border border-amber-500">
                KT
              </div>
              <span class="text-[9px] font-bold bg-slate-900/90 text-white px-2 py-0.5 rounded mt-1 shadow">Toko</span>
            </div>

            <!-- Driver Marker (Animated) -->
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center animate-bounce">
              <div class="w-10 h-10 rounded-full bg-amber-500 text-slate-950 flex items-center justify-center text-sm font-bold shadow-xl ring-4 ring-amber-500/30">
                <i class="fa-solid fa-motorcycle"></i>
              </div>
              <span class="text-[9px] font-bold bg-amber-500 text-slate-950 px-2 py-0.5 rounded mt-1 shadow">Driver (500m)</span>
            </div>

            <!-- Destination Marker -->
            <div class="absolute right-16 top-16 flex flex-col items-center">
              <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center text-xs font-bold shadow-lg">
                <i class="fa-solid fa-location-dot"></i>
              </div>
              <span class="text-[9px] font-bold bg-slate-900/90 text-white px-2 py-0.5 rounded mt-1 shadow">Lokasi Anda</span>
            </div>
          </div>
        </div>

        <!-- DRIVER INFORMATION CARD -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-amber-500/20 text-amber-500 flex items-center justify-center font-bold text-lg border border-amber-500/30">
              <i class="fa-solid fa-user-ninja"></i>
            </div>
            <div>
              <h4 class="text-xs font-bold text-slate-900 dark:text-white">Budi Santoso</h4>
              <p class="text-[11px] text-slate-500 dark:text-slate-400">Gojek Instant • Honda Vario (B 1234 XYZ)</p>
              <div class="flex items-center gap-1 mt-0.5">
                <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300">4.9</span>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <a href="tel:0812345678" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-emerald-500 hover:text-white text-slate-700 dark:text-slate-200 flex items-center justify-center transition-colors" title="Telepon Driver">
              <i class="fa-solid fa-phone text-sm"></i>
            </a>
            <a href="#" class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 font-bold flex items-center justify-center hover:bg-amber-600 transition-colors" title="Chat Driver">
              <i class="fa-solid fa-comment-dots text-sm"></i>
            </a>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: ITEM DETAILS & RECEIPT (5 Cols) -->
      <div class="lg:col-span-5 space-y-6">

        <!-- RINGKASAN ITEMS -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 transition-colors">
          <h3 class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-receipt text-amber-500"></i> Detail Rincian Pesanan
          </h3>

          <div class="space-y-3">
            <!-- Item 1 -->
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-wine-bottle text-xl text-amber-500"></i>
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate">Craft Lager Premium 500ml</h4>
                <p class="text-[10px] text-slate-400">2x @ Rp 65.000</p>
              </div>
              <span class="text-xs font-bold text-slate-900 dark:text-white">Rp 130.000</span>
            </div>

            <!-- Item 2 -->
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-glass-water text-xl text-amber-500"></i>
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate">Cabernet Sauvignon 750ml</h4>
                <p class="text-[10px] text-slate-400">1x @ Rp 420.000</p>
              </div>
              <span class="text-xs font-bold text-slate-900 dark:text-white">Rp 420.000</span>
            </div>
          </div>

          <!-- PAYMENT SUMMARY -->
          <div class="border-t border-slate-200 dark:border-slate-800 pt-3 space-y-2 text-xs text-slate-600 dark:text-slate-400">
            <div class="flex justify-between">
              <span>Metode Pembayaran</span>
              <span class="font-bold text-slate-900 dark:text-white">QRIS (GoPay)</span>
            </div>
            <div class="flex justify-between">
              <span>Alamat Tujuan</span>
              <span class="font-medium text-slate-900 dark:text-white text-right max-w-[180px] truncate">Jl. Senopati No. 45</span>
            </div>
            <div class="flex justify-between">
              <span>Biaya Pengiriman</span>
              <span class="font-medium text-slate-900 dark:text-white">Rp 25.000</span>
            </div>
            <div class="flex justify-between text-amber-600 dark:text-amber-400 font-extrabold text-sm pt-2 border-t border-slate-100 dark:border-slate-800">
              <span>Total Akhir</span>
              <span>Rp 555.000</span>
            </div>
          </div>

          <!-- ACTION BUTTONS -->
          <div class="pt-2 space-y-2">
            <button class="w-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-900 dark:text-white font-bold py-2.5 rounded-xl text-xs transition-colors">
              <i class="fa-solid fa-file-invoice me-1"></i> Unduh Invoice PDF
            </button>
            <a href="{{ route('home') }}" class="block w-full bg-amber-500 hover:bg-amber-600 dark:bg-amber-400 dark:hover:bg-amber-500 text-slate-950 font-extrabold py-2.5 rounded-xl text-center text-xs transition-all shadow-md">
              Pesan Lagi
            </a>
          </div>
        </div>

      </div>

    </div>
  </div>
@endsection