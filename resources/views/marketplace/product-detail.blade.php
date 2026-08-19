@extends('welcome')

@section('title', ($product['name'] ?? 'Detail Produk') . ' - Kawan Tuang')

@section('content')
  <!-- BREADCRUMB -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <nav class="flex text-xs text-slate-500 dark:text-slate-400 gap-2">
      <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
      <span>/</span>
      <a href="{{ route('catalog.index', ['category' => $product['category']['slug'] ?? '']) }}" class="hover:underline">
        {{ $product['category']['name'] ?? 'Katalog' }}
      </a>
      <span>/</span>
      <span class="text-slate-900 dark:text-slate-200 font-medium truncate">{{ $product['name'] ?? 'Detail' }}</span>
    </nav>
  </div>

  <!-- PRODUCT DETAIL CONTENT -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

      <!-- LEFT: PRODUCT IMAGE -->
      <div class="space-y-4">
        <div class="relative aspect-square rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center p-8 shadow-sm overflow-hidden">
          <span class="absolute top-4 right-4 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-xs font-semibold px-2.5 py-1 rounded-lg z-10">
            <i class="fa-solid fa-circle-check me-1"></i>100% Original
          </span>
          <img src="{{ $product['image_url'] ?? 'https://placehold.co/600x600/0f172a/ffffff?text=Product' }}" 
               alt="{{ $product['name'] ?? 'Produk' }}" 
               class="w-full h-full object-contain drop-shadow-2xl">
        </div>
      </div>

      <!-- RIGHT: PRODUCT INFO & SPECS -->
      <div class="space-y-6">

        <!-- Category & Title -->
        <div>
          <span class="text-xs uppercase font-bold text-amber-600 dark:text-amber-400 tracking-wider">
            {{ $product['category']['name'] ?? 'Minuman Premium' }}
          </span>
          <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $product['name'] ?? 'Nama Produk' }}</h1>
        </div>

        <!-- Price Section -->
        <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
          <div class="flex items-baseline gap-2">
            <span class="text-2xl md:text-3xl font-black text-amber-600 dark:text-amber-400">
              Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}
            </span>
          </div>
          <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
            <i class="fa-solid fa-truck-fast text-amber-500 me-1"></i> Pengiriman kilat dengan suhu terjaga.
          </p>
        </div>

        <!-- ALCOHOL SPECS GRID -->
        <div>
          <h3 class="text-xs font-bold uppercase text-slate-400 mb-2">Spesifikasi Minuman</h3>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Kadar Alkohol</span>
              <span class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ $product['abv'] ?? 0 }}% ABV</span>
            </div>
            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Volume</span>
              <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $product['volume'] ?? '-' }} ml</span>
            </div>
            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Brand</span>
              <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $product['brand']['name'] ?? '-' }}</span>
            </div>
            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Stok</span>
              <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $product['stock'] ?? 'Tersedia' }}</span>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="space-y-2">
          <h3 class="text-sm font-bold text-slate-900 dark:text-white">Deskripsi Produk</h3>
          <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
            {{ $product['description'] ?? 'Tidak ada deskripsi produk.' }}
          </p>
        </div>

        <!-- DESKTOP ACTION BAR -->
        <form action="{{ route('cart.store') }}" method="POST" class="hidden md:block pt-4 border-t border-slate-200 dark:border-slate-800">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product['id'] ?? '' }}">

          <div class="flex items-center gap-4">
            <!-- Counter Kuantitas -->
            <div class="flex items-center bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-1">
              <button type="button" onclick="decrementQty('qty-input')" class="w-9 h-9 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg font-bold text-lg">-</button>
              <input type="number" id="qty-input" name="quantity" value="1" min="1" class="w-12 text-center bg-transparent text-sm font-bold text-slate-900 dark:text-white focus:outline-none">
              <button type="button" onclick="incrementQty('qty-input')" class="w-9 h-9 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg font-bold text-lg">+</button>
            </div>

            <!-- Tombol Keranjang -->
            <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 px-6 rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20">
              <i class="fa-solid fa-bag-shopping"></i> + Keranjang Belanja
            </button>
          </div>
        </form>

      </div>
    </div>
  </main>

  <!-- MOBILE STICKY BOTTOM ACTION BAR -->
  <div class="fixed bottom-14 sm:bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 md:hidden p-4">
    <form action="{{ route('cart.store') }}" method="POST" class="flex items-center justify-between gap-3 max-w-md mx-auto">
      @csrf
      <input type="hidden" name="product_id" value="{{ $product['id'] ?? '' }}">
      <input type="hidden" name="quantity" value="1">

      <div>
        <span class="text-[10px] text-slate-400 block">Total Harga</span>
        <span class="text-base font-extrabold text-amber-600 dark:text-amber-400">
          Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}
        </span>
      </div>

      <button type="submit" class="bg-amber-500 dark:bg-amber-400 text-slate-950 px-6 py-3 rounded-xl font-bold text-xs shadow-md">
        <i class="fa-solid fa-bag-shopping me-1"></i> Beli Sekarang
      </button>
    </form>
  </div>
@endsection

@push('scripts')
  <script>
    function incrementQty(inputId) {
      const input = document.getElementById(inputId);
      if (input) {
        input.value = parseInt(input.value || 1) + 1;
      }
    }

    function decrementQty(inputId) {
      const input = document.getElementById(inputId);
      if (input && parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
      }
    }
  </script>
@endpush