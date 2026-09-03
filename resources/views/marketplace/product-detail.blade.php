@extends('welcome')

@section('title', ($product['name'] ?? 'Detail Produk') . ' - Tipsy More')

@section('content')
  @php
    $toUrl = function (?string $path) {
        if (!$path) return null;
        if (\Illuminate\Support\Str::startsWith($path, 'http')) return $path;
        return $path;
    };

    $defaultPath = $product['thumbnail_url'] ?? $product['image_url'] ?? null;
    $initialActiveImage = $toUrl($defaultPath) ?? 'https://placehold.co/600x600/0f172a/ffffff?text=No+Image';

    $rawImages = $product['images'] ?? [];
    $formattedImages = [];
    foreach ($rawImages as $img) {
        $path = is_array($img) ? ($img['image_url'] ?? $img['url'] ?? '') : $img;
        if ($path) $formattedImages[] = $toUrl($path);
    }
    if (empty($formattedImages)) $formattedImages[] = $initialActiveImage;
  @endphp

  <!-- BREADCRUMB -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <nav class="flex text-xs text-slate-500 dark:text-slate-400 gap-2 items-center">
      <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
      <span>/</span>
      <a href="{{ route('catalog.index', ['category' => $product['category']['slug'] ?? '']) }}" class="hover:underline">
        {{ $product['category']['name'] ?? 'Katalog' }}
      </a>
      <span>/</span>
      <span class="text-slate-900 dark:text-slate-200 font-medium truncate">{{ $product['name'] ?? 'Detail' }}</span>
    </nav>
  </div>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

      <!-- LEFT: GALLERY & ZOOM -->
      <div class="lg:col-span-5 space-y-4">
        <div id="main-zoom-container" class="relative aspect-square rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 shadow-sm overflow-hidden group cursor-zoom-in">
          
          <div class="absolute top-4 left-4 right-4 flex items-center justify-between z-10 pointer-events-none">
            @if(!empty($product['is_cold_ready']))
              <span class="bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20 text-[11px] font-bold px-2.5 py-1 rounded-lg backdrop-blur-md">
                <i class="fa-solid fa-snowflake me-1"></i>Siap Dingin
              </span>
            @else
              <div></div>
            @endif

            <span class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-[11px] font-bold px-2.5 py-1 rounded-lg backdrop-blur-md">
              <i class="fa-solid fa-circle-check me-1"></i>100% Original
            </span>
          </div>

          <div class="w-full h-full flex items-center justify-center overflow-hidden pointer-events-none">
            <img id="main-product-img" src="{{ $initialActiveImage }}" alt="{{ $product['name'] ?? 'Produk' }}" class="w-full h-full object-contain drop-shadow-xl transition-transform duration-100 ease-out">
          </div>

          <div class="absolute bottom-3 right-3 bg-slate-900/70 text-white text-[10px] px-2.5 py-1 rounded-md backdrop-blur-sm pointer-events-none flex items-center gap-1.5">
            <i class="fa-solid fa-magnifying-glass-plus"></i> Arahkan kursor zoom / Klik pop-up
          </div>
        </div>

        @if(count($formattedImages) > 1)
          <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-none">
            @foreach($formattedImages as $idx => $imgUrl)
              <button type="button" 
                      onclick="changeImage('{{ $imgUrl }}', this)" 
                      class="thumb-btn relative w-20 h-20 flex-shrink-0 rounded-2xl bg-white dark:bg-slate-900 border-2 overflow-hidden p-1 transition-all {{ $idx === 0 ? 'border-amber-500 ring-2 ring-amber-500/20 opacity-100' : 'border-slate-200 dark:border-slate-800 opacity-60 hover:opacity-100' }}">
                <img src="{{ $imgUrl }}" class="w-full h-full object-contain pointer-events-none">
              </button>
            @endforeach
          </div>
        @endif
      </div>

      <!-- RIGHT: INFO & SPECS -->
      <div class="lg:col-span-7 space-y-6">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="text-xs uppercase font-bold text-amber-600 dark:text-amber-400 tracking-wider">
              {{ $product['category']['name'] ?? 'Minuman Premium' }}
            </span>
            @if(!empty($product['brand']['name']))
              <span class="text-xs text-slate-400">•</span>
              <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                {{ $product['brand']['name'] }}
              </span>
            @endif
          </div>
          <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white leading-tight">
            {{ $product['name'] ?? 'Nama Produk' }}
          </h1>
          <p class="text-xs text-slate-400 mt-1">SKU: {{ $product['sku'] ?? '-' }}</p>
        </div>

        <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
          <div class="flex items-baseline gap-3">
            <span class="text-3xl font-black text-amber-600 dark:text-amber-400">
              Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}
            </span>
            @if(!empty($product['strike_price']) && $product['strike_price'] > $product['price'])
              @php $discount = round((($product['strike_price'] - $product['price']) / $product['strike_price']) * 100); @endphp
              <span class="text-base text-slate-400 line-through">
                Rp {{ number_format($product['strike_price'], 0, ',', '.') }}
              </span>
              <span class="bg-rose-500/10 text-rose-600 text-xs font-bold px-2 py-0.5 rounded-md">-{{ $discount }}%</span>
            @endif
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex items-center gap-1.5">
            <i class="fa-solid fa-truck-fast text-amber-500"></i> Pengiriman kilat dengan kemasan aman & suhu terjaga.
          </p>
        </div>

        <div>
          <h3 class="text-xs font-bold uppercase text-slate-400 mb-2.5">Spesifikasi Minuman</h3>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3.5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Kadar Alkohol</span>
              <span class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ $product['abv'] ?? 0 }}% ABV</span>
            </div>
            <div class="p-3.5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Volume</span>
              <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $product['volume_ml'] ?? '-' }} ml</span>
            </div>
            <div class="p-3.5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Brand</span>
              <span class="text-sm font-bold text-slate-900 dark:text-white truncate block">{{ $product['brand']['name'] ?? '-' }}</span>
            </div>
            <div class="p-3.5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Stok Total</span>
              <span class="text-sm font-bold {{ ($product['stock'] ?? 0) > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                {{ ($product['stock'] ?? 0) > 0 ? ($product['stock'] . ' pcs') : 'Habis' }}
              </span>
            </div>
          </div>
        </div>

        @if(!empty($product['vibes']) && count($product['vibes']) > 0)
          <div>
            <h3 class="text-xs font-bold uppercase text-slate-400 mb-2">Cocok Untuk Momen</h3>
            <div class="flex flex-wrap gap-2">
              @foreach($product['vibes'] as $vibe)
                @php $vibeImgUrl = $toUrl($vibe['icon_url'] ?? null); @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium bg-amber-500/10 text-amber-700 dark:text-amber-300 border border-amber-500/20">
                  @if($vibeImgUrl)
                    <img src="{{ $vibeImgUrl }}" class="w-4 h-4 object-contain rounded" alt="{{ $vibe['name'] ?? '' }}">
                  @else
                    <i class="fa-solid fa-glass-cheers"></i>
                  @endif
                  {{ $vibe['name'] }}
                </span>
              @endforeach
            </div>
          </div>
        @endif

        <div class="space-y-2">
          <h3 class="text-sm font-bold text-slate-900 dark:text-white">Deskripsi Produk</h3>
          <div class="text-xs md:text-sm text-slate-600 dark:text-slate-400 leading-relaxed space-y-2">
            {!! nl2br(e($product['description'] ?? 'Tidak ada deskripsi produk.')) !!}
          </div>
        </div>

        <!-- ACTION BAR -->
        <form action="{{ route('cart.store') }}" method="POST" class="hidden md:block pt-4 border-t border-slate-200 dark:border-slate-800">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product['id'] ?? '' }}">

          <div class="flex items-center gap-4">
            <div class="flex items-center bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-1">
              <button type="button" id="btn-minus" onclick="updateQty(-1)" disabled class="w-10 h-10 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 disabled:opacity-30 disabled:cursor-not-allowed rounded-lg font-bold text-lg">-</button>
              <input type="number" id="qty-input" name="quantity" value="1" min="1" readonly class="w-12 text-center bg-transparent text-sm font-bold text-slate-900 dark:text-white focus:outline-none">
              <button type="button" onclick="updateQty(1)" class="w-10 h-10 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg font-bold text-lg">+</button>
            </div>

            <button type="submit" {{ ($product['stock'] ?? 0) <= 0 ? 'disabled' : '' }} class="flex-1 bg-amber-500 hover:bg-amber-600 disabled:bg-slate-300 dark:disabled:bg-slate-800 disabled:text-slate-500 text-slate-950 font-bold py-3.5 px-6 rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20">
              <i class="fa-solid fa-bag-shopping"></i> {{ ($product['stock'] ?? 0) > 0 ? '+ Keranjang Belanja' : 'Stok Habis' }}
            </button>
          </div>
        </form>

      </div>
    </div>

    <!-- POPUP MODAL LIGHTBOX -->
    <div id="image-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" onclick="closeModal(event)">
      <div class="relative max-w-4xl w-full bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-2xl flex flex-col items-center" onclick="event.stopPropagation()">
        <button type="button" onclick="closeModalDirect()" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-rose-500 hover:text-white text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold shadow-md">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2 self-start">
          <i class="fa-solid fa-magnifying-glass text-amber-500"></i> Detail Gambar Produk
        </h3>

        <div class="w-full h-[60vh] max-h-[600px] flex items-center justify-center p-4 bg-slate-50 dark:bg-slate-950/50 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800/80">
          <img id="modal-img" src="{{ $initialActiveImage }}" alt="Produk" class="max-w-full max-h-full object-contain drop-shadow-2xl">
        </div>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
