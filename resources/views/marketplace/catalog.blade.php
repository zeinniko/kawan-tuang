@extends('welcome')

@section('title', 'Katalog Produk Lengkap - Kawan Tuang')

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
      <p class="text-xs text-slate-500 dark:text-slate-400">Temukan minuman favoritmu dari koleksi terlengkap 100% Original</p>
    </div>

    <!-- SORTING DROPDOWN -->
    <form action="{{ route('catalog.index') }}" method="GET" class="flex items-center gap-2">
      @foreach(request()->except('sort_by') as $key => $val)
      <input type="hidden" name="{{ $key }}" value="{{ $val }}">
      @endforeach
      <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">Urutkan:</span>
      <select name="sort_by" onchange="this.form.submit()" class="bg-white dark:bg-slate-900 text-xs font-semibold text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-500">
        <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Varian Terbaru</option>
        <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
        <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
      </select>
    </form>
  </div>
</div>

<!-- SEARCH & FILTER SECTION -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

  <!-- MOBILE SEARCH INPUT -->
  <form action="{{ route('catalog.index') }}" method="GET" class="sm:hidden mb-4 relative">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari beer, wine, whiskey..." class="w-full bg-white dark:bg-slate-900 text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 rounded-xl py-2.5 pl-9 pr-4 border border-slate-200 dark:border-slate-800 focus:outline-none focus:ring-1 focus:ring-amber-500">
    <button type="submit" class="absolute left-3 top-3 text-slate-400 text-xs">
      <i class="fa-solid fa-magnifying-glass"></i>
    </button>
  </form>

  <!-- CATEGORY PILLS & FILTER BAR -->
  <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">

    <!-- Category Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs flex-1 scrollbar-hide">
      <a href="{{ route('catalog.index') }}"
        class="px-4 py-2 rounded-full whitespace-nowrap {{ !request('category') ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800' }}">
        Semua Varian
      </a>
      @foreach($categories as $category)
      @php
      $catSlug = data_get($category, 'slug', data_get($category, 'data.slug'));
      $catName = data_get($category, 'name', data_get($category, 'data.name'));
      @endphp
      <a href="{{ route('catalog.index', array_merge(request()->query(), ['category' => $catSlug])) }}"
        class="px-4 py-2 rounded-full whitespace-nowrap {{ request('category') === $catSlug ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800' }}">
        {{ $catName }}
      </a>
      @endforeach
    </div>

    <!-- Quick ABV Filter -->
    <div class="flex items-center gap-2 text-xs">
      <span class="text-slate-400 text-[11px] hidden sm:inline">Kadar Alkohol:</span>
      <a href="{{ route('catalog.index', array_merge(request()->query(), ['max_abv' => 5])) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-lg border border-slate-200 dark:border-slate-800 text-[11px] font-medium hover:border-amber-400">&lt; 5% ABV</a>
      <a href="{{ route('catalog.index', array_merge(request()->query(), ['min_abv' => 5, 'max_abv' => 15])) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-lg border border-slate-200 dark:border-slate-800 text-[11px] font-medium hover:border-amber-400">5% - 15% ABV</a>
      <a href="{{ route('catalog.index', array_merge(request()->query(), ['min_abv' => 15])) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-lg border border-slate-200 dark:border-slate-800 text-[11px] font-medium hover:border-amber-400">&gt; 15% ABV</a>
    </div>

  </div>

  <!-- CATALOG PRODUCT GRID -->
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
    @forelse($products as $product)
    @php
    $prodId = data_get($product, 'id');
    $prodName = data_get($product, 'name');
    $prodSlug = data_get($product, 'slug');
    $prodImg = data_get($product, 'thumbnail_url', data_get($product, 'primary_image.image_url', 'https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80'));
    $prodPrice = (float) data_get($product, 'price', 0);
    $prodCategory = data_get($product, 'category.name', 'Minuman');
    $prodAbv = data_get($product, 'abv', data_get($product, 'alcohol_percentage', 0));
    @endphp

    <div class="bg-white dark:bg-slate-900 rounded-2xl p-3 border border-slate-200 dark:border-slate-800/80 hover:border-amber-500/50 transition-all group flex flex-col justify-between shadow-sm">

      <a href="{{ route('catalog.show', $prodSlug) }}" class="relative aspect-square rounded-xl bg-slate-100 dark:bg-slate-950 flex items-center justify-center overflow-hidden mb-3">
        <img src="{{ $prodImg }}" alt="{{ $prodName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
      </a>

      <div>
        <div class="flex items-center justify-between">
          <span class="text-[10px] uppercase font-semibold text-amber-600 dark:text-amber-400 tracking-wider">
            {{ $prodCategory }}
          </span>
          <span class="text-[10px] text-slate-400 font-medium">{{ $prodAbv }}% ABV</span>
        </div>

        <a href="{{ route('catalog.show', $prodSlug) }}" class="block">
          <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 mt-0.5 hover:text-amber-500 transition-colors">
            {{ $prodName }}
          </h3>
        </a>

        <div class="flex items-center justify-between mt-3">
          <div>
            <p class="text-sm font-extrabold text-amber-600 dark:text-amber-400">
              Rp {{ number_format($prodPrice, 0, ',', '.') }}
            </p>
          </div>

          <!-- Form Tambah ke Keranjang -->
          <form action="{{ route('cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $prodId }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-amber-500 hover:text-slate-950 text-slate-700 dark:text-slate-200 flex items-center justify-center transition-colors">
              <i class="fa-solid fa-plus text-xs"></i>
            </button>
          </form>
        </div>

      </div>
    </div>
    @empty
    <div class="col-span-full p-8 text-center text-slate-400 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800">
      Tidak ada produk yang cocok dengan pencarian / filter Anda.
    </div>
    @endforelse
  </div>

  <!-- PAGINATION LINK -->
  <div class="mt-10">
    @if(is_object($products) && method_exists($products, 'links'))
    {{ $products->links() }}
    @elseif(is_array($products) && isset($products['meta']['links']))
    <!-- Jika data dikirim dari API Resource Pagination -->
    <div class="flex justify-center gap-2">
      @foreach($products['meta']['links'] as $link)
      <a href="{{ $link['url'] ?? '#' }}"
        class="px-3 py-2 rounded-lg text-xs font-semibold {{ $link['active'] ? 'bg-amber-500 text-slate-950' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800' }}">
        {!! $link['label'] !!}
      </a>
      @endforeach
    </div>
    @endif
  </div>
</div>
@endsection