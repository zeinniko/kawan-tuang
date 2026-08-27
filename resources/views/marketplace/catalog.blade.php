@extends('welcome')

@section('title', 'Katalog Produk Lengkap - Tipsy More')

@section('content')
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

    <!-- SORTING DROPDOWN & VIEW SWITCHER -->
    <div class="flex items-center gap-1.5 sm:gap-3 w-full sm:w-auto justify-between sm:justify-end">
      <form action="{{ route('catalog.index') }}" method="GET" class="flex items-center gap-1.5 flex-1 sm:flex-initial">
        @foreach(request()->except('sort_by') as $key => $val)
        @if(!is_null($val) && $val !== '')
        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
        @endif
        @endforeach

        <!-- Teks 'Urutkan:' disembunyikan di mobile, tampil di desktop -->
        <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap font-medium hidden sm:inline">
          Urutkan:
        </span>

        <!-- Dropdown Select Otomatis Mengisi Ruang (w-full sm:w-auto) -->
        <select name="sort_by" onchange="this.form.submit()" class="w-full sm:w-auto bg-white dark:bg-slate-900 text-xs font-semibold text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-800 rounded-xl px-2.5 py-2 outline-none focus:border-amber-500 transition-colors cursor-pointer truncate">
          <option value="latest" {{ request('sort_by', 'latest') == 'latest' ? 'selected' : '' }}>Varian Terbaru</option>
          <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tinggi</option>
          <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Rendah</option>
        </select>
      </form>

      <!-- View Switcher Icons (Tetap Berada di Kanan) -->
      <div class="flex items-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-1 gap-1 shadow-sm shrink-0">
        <button type="button" id="btn-view-grid" onclick="switchCatalogView('grid')" title="Tampilan Grid Card"
          class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center text-xs transition-colors bg-amber-500 text-slate-950 font-bold">
          <i class="fa-solid fa-border-all"></i>
        </button>
        <button type="button" id="btn-view-list" onclick="switchCatalogView('list')" title="Tampilan ListTile"
          class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center text-xs transition-colors text-slate-400 hover:text-slate-900 dark:hover:text-white">
          <i class="fa-solid fa-list-ul"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- FILTER SECTION CONTAINER -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">

  <!-- SEARCH INPUT MOBILE & DESKTOP -->
  <form action="{{ route('catalog.index') }}" method="GET" class="relative">
    @foreach(request()->except(['search', 'page']) as $key => $val)
    @if(!is_null($val) && $val !== '')
    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
    @endif
    @endforeach
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari beer, wine, whiskey, gin..."
      class="w-full bg-white dark:bg-slate-900 text-xs sm:text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-10 pr-10 border border-slate-200 dark:border-slate-800 outline-none focus:border-amber-500 transition-all shadow-sm">
    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
    @if(request('search'))
    <a href="{{ route('catalog.index', request()->except(['search', 'page'])) }}" class="absolute right-3.5 top-3.5 text-slate-400 hover:text-amber-500">
      <i class="fa-solid fa-xmark text-sm"></i>
    </a>
    @endif
  </form>

  <!-- 1. FILTER KATEGORI (BARIS ATAS) -->
  <div class="space-y-1.5">
    <span class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Kategori Minuman:</span>

    <div class="flex items-center gap-2 w-full">
      <a href="{{ route('catalog.index', request()->except(['category', 'page'])) }}"
        class="shrink-0 px-4 py-2 rounded-xl text-xs font-medium transition-all {{ !request('category') ? 'bg-amber-500 text-slate-950 font-bold shadow-md shadow-amber-500/20' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:border-amber-500/50' }}">
        Semua Varian
      </a>

      <div class="h-6 w-[1px] bg-slate-200 dark:bg-slate-800 shrink-0"></div>

      <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide text-xs w-full">
        @php
        $sortedCategories = collect($categories)->sortBy(function($cat) {
        return data_get($cat, 'name', data_get($cat, 'data.name'));
        });
        @endphp

        @foreach($sortedCategories as $category)
        @php
        $catSlug = data_get($category, 'slug', data_get($category, 'data.slug'));
        $catName = data_get($category, 'name', data_get($category, 'data.name'));
        $isActive = request('category') === $catSlug;
        @endphp
        <a href="{{ route('catalog.index', array_merge(request()->except(['category', 'page']), ['category' => $catSlug])) }}"
          class="shrink-0 px-4 py-2 rounded-xl whitespace-nowrap font-medium transition-all {{ $isActive ? 'bg-amber-500 text-slate-950 font-bold shadow-md shadow-amber-500/20' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:border-amber-500/50' }}">
          {{ $catName }}
        </a>
        @endforeach
      </div>
    </div>
  </div>

  <!-- 2. FILTER KANDUNGAN ALKOHOL / ABV (BARIS BAWAH DIPISAH) -->
  <div class="pt-3 border-t border-slate-200/60 dark:border-slate-800/60 w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 w-full">
      <div class="flex items-center justify-between sm:justify-start gap-2 shrink-0">
        <span class="text-[11px] uppercase font-bold text-slate-400 tracking-wider whitespace-nowrap">
          Kandungan Alkohol (ABV):
        </span>

        @if(request()->has('min_abv') || request()->has('max_abv'))
        <a href="{{ route('catalog.index', request()->except(['min_abv', 'max_abv', 'page'])) }}" class="text-[11px] text-amber-600 dark:text-amber-400 font-bold hover:underline sm:hidden">
          Reset Filter
        </a>
        @endif
      </div>

      <div class="flex items-center gap-2 w-full sm:w-auto sm:flex-1 text-xs">
        <a href="{{ route('catalog.index', array_merge(request()->except(['min_abv', 'max_abv', 'page']), ['max_abv' => 5])) }}"
          class="flex-1 text-center py-2 px-1 sm:px-3 rounded-xl font-medium transition-all {{ request('max_abv') == 5 && !request('min_abv') ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:border-amber-400' }}">
          <span class="sm:hidden">&lt;5% ABV</span>
          <span class="hidden sm:inline">Ringan (&lt; 5% ABV)</span>
        </a>

        <a href="{{ route('catalog.index', array_merge(request()->except(['min_abv', 'max_abv', 'page']), ['min_abv' => 5, 'max_abv' => 15])) }}"
          class="flex-1 text-center py-2 px-1 sm:px-3 rounded-xl font-medium transition-all {{ request('min_abv') == 5 && request('max_abv') == 15 ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:border-amber-400' }}">
          <span class="sm:hidden">5-15% ABV</span>
          <span class="hidden sm:inline">Sedang (5% - 15% ABV)</span>
        </a>

        <a href="{{ route('catalog.index', array_merge(request()->except(['min_abv', 'max_abv', 'page']), ['min_abv' => 15])) }}"
          class="flex-1 text-center py-2 px-1 sm:px-3 rounded-xl font-medium transition-all {{ request('min_abv') == 15 && !request('max_abv') ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:border-amber-400' }}">
          <span class="sm:hidden">&gt;15% ABV</span>
          <span class="hidden sm:inline">Tinggi (&gt; 15% ABV)</span>
        </a>

        @if(request()->has('min_abv') || request()->has('max_abv'))
        <a href="{{ route('catalog.index', request()->except(['min_abv', 'max_abv', 'page'])) }}" class="hidden sm:inline text-[11px] text-amber-600 dark:text-amber-400 font-bold hover:underline whitespace-nowrap ml-1">
          Reset Filter
        </a>
        @endif
      </div>
    </div>
  </div>

  <!-- CATALOG PRODUCT CONTAINER -->
  <!-- 1. GRID CARD MODE (Default) -->
  <div id="catalog-grid-view" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 pt-2">
    @forelse($products as $product)
    @php
    $prodId = data_get($product, 'id');
    $prodName = data_get($product, 'name');
    $prodSlug = data_get($product, 'slug');
    $prodImg = data_get($product, 'thumbnail_url', data_get($product, 'primary_image.image_url', 'https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80'));
    $prodPrice = (float) data_get($product, 'price', 0);
    $prodCategory = data_get($product, 'category.name', 'Minuman');
    $prodAbv = data_get($product, 'alcohol_percentage', data_get($product, 'abv', 0));
    @endphp

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-3 sm:p-4 border border-slate-200/80 dark:border-slate-800 hover:border-amber-500/50 transition-all group flex flex-col justify-between shadow-sm">
      <a href="{{ route('catalog.show', $prodSlug) }}" class="relative aspect-square rounded-2xl bg-slate-50 dark:bg-slate-950 flex items-center justify-center overflow-hidden mb-3">
        <img src="{{ $prodImg }}" alt="{{ $prodName }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
      </a>

      <div>
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

        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100 dark:border-slate-800/60">
          <div>
            <p class="text-xs sm:text-sm font-black text-amber-600 dark:text-amber-400">
              Rp {{ number_format($prodPrice, 0, ',', '.') }}
            </p>
          </div>

          <form action="{{ route('cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $prodId }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="w-8 h-8 rounded-xl bg-amber-500/10 hover:bg-amber-500 text-amber-600 hover:text-slate-950 dark:text-amber-400 flex items-center justify-center transition-colors">
              <i class="fa-solid fa-plus text-xs"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
    @empty
    <div class="col-span-full py-16 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6">
      <div class="w-12 h-12 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-3 text-xl">
        <i class="fa-solid fa-wine-bottle"></i>
      </div>
      <h3 class="text-sm font-bold text-slate-900 dark:text-white">Produk Tidak Ditemukan</h3>
      <p class="text-xs text-slate-500 mt-1 mb-4">Maaf, tidak ada minuman yang cocok dengan pencarian atau filter Anda.</p>
      <a href="{{ route('catalog.index') }}" class="inline-block bg-amber-500 text-slate-950 font-bold px-5 py-2.5 rounded-2xl text-xs">
        Reset Semua Filter
      </a>
    </div>
    @endforelse
  </div>

  <!-- 2. LISTTILE MODE (Tampilan Horizontal List) -->
  <div id="catalog-list-view" class="hidden space-y-3 pt-2">
    @forelse($products as $product)
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
      <a href="{{ route('catalog.show', $prodSlug) }}" class="shrink-0 w-20 h-20 sm:w-28 sm:h-28 rounded-xl bg-slate-50 dark:bg-slate-950 flex items-center justify-center overflow-hidden">
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
          <button type="submit" class="px-3 py-2 rounded-xl bg-amber-500/10 hover:bg-amber-500 text-amber-600 hover:text-slate-950 dark:text-amber-400 text-xs font-bold flex items-center gap-1.5 transition-colors">
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
      <h3 class="text-sm font-bold text-slate-900 dark:text-white">Produk Tidak Ditemukan</h3>
      <p class="text-xs text-slate-500 mt-1 mb-4">Maaf, tidak ada minuman yang cocok dengan pencarian atau filter Anda.</p>
      <a href="{{ route('catalog.index') }}" class="inline-block bg-amber-500 text-slate-950 font-bold px-5 py-2.5 rounded-2xl text-xs">
        Reset Semua Filter
      </a>
    </div>
    @endforelse
  </div>