<script>
  let currentQty = 1;

  // 1. Kuantitas Cart
  function updateQty(change) {
    currentQty += change;
    if (currentQty < 1) currentQty = 1;
    document.getElementById('qty-input').value = currentQty;
    document.getElementById('btn-minus').disabled = (currentQty <= 1);
  }

  // 2. Ganti Gambar
  function changeImage(url, el) {
    document.getElementById('main-product-img').src = url;
    document.getElementById('modal-img').src = url;
    
    document.querySelectorAll('.thumb-btn').forEach(btn => {
      btn.className = 'thumb-btn relative w-20 h-20 flex-shrink-0 rounded-2xl bg-white dark:bg-slate-900 border-2 overflow-hidden p-1 transition-all border-slate-200 dark:border-slate-800 opacity-60 hover:opacity-100';
    });
    el.className = 'thumb-btn relative w-20 h-20 flex-shrink-0 rounded-2xl bg-white dark:bg-slate-900 border-2 overflow-hidden p-1 transition-all border-amber-500 ring-2 ring-amber-500/20 opacity-100';
  }

  // 3. Hover Magnifier Zoom
  const zoomBox = document.getElementById('main-zoom-container');
  const mainImg = document.getElementById('main-product-img');

  zoomBox.addEventListener('mousemove', function(e) {
    const rect = zoomBox.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    mainImg.style.transformOrigin = `${x}% ${y}%`;
    mainImg.style.transform = 'scale(2.2)';
  });

  zoomBox.addEventListener('mouseleave', function() {
    mainImg.style.transform = 'scale(1)';
  });

  // 4. Modal Pop-Up
  zoomBox.addEventListener('click', function() {
    document.getElementById('image-modal').classList.remove('hidden');
  });

  function closeModal(e) {
    if (e.target.id === 'image-modal') {
      document.getElementById('image-modal').classList.add('hidden');
    }
  }

  function closeModalDirect() {
    document.getElementById('image-modal').classList.add('hidden');
  }
</script>
@endpush