@extends('welcome')

@section('title', 'Katalog Produk Lengkap - Tipsy More')

@section('content')

@php
// 1. Tentukan toko yang aktif secara dinamis berdasarkan URL request('store_id')
$currentStoreId = request('store_id');
$activeStore = collect($stores)->first(function($s) use ($currentStoreId) {
    return data_get($s, 'id') == $currentStoreId;
}) ?? $selectedStore ?? collect($stores)->first();

$activeStoreId = data_get($activeStore, 'id');

// 2. Filter produk agar hanya menampilkan produk yang stoknya > 0 di toko terpilih ($activeStoreId)
$availableProducts = collect($products)->filter(function($product) use ($activeStoreId) {
    $storeStocks = data_get($product, 'store_stocks', []);
    
    // Cari stok di toko yang sedang aktif (menggunakan perbandingan longgar ==)
    $currentStoreStock = collect($storeStocks)->first(function($stock) use ($activeStoreId) {
        return data_get($stock, 'store_id') == $activeStoreId;
    });

    $stockVal = $currentStoreStock 
        ? (int) data_get($currentStoreStock, 'stock', 0) 
        : (int) data_get($product, 'stock', 0);

    return $stockVal > 0;
});
@endphp

<!-- BREADCRUMB & PAGE HEADER -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
  <nav class="flex text-xs text-slate-500 dark:text-slate-400 gap-2 mb-2">
    <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
    <span>/</span>
    <span class="text-slate-900 dark:text-slate-200 font-medium">Katalog Produk</span>
  </nav>

  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white">Katalog Produk</h1>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Temukan minuman favoritmu dari koleksi terlengkap 100% Original</p>
    </div>

    <!-- STORE DROPDOWN (RICH DETAILS MENU) & VIEW SWITCHER -->
    <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-between sm:justify-end">

      <!-- STORE DROPDOWN WIDGET -->
      <div class="relative flex-1 sm:flex-initial min-w-0" id="storeDropdownContainer">

        <!-- Tombol Trigger Dropdown Toko (Menampilkan $activeStore secara dinamis) -->
        <button type="button" onclick="toggleStoreDropdown(event)"
          class="w-full sm:w-auto min-w-[200px] max-w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-amber-500 rounded-2xl px-3 py-2 text-left transition-all shadow-xs flex items-center justify-between gap-2.5 group cursor-pointer">

          <div class="flex items-center gap-2.5 min-w-0 flex-1">
            <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-slate-950 transition-colors">
              <i class="fa-solid fa-store text-xs"></i>
            </div>

            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-1.5 leading-none mb-1">
                <span class="text-xs font-bold text-slate-900 dark:text-white truncate">
                  {{ data_get($activeStore, 'name', 'Pilih Toko') }}
                </span>
                @php
                $distVal = data_get($activeStore, 'distance_text', data_get($activeStore, 'distance_km') ? data_get($activeStore, 'distance_km').' km' : null);
                @endphp
                @if($distVal)
                <span class="text-[9px] bg-amber-500/15 text-amber-700 dark:text-amber-300 font-extrabold px-1.5 py-0.5 rounded-full shrink-0">
                  <i class="fa-solid fa-location-arrow text-[8px] mr-0.5"></i>{{ $distVal }}
                </span>
                @endif
              </div>

              <div class="text-[10px] text-slate-400 leading-none truncate flex items-center gap-1">
                <span class="shrink-0"><i class="fa-regular fa-clock mr-0.5"></i>{{ data_get($activeStore, 'open_time', '09:00') }}-{{ data_get($activeStore, 'close_time', '23:00') }}</span>
                @if(data_get($activeStore, 'address'))
                <span class="shrink-0">•</span>
                <span class="truncate">{{ data_get($activeStore, 'address') }}</span>
                @endif
              </div>
            </div>
          </div>

          <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] shrink-0 transition-transform group-hover:text-amber-500"></i>
        </button>

        <!-- Menu List Toko Popover -->
        <div id="storeDropdownMenu" class="hidden absolute left-0 sm:right-0 sm:left-auto mt-2 w-72 sm:w-80 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-2 shadow-2xl z-50 max-h-80 overflow-y-auto space-y-1 scrollbar-hide">
          <div class="px-2.5 py-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Pilih Outlet Toko</span>
            <span class="text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-full font-bold">{{ count($stores) }} Outlet</span>
          </div>

          @foreach($stores as $store)
          @php
          $stId = data_get($store, 'id');
          $stName = data_get($store, 'name');
          $stAddr = data_get($store, 'address');
          $stOpen = data_get($store, 'open_time', '09:00');
          $stClose = data_get($store, 'close_time', '23:00');
          $stDist = data_get($store, 'distance_text', data_get($store, 'distance_km') ? data_get($store, 'distance_km').' km' : null);
          $isSelected = $activeStoreId == $stId;
          @endphp

          <div onclick="selectStoreDropdown('{{ $stId }}')"
            class="p-2.5 rounded-xl transition-all cursor-pointer flex items-start justify-between gap-2.5 {{ $isSelected ? 'bg-amber-500/10 border border-amber-500/30' : 'hover:bg-slate-50 dark:hover:bg-slate-800/60' }}">

            <div class="space-y-1 min-w-0 flex-1">
              <div class="flex items-center gap-1.5">
                <span class="text-xs font-bold {{ $isSelected ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white' }} truncate">
                  {{ $stName }}
                </span>
                @if($isSelected)
                <span class="text-[9px] bg-amber-500 text-slate-950 font-black px-1.5 py-0.5 rounded-md">Aktif</span>
                @endif
              </div>

              @if($stAddr)
              <p class="text-[10px] text-slate-500 dark:text-slate-400 line-clamp-1 flex items-center gap-1">
                <i class="fa-solid fa-location-dot text-[9px] text-slate-400 shrink-0"></i>
                <span class="truncate">{{ $stAddr }}</span>
              </p>
              @endif

              <div class="flex items-center gap-2 text-[10px] text-slate-400">
                <span><i class="fa-regular fa-clock mr-0.5"></i>{{ $stOpen }} - {{ $stClose }}</span>
                @if($stDist)
                <span>•</span>
                <span class="text-amber-600 dark:text-amber-400 font-semibold">
                  <i class="fa-solid fa-location-arrow mr-0.5"></i>{{ $stDist }}
                </span>
                @endif
              </div>
            </div>

            @if($isSelected)
            <i class="fa-solid fa-circle-check text-amber-500 text-xs mt-0.5 shrink-0"></i>
            @endif
          </div>
          @endforeach
        </div>
      </div>

      <!-- View Switcher Icons -->
      <div class="flex items-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-1 gap-1 shadow-xs shrink-0">
        <button type="button" id="btn-view-grid" onclick="switchCatalogView('grid')" title="Tampilan Grid Card"
          class="w-8 h-8 rounded-xl flex items-center justify-center text-xs transition-colors bg-amber-500 text-slate-950 font-bold">
          <i class="fa-solid fa-border-all"></i>
        </button>
        <button type="button" id="btn-view-list" onclick="switchCatalogView('list')" title="Tampilan ListTile"
          class="w-8 h-8 rounded-xl flex items-center justify-center text-xs transition-colors text-slate-400 hover:text-slate-900 dark:hover:text-white">
          <i class="fa-solid fa-list-ul"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- FILTER & CATALOG CONTAINER -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">

  <!-- SEARCH BAR & POPOVER FILTER WIDGET -->
  <div class="relative flex items-center gap-2 w-full">
    <!-- SEARCH FORM -->
    <form action="{{ route('catalog.index') }}" method="GET" class="flex-1 flex items-center gap-2">
      <input type="hidden" name="store_id" value="{{ $activeStoreId }}">
      @foreach(request()->except(['search', 'page', 'store_id']) as $key => $val)
      @if(!is_null($val) && $val !== '')
      <input type="hidden" name="{{ $key }}" value="{{ $val }}">
      @endif
      @endforeach

      <div class="relative flex-1">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari beer, wine, whiskey, gin..."
          class="w-full bg-white dark:bg-slate-900 text-xs sm:text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-2.5 pl-10 pr-10 border border-slate-200 dark:border-slate-800 outline-none focus:border-amber-500 transition-all shadow-sm">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>

        @if(request('search'))
        <a href="{{ route('catalog.index', request()->except(['search', 'page'])) }}" class="absolute right-3.5 top-3 text-slate-400 hover:text-amber-500">
          <i class="fa-solid fa-xmark text-sm"></i>
        </a>
        @endif
      </div>

      <!-- TOMBOL CARI -->
      <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-2xl transition-all shadow-sm shrink-0 flex items-center gap-1.5 cursor-pointer">
        <i class="fa-solid fa-magnifying-glass text-xs"></i>
        <span class="hidden sm:inline">Cari</span>
      </button>
    </form>

    <!-- POPOVER FILTER & SORT BUTTON WIDGET -->
    <div class="relative shrink-0">
      <button type="button" id="filterPopoverBtn" onclick="toggleFilterMenu(event)"
        class="px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-amber-500 text-slate-800 dark:text-slate-200 rounded-2xl text-xs font-bold transition-all shadow-sm flex items-center gap-2 cursor-pointer">
        <i class="fa-solid fa-sliders text-amber-500"></i>
        <span class="hidden sm:inline">Filter & Urutkan</span>
        @if(request()->hasAny(['category', 'min_abv', 'max_abv', 'sort_by']))
        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
        @endif
      </button>

      <!-- DROPDOWN MENU POPOVER (NON-MODAL) -->
      <div id="filterPopoverMenu" class="hidden absolute right-0 mt-2 w-72 sm:w-80 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xl z-30 space-y-4">

        <!-- Popover Header -->
        <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
          <span class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
            <i class="fa-solid fa-filter text-amber-500"></i> Filter Katalog
          </span>
          @if(request()->hasAny(['category', 'min_abv', 'max_abv', 'sort_by']))
          <a href="{{ route('catalog.index', ['store_id' => $activeStoreId, 'search' => request('search')]) }}" class="text-[11px] text-amber-600 dark:text-amber-400 font-bold hover:underline">
            Reset Filter
          </a>
          @endif
        </div>

        <form action="{{ route('catalog.index') }}" method="GET" class="space-y-4">
          <input type="hidden" name="store_id" value="{{ $activeStoreId }}">
          @if(request('search'))
          <input type="hidden" name="search" value="{{ request('search') }}">
          @endif

          <!-- 1. SORTING -->
          <div>
            <label class="block text-[11px] uppercase font-bold text-slate-400 tracking-wider mb-1">Urutan Produk:</label>
            <select name="sort_by" class="w-full bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-800 rounded-xl px-2.5 py-2 outline-none focus:border-amber-500">
              <option value="latest" {{ request('sort_by', 'latest') == 'latest' ? 'selected' : '' }}>Varian Terbaru</option>
              <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tinggi</option>
              <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Rendah</option>
            </select>
          </div>

          <!-- 2. KATEGORI -->
          <div>
            <label class="block text-[11px] uppercase font-bold text-slate-400 tracking-wider mb-1">Kategori Minuman:</label>
            <select name="category" class="w-full bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-800 rounded-xl px-2.5 py-2 outline-none focus:border-amber-500">
              <option value="">Semua Kategori</option>
              @foreach($categories as $category)
              @php
              $cSlug = data_get($category, 'slug', data_get($category, 'data.slug'));
              $cName = data_get($category, 'name', data_get($category, 'data.name'));
              @endphp
              <option value="{{ $cSlug }}" {{ request('category') == $cSlug ? 'selected' : '' }}>
                {{ $cName }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- 3. KANDUNGAN ALKOHOL (ABV) -->
          <div>
            <label class="block text-[11px] uppercase font-bold text-slate-400 tracking-wider mb-1">Kandungan Alkohol (ABV):</label>
            <div class="grid grid-cols-3 gap-1.5">
              <button type="button" onclick="applyAbvFilter('', 5)" class="py-1.5 px-2 rounded-xl border text-[11px] font-medium text-center transition-all cursor-pointer {{ request('max_abv') == 5 && !request('min_abv') ? 'bg-amber-500 text-slate-950 font-bold border-amber-500' : 'bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-800' }}">
                &lt; 5%
              </button>
              <button type="button" onclick="applyAbvFilter(5, 15)" class="py-1.5 px-2 rounded-xl border text-[11px] font-medium text-center transition-all cursor-pointer {{ request('min_abv') == 5 && request('max_abv') == 15 ? 'bg-amber-500 text-slate-950 font-bold border-amber-500' : 'bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-800' }}">
                5-15%
              </button>
              <button type="button" onclick="applyAbvFilter(15, '')" class="py-1.5 px-2 rounded-xl border text-[11px] font-medium text-center transition-all cursor-pointer {{ request('min_abv') == 15 && !request('max_abv') ? 'bg-amber-500 text-slate-950 font-bold border-amber-500' : 'bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-800' }}">
                &gt; 15%
              </button>
            </div>
            <input type="hidden" name="min_abv" id="popover_min_abv" value="{{ request('min_abv') }}">
            <input type="hidden" name="max_abv" id="popover_max_abv" value="{{ request('max_abv') }}">
          </div>

          <button type="submit" class="w-full py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl transition-all shadow-sm cursor-pointer">
            Terapkan Filter
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- CATALOG PRODUCT CONTAINER -->
  <!-- 1. GRID CARD MODE (Default) -->
  <div id="catalog-grid-view" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 pt-2">
    @forelse($availableProducts as $product)
    @php
    $prodId = data_get($product, 'id');
    $prodName = data_get($product, 'name');
    $prodSlug = data_get($product, 'slug');
    $prodImg = data_get($product, 'thumbnail_url', data_get($product, 'primary_image.image_url', 'https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80'));
    $prodPrice = (float) data_get($product, 'price', 0);
    $prodStrike = data_get($product, 'strike_price');
    $prodCategory = data_get($product, 'category.name', 'Minuman');
    $prodAbv = data_get($product, 'alcohol_percentage', data_get($product, 'abv', 0));
    $isColdReady = (bool) data_get($product, 'is_cold_ready', false);
    @endphp

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-3 sm:p-4 border border-slate-200/80 dark:border-slate-800 hover:border-amber-500/50 transition-all group flex flex-col justify-between shadow-sm">
      <div>
        <a href="{{ route('catalog.show', $prodSlug) }}" class="relative aspect-square rounded-2xl bg-slate-50 dark:bg-slate-950 flex items-center justify-center overflow-hidden mb-3">
          <img src="{{ $prodImg }}" alt="{{ $prodName }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">

          @if($isColdReady)
          <span class="absolute top-2 left-2 bg-blue-500/90 text-white text-[9px] font-extrabold px-2 py-0.5 rounded-full flex items-center gap-1 backdrop-blur-xs">
            <i class="fa-solid fa-snowflake text-[8px]"></i> Cold
          </span>
          @endif
        </a>

        <div class="flex items-center justify-between mb-1">
          <span class="text-[10px] uppercase font-bold text-amber-600 dark:text-amber-400 tracking-wider truncate">
            {{ $prodCategory }}
          </span>
          <span class="text-[10px] text-slate-400 font-semibold shrink-0">{{ $prodAbv }}% ABV</span>
        </div>

        <a href="{{ route('catalog.show', $prodSlug) }}" class="block">
          <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white line-clamp-2 hover:text-amber-500 transition-colors leading-snug">
            {{ $prodName }}
          </h3>
        </a>
      </div>

      <div class="mt-3 pt-2 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
        <div>
          @if($prodStrike)
          <p class="text-[10px] text-slate-400 line-through">Rp {{ number_format((float)$prodStrike, 0, ',', '.') }}</p>
          @endif
          <p class="text-xs sm:text-sm font-black text-amber-600 dark:text-amber-400">
            Rp {{ number_format($prodPrice, 0, ',', '.') }}
          </p>
        </div>

        <form action="{{ route('cart.store') }}" method="POST">
          @csrf
          <input type="hidden" name="product_id" value="{{ $prodId }}">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="w-8 h-8 rounded-xl bg-amber-500/10 hover:bg-amber-500 text-amber-600 hover:text-slate-950 dark:text-amber-400 flex items-center justify-center transition-colors cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
          </button>
        </form>
      </div>
    </div>
    @empty
    <div class="col-span-full py-16 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6">
      <div class="w-12 h-12 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-3 text-xl">
        <i class="fa-solid fa-wine-bottle"></i>
      </div>
      <h3 class="text-sm font-bold text-slate-900 dark:text-white">Produk Tidak Tersedia</h3>
      <p class="text-xs text-slate-500 mt-1 mb-4">Maaf, tidak ada minuman yang tersedia di toko terpilih saat ini.</p>
      <a href="{{ route('catalog.index', ['store_id' => $activeStoreId]) }}" class="inline-block bg-amber-500 text-slate-950 font-bold px-5 py-2.5 rounded-2xl text-xs">
        Reset Semua Filter
      </a>
    </div>
    @endforelse
  </div>

  <!-- 2. LISTTILE MODE (Tampilan Horizontal List) -->
  <div id="catalog-list-view" class="hidden space-y-3 pt-2">
    @forelse($availableProducts as $product)
    @php
    $prodId = data_get($product, 'id');
    $prodName = data_get($product, 'name');
    $prodSlug = data_get($product, 'slug');
    $prodImg = data_get($product, 'thumbnail_url', data_get($product, 'primary_image.image_url', 'https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80'));
    $prodPrice = (float) data_get($product, 'price', 0);
    $prodCategory = data_get($product, 'category.name', 'Minuman');
    $prodAbv = data_get($product, 'alcohol_percentage', data_get($product, 'abv', 0));
    $prodDesc = data_get($product, 'description', 'Minuman original berkualitas terbaik.');
    @endphp

    <div class="bg-white dark:bg-slate-900 rounded-2xl p-3 sm:p-4 border border-slate-200/80 dark:border-slate-800 hover:border-amber-500/50 transition-all group flex items-center justify-between gap-3 sm:gap-5 shadow-sm">

      <!-- Image Container -->
      <a href="{{ route('catalog.show', $prodSlug) }}" class="shrink-0 w-20 h-20 sm:w-28 sm:h-28 rounded-xl bg-slate-50 dark:bg-slate-950 flex items-center justify-center overflow-hidden relative">
        <img src="{{ $prodImg }}" alt="{{ $prodName }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
      </a>

      <!-- Content Middle Info -->
      <div class="flex-1 min-w-0 space-y-1">
        <div class="flex items-center gap-2">
          <span class="text-[10px] uppercase font-bold text-amber-600 dark:text-amber-400 tracking-wider">
            {{ $prodCategory }}
          </span>
          <span class="text-slate-300 dark:text-slate-700">•</span>
          <span class="text-[10px] text-slate-400 font-semibold">{{ $prodAbv }}% ABV</span>
        </div>

        <a href="{{ route('catalog.show', $prodSlug) }}" class="block">
          <h3 class="text-xs sm:text-base font-bold text-slate-900 dark:text-white truncate hover:text-amber-500 transition-colors">
            {{ $prodName }}
          </h3>
        </a>

        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 hidden sm:block">
          {{ $prodDesc }}
        </p>

        <p class="text-xs sm:text-base font-black text-amber-600 dark:text-amber-400 sm:hidden pt-0.5">
          Rp {{ number_format($prodPrice, 0, ',', '.') }}
        </p>
      </div>

      <!-- Right Side: Price & Cart Button -->
      <div class="flex flex-col items-end justify-between gap-2 shrink-0">
        <p class="text-sm sm:text-base font-black text-amber-600 dark:text-amber-400 hidden sm:block">
          Rp {{ number_format($prodPrice, 0, ',', '.') }}
        </p>

        <form action="{{ route('cart.store') }}" method="POST">
          @csrf
          <input type="hidden" name="product_id" value="{{ $prodId }}">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="px-3 py-2 rounded-xl bg-amber-500/10 hover:bg-amber-500 text-amber-600 hover:text-slate-950 dark:text-amber-400 text-xs font-bold flex items-center gap-1.5 transition-colors cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            <span class="hidden sm:inline">Beli</span>
          </button>
        </form>
      </div>

    </div>
    @empty
    <div class="col-span-full py-16 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6">
      <div class="w-12 h-12 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-3 text-xl">
        <i class="fa-solid fa-wine-bottle"></i>
      </div>
      <h3 class="text-sm font-bold text-slate-900 dark:text-white">Produk Tidak Tersedia</h3>
      <p class="text-xs text-slate-500 mt-1 mb-4">Maaf, tidak ada minuman yang tersedia di toko terpilih saat ini.</p>
      <a href="{{ route('catalog.index', ['store_id' => $activeStoreId]) }}" class="inline-block bg-amber-500 text-slate-950 font-bold px-5 py-2.5 rounded-2xl text-xs">
        Reset Semua Filter
      </a>
    </div>
    @endforelse
  </div>

</div>

@push('scripts')
<script>
  // Grid/List View Switcher
  function switchCatalogView(mode) {
    const gridView = document.getElementById('catalog-grid-view');
    const listView = document.getElementById('catalog-list-view');
    const btnGrid = document.getElementById('btn-view-grid');
    const btnList = document.getElementById('btn-view-list');

    if (mode === 'list') {
      gridView.classList.add('hidden');
      listView.classList.remove('hidden');

      btnList.className = "w-8 h-8 rounded-xl flex items-center justify-center text-xs transition-colors bg-amber-500 text-slate-950 font-bold";
      btnGrid.className = "w-8 h-8 rounded-xl flex items-center justify-center text-xs transition-colors text-slate-400 hover:text-slate-900 dark:hover:text-white";

      localStorage.setItem('kt_catalog_view', 'list');
    } else {
      listView.classList.add('hidden');
      gridView.classList.remove('hidden');

      btnGrid.className = "w-8 h-8 rounded-xl flex items-center justify-center text-xs transition-colors bg-amber-500 text-slate-950 font-bold";
      btnList.className = "w-8 h-8 rounded-xl flex items-center justify-center text-xs transition-colors text-slate-400 hover:text-slate-900 dark:hover:text-white";

      localStorage.setItem('kt_catalog_view', 'grid');
    }
  }

  // Store Dropdown Popover Handlers
  function toggleStoreDropdown(event) {
    event.stopPropagation();
    const menu = document.getElementById('storeDropdownMenu');
    menu.classList.toggle('hidden');
  }

  // Mengubah toko secara langsung lewat URL searchParams
  function selectStoreDropdown(storeId) {
    const url = new URL(window.location.href);
    url.searchParams.set('store_id', storeId);
    url.searchParams.delete('page'); // Reset halaman pagination ke 1
    window.location.href = url.toString();
  }

  // Filter Popover Handlers
  function toggleFilterMenu(event) {
    event.stopPropagation();
    const menu = document.getElementById('filterPopoverMenu');
    menu.classList.toggle('hidden');
  }

  function applyAbvFilter(min, max) {
    document.getElementById('popover_min_abv').value = min;
    document.getElementById('popover_max_abv').value = max;
  }

  // Close menus when clicking outside
  document.addEventListener('click', function(event) {
    // Store dropdown click outside
    const storeContainer = document.getElementById('storeDropdownContainer');
    const storeMenu = document.getElementById('storeDropdownMenu');
    if (storeMenu && !storeMenu.classList.contains('hidden') && storeContainer && !storeContainer.contains(event.target)) {
      storeMenu.classList.add('hidden');
    }

    // Filter popover click outside
    const filterMenu = document.getElementById('filterPopoverMenu');
    const filterBtn = document.getElementById('filterPopoverBtn');
    if (filterMenu && !filterMenu.classList.contains('hidden') && !filterMenu.contains(event.target) && !filterBtn.contains(event.target)) {
      filterMenu.classList.add('hidden');
    }
  });

  // Load saved view state on page load
  document.addEventListener('DOMContentLoaded', () => {
    const savedView = localStorage.getItem('kt_catalog_view') || 'grid';
    switchCatalogView(savedView);
  });
</script>
@endpush
@endsection