</div>

@push('scripts')
<script>
  function switchCatalogView(mode) {
    const gridView = document.getElementById('catalog-grid-view');
    const listView = document.getElementById('catalog-list-view');
    const btnGrid = document.getElementById('btn-view-grid');
    const btnList = document.getElementById('btn-view-list');

    if (mode === 'list') {
      gridView.classList.add('hidden');
      listView.classList.remove('hidden');

      // Update button styles
      btnList.className = "w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors bg-amber-500 text-slate-950 font-bold";
      btnGrid.className = "w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors text-slate-400 hover:text-slate-900 dark:hover:text-white";

      localStorage.setItem('kt_catalog_view', 'list');
    } else {
      listView.classList.add('hidden');
      gridView.classList.remove('hidden');

      // Update button styles
      btnGrid.className = "w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors bg-amber-500 text-slate-950 font-bold";
      btnList.className = "w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors text-slate-400 hover:text-slate-900 dark:hover:text-white";

      localStorage.setItem('kt_catalog_view', 'grid');
    }
  }

  // Set initial view berdasarkan preferensi user
  document.addEventListener('DOMContentLoaded', () => {
    const savedView = localStorage.getItem('kt_catalog_view') || 'grid';
    switchCatalogView(savedView);
  });
</script>
@endpush
@endsection