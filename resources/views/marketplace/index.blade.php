@extends('welcome')

@section('title', 'Kawan Tuang - Premium Beverage Store')

@section('content')
<!-- ================= HERO BANNER SLIDER ================= -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 md:pt-12">
  <!-- Container Utama: Menggunakan Background Wine Fermentasi Gelap (bg-[#2a0812]) dan Border Maroon (border-[#4a0d1d]) -->
  <div class="relative rounded-[2rem] md:rounded-[2.5rem] overflow-hidden bg-gradient-to-br from-[#2e0914] via-[#1a040b] to-[#0f0207] text-white shadow-2xl shadow-rose-950/40 border border-[#4a0d1d]">

    <!-- Background Glow Effects Nuansa Wine & Amber -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
      <div class="absolute -top-24 -left-24 w-96 h-96 bg-rose-700/25 rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 right-0 w-80 h-80 bg-amber-500/15 rounded-full blur-3xl"></div>
    </div>

    <!-- Slider Wrapper -->
    <div id="hero-slider" class="flex transition-transform duration-700 ease-in-out relative z-10 items-stretch">

      <!-- Slide 1: Welcome Banner -->
      <div class="min-w-full flex flex-col md:flex-row items-center justify-between p-6 sm:p-8 md:p-16">
        <div class="space-y-4 md:space-y-6 max-w-xl text-center md:text-left">
          <span class="inline-block px-3 py-1 md:px-4 md:py-1.5 bg-rose-500/20 backdrop-blur-md text-amber-300 border border-rose-400/30 rounded-full text-[10px] md:text-xs font-semibold uppercase tracking-widest">
            ⚡ Delivery 1 Jam • Ready to Serve
          </span>
          <h1 class="text-3xl sm:text-4xl md:text-6xl font-serif leading-tight">
            Kawani Momen <br class="hidden sm:inline">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-amber-300 to-amber-500 italic">Sempurna Anda.</span>
          </h1>
          <p class="text-rose-100/80 text-xs sm:text-sm md:text-base font-light leading-relaxed">
            Koleksi premium Whiskey, Fine Wine, dan Craft Beer 100% Original. Dikirim langsung ke depan pintu Anda dengan suhu yang terjaga.
          </p>
          <div class="flex items-center justify-center md:justify-start gap-4 pt-2 md:pt-4">
            <a href="{{ route('catalog.index') }}" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-6 py-3 md:px-8 md:py-3.5 rounded-full text-xs md:text-sm transition-all shadow-lg shadow-amber-500/25">
              Eksplorasi Sekarang
            </a>
          </div>
        </div>

        <div class="w-full md:w-1/2 flex justify-center p-4 md:p-0 relative mt-6 md:mt-0">
          <img src="https://images.unsplash.com/photo-1527281400683-1aae777175f8?auto=format&fit=crop&w=600&q=80"
            alt="Promo Beverage" class="w-48 sm:w-64 md:w-80 h-[240px] sm:h-[320px] md:h-[400px] object-cover rounded-full shadow-2xl border-4 border-rose-300/20 animate-float-slow">
          <div class="absolute bottom-6 -left-2 md:bottom-12 md:left-8 bg-[#2a0812]/80 backdrop-blur-md border border-rose-500/30 p-2.5 sm:p-4 rounded-2xl animate-float-fast shadow-xl">
            <p class="text-amber-400 font-bold text-xs sm:text-lg">100% Original</p>
            <p class="text-[10px] sm:text-xs text-rose-100/90">Garansi Distributor Resmi</p>
          </div>
        </div>
      </div>

      <!-- Slide 2: Voucher / Promo Banner -->
      <div class="min-w-full relative flex flex-col justify-start sm:justify-end overflow-hidden">
        <!-- Background Image -->
        <img src="https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=1200&q=80"
          alt="Voucher Promo" class="absolute inset-0 w-full h-full object-cover">

        <!-- Gradient Overlay dengan Perpaduan Tone Wine (#21050d) -->
        <div class="relative z-10 bg-gradient-to-b from-[#21050d]/95 via-[#21050d]/65 to-transparent sm:bg-gradient-to-t sm:from-[#21050d]/95 sm:via-[#21050d]/55 sm:to-transparent p-6 sm:p-8 md:p-14 flex flex-col justify-start sm:justify-end h-full">

          <span class="px-3 py-1 bg-rose-600 text-white font-bold rounded-full text-[10px] uppercase tracking-widest w-max mb-2 shadow-md">
            Voucher Diskon Spesial
          </span>

          @if(!empty($vouchers) && count($vouchers) > 0)
          <h2 class="text-2xl md:text-4xl font-serif font-bold text-white mb-2">
            Gunakan Kode: <span class="text-amber-400 font-mono">{{ $vouchers[0]['code'] ?? 'KT21PLUS' }}</span>
          </h2>
          <p class="text-xs md:text-sm text-rose-100/90 max-w-lg mb-4">
            Diskon Rp {{ number_format($vouchers[0]['discount_value'] ?? 20000, 0, ',', '.') }} untuk minimal belanja Rp {{ number_format($vouchers[0]['min_order_amount'] ?? 100000, 0, ',', '.') }}.
          </p>
          @else
          <h2 class="text-2xl md:text-4xl font-serif font-bold text-white mb-2">
            Pesta Hemat Bersama Kawan Tuang
          </h2>
          <p class="text-xs md:text-sm text-rose-100/90 max-w-lg mb-4">
            Nikmati potongan harga eksklusif untuk pesanan pilihan minggu ini.
          </p>
          @endif

          <a href="{{ route('catalog.index') }}" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-6 py-2.5 rounded-full text-xs w-max transition-colors shadow-lg shadow-amber-500/20">
            Gunakan Voucher
          </a>
        </div>
      </div>

    </div>

    <!-- Slider Navigation Buttons -->
    <!-- Button Previous (Left Box) -->
    <button onclick="prevSlide()" class="absolute left-2.5 sm:left-4 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 flex items-center justify-center transition-all duration-300 shadow-lg shadow-amber-500/20 hover:scale-105 active:scale-95 z-20 group">
      <i class="fa-solid fa-angle-left font-bold text-xs sm:text-base group-hover:-translate-x-0.5 transition-transform"></i>
    </button>

    <!-- Button Next (Right Box) -->
    <button onclick="nextSlide()" class="absolute right-2.5 sm:right-4 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 flex items-center justify-center transition-all duration-300 shadow-lg shadow-amber-500/20 hover:scale-105 active:scale-95 z-20 group">
      <i class="fa-solid fa-angle-right font-bold text-xs sm:text-base group-hover:translate-x-0.5 transition-transform"></i>
    </button>

  </div>
</section>

<!-- ================= 2. EVENT LIGHT PROMO / BEST DEAL ================= -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">
  <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-3xl p-5 sm:p-6 md:p-8 text-slate-950 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6 shadow-xl">

    <div class="space-y-1.5 text-center md:text-left w-full md:w-auto">
      <span class="bg-slate-950 text-amber-400 text-[10px] sm:text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider inline-block">
        🔥 Best Deal Hari Ini
      </span>
      <h2 class="text-xl sm:text-2xl md:text-3xl font-serif font-bold leading-tight">Lagi Cari yang Segar dengan Harga Spesial?</h2>
      <p class="text-xs sm:text-sm text-slate-900 font-medium">Jangan sampai kelewatan potongan harga khusus minggu ini!</p>
    </div>

    <!-- Disesuaikan Layout Jam & Tombol Agar Kompak di Mobile -->
    <div class="flex flex-row items-center justify-between sm:justify-end gap-3 w-full md:w-auto shrink-0 pt-2 md:pt-0 border-t border-slate-950/10 md:border-t-0">
      <div class="bg-slate-950 text-white px-3 py-2 sm:px-4 sm:py-2.5 rounded-2xl text-center shrink-0">
        <span class="block text-[9px] sm:text-[10px] text-amber-400 font-bold uppercase tracking-wider">Berakhir Dalam</span>
        <span id="countdown" class="font-mono text-sm sm:text-base font-bold text-white">04 : 32 : 18</span>
      </div>
      <a href="{{ route('catalog.index') }}" class="bg-slate-950 hover:bg-slate-900 text-amber-400 font-bold px-4 py-3 sm:px-6 sm:py-3.5 rounded-2xl text-xs sm:text-sm transition-colors whitespace-nowrap shadow-md flex items-center justify-center gap-2 flex-1 sm:flex-initial">
        <span>Sikat Sekarang</span>
        <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>

  </div>
</section>

<!-- ================= 3. PILIH SELERA ANDA (Category) ================= -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <div class="flex items-center justify-between mb-4 sm:mb-6">
    <div>
      <h2 class="text-lg sm:text-2xl font-serif font-bold text-slate-900 dark:text-white">Mau Teman Minum yang Mana Nih?</h2>
      <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pilih kategori favorit yang pas buat nemenin santai kamu.</p>
    </div>

    <!-- Perbarui Tampilan 'Lihat Semua' -->
    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-500/10 hover:bg-amber-500 hover:text-slate-950 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full border border-amber-500/20 transition-all shrink-0">
      <span>Lihat Semua</span>
      <i class="fa-solid fa-arrow-right text-[10px]"></i>
    </a>
  </div>

  <!-- Container Slide Alami dengan Snap Scroll & Hidden Scrollbar -->
  <div class="flex gap-4 sm:gap-6 overflow-x-auto pb-4 scrollbar-hide snap-x snap-mandatory scroll-smooth -mx-4 px-4 sm:mx-0 sm:px-0">
    @php
    // Filter kategori agar hanya yang memiliki produk saja yang ditampilkan
    $activeCategories = collect($categories)->filter(function($category) {
    return (int) data_get($category, 'products_count', 0) > 0;
    });
    @endphp

    @forelse($activeCategories as $category)
    <a href="{{ route('catalog.index', ['category_slug' => data_get($category, 'slug', '')]) }}"
      class="flex-shrink-0 snap-start group text-center w-20 sm:w-24">

      <!-- Icon Circle -->
      <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full bg-white dark:bg-slate-900 shadow-sm border border-slate-200/80 dark:border-slate-800 flex items-center justify-center mb-2.5 group-hover:border-amber-500 group-hover:bg-amber-500/10 dark:group-hover:bg-amber-500/20 transition-all duration-300">
        <i class="fa-solid fa-wine-bottle text-2xl sm:text-3xl text-slate-400 group-hover:text-amber-500 group-hover:scale-110 transition-all duration-300"></i>
      </div>

      <!-- Category Title & Count -->
      <h3 class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 truncate group-hover:text-amber-500 transition-colors">
        {{ data_get($category, 'name', '') }}
      </h3>
      <p class="text-[10px] text-slate-400 mt-0.5">
        {{ data_get($category, 'products_count', 0) }} Varian
      </p>
    </a>
    @empty
    <div class="w-full py-6 text-center text-xs text-slate-400">
      Kategori produk belum tersedia saat ini.
    </div>
    @endforelse
  </div>
</section>

<!-- ================= 4. MINUM UNTUK ACARA APA? (Vibes) ================= -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="mb-6">
    <h2 class="text-3xl font-serif font-bold text-slate-900 dark:text-white">Malam Ini Agendanya Apa Nih?</h2>
    <p class="text-sm text-slate-500 mt-1">Biar gak salah pilih, yuk sesuaikan minuman dengan suasana acara kamu.</p>
  </div>

  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @forelse($vibes as $vibe)
    @php
    $vibeName = data_get($vibe, 'name', data_get($vibe, 'data.name'));
    $vibeSlug = data_get($vibe, 'slug', data_get($vibe, 'data.slug'));
    $vibeEmoji = data_get($vibe, 'icon_emoji', data_get($vibe, 'data.icon_emoji', '🥂'));
    $vibeImage = data_get($vibe, 'image_url', data_get($vibe, 'data.image_url', 'https://images.unsplash.com/photo-1516997180214-3a5c2f829f8f?auto=format&fit=crop&w=500&q=80'));
    @endphp
    <a href="{{ route('catalog.index', ['vibe_slug' => $vibeSlug]) }}" class="relative h-40 md:h-56 rounded-3xl overflow-hidden group">
      <img src="{{ $vibeImage }}" alt="{{ $vibeName }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-4">
        <div class="text-2xl mb-1">{{ $vibeEmoji }}</div>
        <h3 class="text-white font-serif font-bold text-lg">{{ $vibeName }}</h3>
      </div>
    </a>
    @empty
    <p class="text-xs text-slate-400 col-span-full">Pilihan vibe belum tersedia.</p>
    @endforelse
  </div>
</section>

<!-- ================= 5. KEUNTUNGAN BELANJA DI KT ================= -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
    <div class="text-center max-w-xl mx-auto mb-10">
      <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 dark:text-white">Kenapa Harus Belanja di Kawan Tuang?</h2>
      <p class="text-xs md:text-sm text-slate-500 mt-2">Biar kamu gak ragu, ini nih keistimewaan yang bakal kamu dapetin setiap kali order.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl shrink-0">
          <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div>
          <h4 class="font-bold text-slate-900 dark:text-white mb-1">100% Original Bergaransi</h4>
          <p class="text-xs text-slate-500 leading-relaxed">Gak usah takut barang palsu. Semua produk diimpor langsung dari distributor resmi bersertifikat.</p>
        </div>
      </div>
      <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl shrink-0">
          <i class="fa-solid fa-bolt"></i>
        </div>
        <div>
          <h4 class="font-bold text-slate-900 dark:text-white mb-1">Kirim Kilat 1 Jam Sampai</h4>
          <p class="text-xs text-slate-500 leading-relaxed">Minuman langsung diantar pakai kurir instant dalam kondisi dingin terjaga siap teguk.</p>
        </div>
      </div>
      <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl shrink-0">
          <i class="fa-solid fa-gift"></i>
        </div>
        <div>
          <h4 class="font-bold text-slate-900 dark:text-white mb-1">Poin & Reward Member</h4>
          <p class="text-xs text-slate-500 leading-relaxed">Setiap transaksi otomatis ngumpulin poin yang bisa kamu tukar voucher diskon di pesanan berikutnya!</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= 6. BRAND & KOLEKSI GLOBAL ================= -->
<section class="py-10 relative overflow-hidden bg-[#F8F9FA] dark:bg-[#0B0F19]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
    <h2 class="text-xl font-serif font-bold text-slate-900 dark:text-white text-center">Brand Global Pilihan Sobat KT</h2>
  </div>

  <!-- Gradient Overlay Kiri & Kanan -->
  <div class="absolute inset-y-0 left-0 w-16 md:w-32 bg-gradient-to-r from-[#F8F9FA] dark:from-[#0B0F19] to-transparent z-10 pointer-events-none"></div>
  <div class="absolute inset-y-0 right-0 w-16 md:w-32 bg-gradient-to-l from-[#F8F9FA] dark:from-[#0B0F19] to-transparent z-10 pointer-events-none"></div>

  <div class="flex flex-col gap-4 relative z-0">

    <!-- BARIS 1: Ke Kanan (animate-marquee-right) -->
    <div class="flex w-max animate-marquee-right hover:[animation-play-state:paused]">
      <div class="flex gap-4 px-2">
        @forelse($brands as $brand)
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest uppercase text-xs sm:text-sm whitespace-nowrap shadow-sm">
          {{ $brand['name'] ?? '' }}
        </div>
        @empty
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">MACALLAN</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">PENFOLDS</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">HENDRICK'S</div>
        @endforelse
      </div>
      <div class="flex gap-4 px-2">
        @forelse($brands as $brand)
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest uppercase text-xs sm:text-sm whitespace-nowrap shadow-sm">
          {{ $brand['name'] ?? '' }}
        </div>
        @empty
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">MACALLAN</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">PENFOLDS</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">HENDRICK'S</div>
        @endforelse
      </div>
    </div>

    <!-- BARIS 2: Ke Kiri (animate-marquee-left) -->
    <div class="flex w-max animate-marquee-left hover:[animation-play-state:paused]">
      <div class="flex gap-4 px-2">
        @forelse($brands as $brand)
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest uppercase text-xs sm:text-sm whitespace-nowrap shadow-sm">
          {{ $brand['name'] ?? '' }}
        </div>
        @empty
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">JOHNNIE WALKER</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">CHIVAS REGAL</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">JACK DANIEL'S</div>
        @endforelse
      </div>
      <div class="flex gap-4 px-2">
        @forelse($brands as $brand)
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest uppercase text-xs sm:text-sm whitespace-nowrap shadow-sm">
          {{ $brand['name'] ?? '' }}
        </div>
        @empty
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">JOHNNIE WALKER</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">CHIVAS REGAL</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">JACK DANIEL'S</div>
        @endforelse
      </div>
    </div>

    <!-- BARIS 3: Ke Kanan (animate-marquee-right) -->
    <div class="flex w-max animate-marquee-right hover:[animation-play-state:paused]">
      <div class="flex gap-4 px-2">
        @forelse($brands as $brand)
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest uppercase text-xs sm:text-sm whitespace-nowrap shadow-sm">
          {{ $brand['name'] ?? '' }}
        </div>
        @empty
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">MOËT & CHANDON</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">JAMESON</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">GREY GOOSE</div>
        @endforelse
      </div>
      <div class="flex gap-4 px-2">
        @forelse($brands as $brand)
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest uppercase text-xs sm:text-sm whitespace-nowrap shadow-sm">
          {{ $brand['name'] ?? '' }}
        </div>
        @empty
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">MOËT & CHANDON</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">JAMESON</div>
        <div class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-500 dark:text-slate-400 font-serif font-bold tracking-widest text-xs sm:text-sm whitespace-nowrap">GREY GOOSE</div>
        @endforelse
      </div>
    </div>

  </div>
</section>

<!-- ================= 7. KOLEKSI TERFAVORIT (Product) ================= -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
  <div class="mb-6 sm:mb-8">
    <h2 class="text-xl sm:text-3xl font-serif font-bold text-slate-900 dark:text-white">Paling Sering Diburu Sobat KT</h2>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">Nih rekomendasi botol paling juara yang lagi hits minggu ini.</p>
  </div>

  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
    @forelse($products as $product)
    <x-product-card :product="$product" />
    @empty
    <div class="col-span-full py-12 text-center text-slate-400 text-sm">
      Belum ada produk yang tersedia saat ini.
    </div>
    @endforelse
  </div>
</section>

<!-- ================= 8. KUNJUNGI TOKO OFFLINE KAMI (Store) ================= -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-6 border-t border-slate-200/50 dark:border-slate-800/50">
  <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
    <div>
      <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 dark:text-white">Mau Mampir Langsung ke Toko KT?</h2>
      <p class="text-xs sm:text-sm text-slate-500 mt-1">Bisa banget! Pilih opsi 'Store Pickup' saat checkout kalau mau ambil sendiri tanpa ongkir.</p>
    </div>
    <a href="{{ route('catalog.index') }}" class="self-center sm:w-auto text-center text-xs sm:text-sm font-semibold text-amber-500 border border-amber-500/50 rounded-full px-6 py-2.5 hover:bg-amber-500 hover:text-slate-950 transition-colors shrink-0">
      Lihat Semua Cabang
    </a>
  </div>

  @php
  // Acak data toko dan ambil maksimal 3 toko saja
  $randomStores = collect($stores)->shuffle()->take(3);
  @endphp

  <!-- Grid 1 Row 3 Columns -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    @forelse($randomStores as $store)
    @php
    $openTime = !empty($store['open_time']) ? date('H:i', strtotime($store['open_time'])) : '10:00';
    $closeTime = !empty($store['close_time']) ? date('H:i', strtotime($store['close_time'])) : '22:00';
    $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode(($store['name'] ?? '') . ' ' . ($store['address'] ?? ''));
    @endphp

    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between hover:border-amber-500/50 transition-all duration-300 shadow-sm">
      <div>
        <!-- Header Card: Icon & Badge -->
        <div class="flex items-start justify-between gap-3 mb-3">
          <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-location-dot text-lg"></i>
          </div>
          @if(!empty($store['is_pickup_active']))
          <span class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] px-2.5 py-1 rounded-full font-bold border border-emerald-500/20">
            Pickup Ready
          </span>
          @endif
        </div>

        <!-- Store Title & Address -->
        <h4 class="font-bold text-slate-900 dark:text-white text-base mb-1 line-clamp-1">
          {{ $store['name'] ?? '' }}
        </h4>
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-3 line-clamp-2">
          {{ $store['address'] ?? '' }}
        </p>
      </div>

      <!-- Footer Card: Time & Maps Link -->
      <div class="pt-3 border-t border-slate-100 dark:border-slate-800/60 mt-auto">
        <p class="text-[11px] text-amber-600 dark:text-amber-400 font-medium mb-2 flex items-center gap-1.5">
          <i class="fa-regular fa-clock"></i> Buka: {{ $openTime }} - {{ $closeTime }} WIB
        </p>
        <a href="{{ $mapsUrl }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-amber-500 dark:hover:text-amber-400 transition-colors">
          <i class="fa-solid fa-map-location-dot text-amber-500"></i> Buka di Google Maps
        </a>
      </div>
    </div>
    @empty
    <div class="col-span-full py-8 text-center text-xs text-slate-400 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800">
      Belum ada lokasi cabang yang tersedia saat ini.
    </div>
    @endforelse
  </div>
</section>

<!-- ================= 9. KATA MEREKA (Review) ================= -->
<section class="max-w-7xl mx-auto py-10 overflow-hidden">
  <div class="mb-6 text-center px-4">
    <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 dark:text-white">Apa Kata Sobat KT Lainnya?</h2>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">Cerita jujur dari mereka yang sudah nemenin malamnya pakai Kawan Tuang.</p>
  </div>

  @php
  $reviews = [
  [
  'name' => 'Dimas Prayoga',
  'product' => 'Johnnie Walker Black Label',
  'comment' => 'Pengiriman super cepat dan packaging instan sangat aman pake bubble wrap tebal. Botol 100% original, cukai resmi!',
  'rating' => 5,
  'avatar' => 'Dimas+Prayoga'
  ],
  [
  'name' => 'Budi Santoso',
  'product' => 'Macallan 12 Y.O Sherry Oak',
  'comment' => 'Pesan buat acara dadakan, 45 menit langsung sampai. Penjual ramah dan fast response banget pas ditanya rekomendasi.',
  'rating' => 5,
  'avatar' => 'Budi+Santoso'
  ],
  [
  'name' => 'Siti Nurhaliza',
  'product' => 'Penfolds Bin 389 Shiraz Cabernet',
  'comment' => 'Wine dikemas dalam temperatur ruangan yang terjaga. Rasa authentic dan dapet bonus opener juga dari Kawan Tuang!',
  'rating' => 5,
  'avatar' => 'Siti+Nurhaliza'
  ],
  [
  'name' => 'Reza Rahadian',
  'product' => 'Hendrick\'s Gin 750ml',
  'comment' => 'Dapat harga promo terbaik se-Jabodetabek. Selalu langganan di KT kalau lagi butuh stok buat weekend santai.',
  'rating' => 5,
  'avatar' => 'Reza+Rahadian'
  ],
  [
  'name' => 'Andi Wijaya',
  'product' => 'Chivas Regal 12 Y.O',
  'comment' => 'Layanan Store Pickup nya juara banget! Tinggal ambil tanpa antre sama sekali. Mantap Kawan Tuang!',
  'rating' => 5,
  'avatar' => 'Andi+Wijaya'
  ],
  ];
  @endphp

  <!-- Podium Slider Container -->
  <div id="review-slider" class="flex gap-3 sm:gap-6 overflow-x-auto py-8 px-[15vw] sm:px-[30vw] scrollbar-hide snap-x snap-mandatory scroll-smooth items-center">
    @foreach($reviews as $index => $review)
    @php
    // Menentukan item tengah default (index ke-2 dari 5 item)
    $isCenterDefault = ($index === 2);
    @endphp
    <div id="review-card-{{ $index }}"
      class="snap-center shrink-0 w-[260px] sm:w-[320px] bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-md relative transition-all duration-300 flex flex-col justify-between
                  {{ $isCenterDefault ? 'scale-105 z-10 border-amber-500/50 shadow-amber-500/10 opacity-100' : 'scale-90 opacity-60 hover:opacity-100 hover:scale-95' }}">

      <div>
        <!-- Rating Stars -->
        <div class="flex gap-1 text-amber-400 text-xs mb-3">
          @for($i = 0; $i < $review['rating']; $i++)
            <i class="fa-solid fa-star"></i>
            @endfor
        </div>

        <!-- Review Comment -->
        <p class="text-slate-600 dark:text-slate-300 text-xs leading-relaxed mb-6 font-medium italic line-clamp-4">
          "{{ $review['comment'] }}"
        </p>
      </div>

      <!-- User Profile -->
      <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/60 mt-auto">
        <img src="https://ui-avatars.com/api/?name={{ $review['avatar'] }}&background=f59e0b&color=fff&bold=true"
          alt="{{ $review['name'] }}"
          class="w-9 h-9 rounded-full border-2 border-white dark:border-slate-800 shadow-sm shrink-0">
        <div class="overflow-hidden">
          <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $review['name'] }}</h4>
          <p class="text-[10px] text-slate-400 truncate">Verified Buyer • {{ $review['product'] }}</p>
        </div>
      </div>

    </div>
    @endforeach
  </div>
</section>

@endsection
@push('scripts')
<script>
  // Force browser untuk selalu scroll ke paling atas saat pertama kali load / refresh
  if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
  }

  window.addEventListener('beforeunload', function() {
    window.scrollTo(0, 0);
  });

  document.addEventListener('DOMContentLoaded', function() {
    // Memastikan posisi scroll langsung ke koordinat (0, 0)
    setTimeout(function() {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'instant'
      });
    }, 10);
  });

  // Hero Slider Specific Logic
  let currentSlide = 0;
  const heroSlider = document.getElementById('hero-slider');
  const totalSlides = 2;

  function updateHeroSlider() {
    if (heroSlider) {
      heroSlider.style.transform = `translateX(-${currentSlide * 100}%)`;
    }
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateHeroSlider();
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateHeroSlider();
  }

  setInterval(nextSlide, 10000);
</script>
@endpush