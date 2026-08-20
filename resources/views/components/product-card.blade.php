@props(['product'])

@php
    // Dukungan fleksibel untuk Array maupun Eloquent Model/Object
    $prodId          = data_get($product, 'id');
    $prodName        = data_get($product, 'name');
    $prodSlug        = data_get($product, 'slug');
    $prodPrice       = (float) data_get($product, 'price', 0);
    $prodStrikePrice = data_get($product, 'strike_price');
    $prodAbv         = data_get($product, 'alcohol_percentage', data_get($product, 'abv', 0));
    $prodVolume      = data_get($product, 'volume_ml');
    $prodCategory    = data_get($product, 'category.name', 'Minuman');
    $isColdReady     = data_get($product, 'is_cold_ready', false);

    // Resolusi Gambar Utama
    $prodImg = data_get($product, 'thumbnail_url') 
        ?? data_get($product, 'primary_image.image_url') 
        ?? data_get($product, 'images.0.image_url') 
        ?? 'https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80';
@endphp

<div class="group cursor-pointer">
    <!-- Image Card Container -->
    <div class="relative aspect-[4/5] rounded-2xl sm:rounded-3xl overflow-hidden bg-white border border-slate-100 dark:border-slate-800 dark:bg-slate-900 mb-3 sm:mb-4 shadow-sm">
        <img src="{{ $prodImg }}" alt="{{ $prodName }}" class="w-full h-full object-contain p-2 mix-blend-multiply dark:mix-blend-normal group-hover:scale-105 transition-transform duration-500">

        @if($isColdReady)
        <div class="absolute top-2.5 left-2.5 bg-indigo-500 text-white text-[9px] sm:text-[10px] font-bold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full z-10 shadow-sm">
            🧊 COLD READY
        </div>
        @endif

        <!-- Quick Add to Cart Button -->
        <form action="{{ route('cart.store') }}" method="POST" class="absolute bottom-2.5 right-2.5 z-20">
            @csrf
            <input type="hidden" name="product_id" value="{{ $prodId }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-amber-500 text-slate-950 flex items-center justify-center shadow-lg hover:bg-amber-400 transition-colors">
                <i class="fa-solid fa-plus text-xs sm:text-sm"></i>
            </button>
        </form>

        <a href="{{ route('catalog.show', $prodSlug) }}" class="absolute inset-0 z-10"></a>
    </div>

    <!-- Details Container -->
    <div>
        <div class="flex items-center justify-between">
            <span class="text-[9px] sm:text-[10px] uppercase font-semibold text-amber-600 dark:text-amber-400 tracking-wider">
                {{ $prodCategory }}
            </span>
            <span class="text-[9px] sm:text-[10px] text-slate-400 font-medium">{{ $prodAbv }}% ABV</span>
        </div>

        <a href="{{ route('catalog.show', $prodSlug) }}" class="block">
            <h3 class="text-xs sm:text-base font-serif font-bold text-slate-900 dark:text-white mt-0.5 group-hover:text-amber-500 transition-colors line-clamp-1">
                {{ $prodName }} @if($prodVolume) ({{ $prodVolume }}ml) @endif
            </h3>
        </a>

        <div class="flex items-center gap-2 my-1">
            <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">
                Rp {{ number_format($prodPrice, 0, ',', '.') }}
            </p>
            @if(!empty($prodStrikePrice) && $prodStrikePrice > $prodPrice)
            <span class="text-[10px] sm:text-xs text-slate-400 line-through">
                Rp {{ number_format($prodStrikePrice, 0, ',', '.') }}
            </span>
            @endif
        </div>
    </div>
</div>