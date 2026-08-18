<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kawan Tuang - Premium Beverage Store</title>

  <!-- Google Fonts: Plus Jakarta Sans (UI) & Playfair Display (Heading) -->
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet">

  <!-- Tailwind CSS & Config -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            serif: ['"Playfair Display"', 'serif'],
          },
          animation: {
            'float-slow': 'float 6s ease-in-out infinite',
            'float-fast': 'float 4s ease-in-out infinite',
            'marquee-left': 'marquee-left 40s linear infinite',
            'marquee-right': 'marquee-right 40s linear infinite',
          },
          keyframes: {
            float: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-15px)' },
            },
            'marquee-left': {
              '0%': { transform: 'translateX(0)' },
              '100%': { transform: 'translateX(-50%)' },
            },
            'marquee-right': {
              '0%': { transform: 'translateX(-50%)' },
              '100%': { transform: 'translateX(0)' },
            }
          }
        }
      }
    }
  </script>

  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>

<body
  class="bg-[#F8F9FA] text-slate-800 dark:bg-[#0B0F19] dark:text-slate-200 font-sans transition-colors duration-500 selection:bg-amber-500 selection:text-white overflow-x-hidden pb-24 md:pb-0">

  <!-- 21+ Verification Banner -->
  <div
    class="bg-amber-100 text-amber-900 dark:bg-amber-500/10 dark:text-amber-400 px-4 py-2 text-[11px] sm:text-xs text-center font-medium tracking-wide">
    <i class="fa-solid fa-triangle-exclamation me-1"></i> Halo Sobat KT! Pastikan usia kamu sudah 21+ ya sebelum
    menjelajah. Nikmati minuman secara bertanggung jawab.
  </div>

  <!-- HEADER NAVBAR -->
  <header
    class="sticky top-0 z-40 bg-white/70 dark:bg-[#0B0F19]/70 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">

      <!-- Brand Logo KT -->
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div
          class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-600 to-amber-300 flex items-center justify-center font-serif font-bold text-slate-950 text-xl shadow-lg shadow-amber-500/30 group-hover:rotate-12 transition-transform duration-300">
          KT</div>
        <span class="text-2xl font-serif font-bold tracking-tight text-slate-900 dark:text-white">Kawan<span
            class="text-amber-500 dark:text-amber-400 italic">Tuang</span></span>
      </a>

      <!-- Search Bar -->
      <div class="hidden md:flex flex-1 max-w-md relative group">
        <input type="text" placeholder="Mau cari teman minum apa malam ini? (Contoh: Whiskey, Wine)"
          class="w-full bg-slate-100/50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-full py-2.5 pl-12 pr-4 outline-none border border-transparent focus:border-amber-500/50 focus:bg-white dark:focus:bg-slate-900 transition-all duration-300">
        <i
          class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
      </div>

      <!-- Actions -->
      <div class="flex items-center gap-2 sm:gap-4">
        <!-- Theme Toggle -->
        <button id="theme-toggle"
          class="p-2.5 rounded-full text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-slate-800 transition-all"
          title="Ubah Tema">
          <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-amber-400 text-lg hidden"></i>
          <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-lg"></i>
        </button>

        <!-- Cart Icon -->
        <a href="{{ route('cart.index') }}" class="relative p-2.5 text-slate-400 hover:text-amber-500 transition-colors"
          title="Keranjang">
          <i class="fa-solid fa-bag-shopping text-xl"></i>
          <span
            class="absolute top-1 right-0 bg-amber-500 text-slate-950 font-bold text-[10px] w-4 h-4 rounded-full flex items-center justify-center shadow-sm shadow-amber-500/50">3</span>
        </a>

        <!-- Login Button -->
        <a href="{{ route('login') }}"
          class="hidden md:flex items-center gap-2 bg-slate-900 hover:bg-amber-500 dark:bg-white dark:hover:bg-amber-400 text-white hover:text-slate-900 dark:text-slate-900 font-semibold px-6 py-2.5 rounded-full text-sm transition-all duration-300 shadow-md">
          Masuk
        </a>
      </div>

    </div>
  </header>

  <!-- ================= HERO BANNER SLIDER ================= -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 md:pt-12">
    <div class="relative rounded-[2.5rem] overflow-hidden bg-slate-900 text-white shadow-2xl border border-slate-800">

      <!-- Background Glow Effects -->
      <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-rose-500/10 rounded-full blur-3xl"></div>
      </div>

      <!-- Slider Wrapper -->
      <div id="hero-slider" class="flex transition-transform duration-700 ease-in-out relative z-10">

        <!-- Slide 1: Kontainer Asli (Teks di Kiri, Foto di Kanan dengan Badge) -->
        <div class="min-w-full flex flex-col md:flex-row items-center justify-between p-8 md:p-16">
          <div class="space-y-6 max-w-xl">
            <span
              class="inline-block px-4 py-1.5 bg-white/10 backdrop-blur-md text-amber-300 border border-white/10 rounded-full text-xs font-semibold uppercase tracking-widest">
              ⚡ Delivery 1 Jam • Ready to Serve
            </span>
            <h1 class="text-4xl md:text-6xl font-serif leading-tight">
              Kawani Momen <br>
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-200 to-amber-500 italic">Sempurna
                Anda.</span>
            </h1>
            <p class="text-slate-300 text-sm md:text-base font-light leading-relaxed">
              Koleksi premium Whiskey, Fine Wine, dan Craft Beer 100% Original. Dikirim langsung ke depan pintu Anda
              dengan suhu yang terjaga.
            </p>
            <div class="flex items-center gap-4 pt-4">
              <a href="{{ route('catalog.index') }}"
                class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold px-8 py-3.5 rounded-full text-sm transition-all shadow-lg shadow-amber-500/30">
                Eksplorasi Sekarang
              </a>
            </div>
          </div>
          <div class="w-full md:w-1/2 flex justify-center p-8 md:p-0 relative mt-8 md:mt-0">
            <img src="https://images.unsplash.com/photo-1527281400683-1aae777175f8?auto=format&fit=crop&w=600&q=80"
              alt="Promo Whiskey"
              class="w-64 md:w-80 h-[320px] md:h-[400px] object-cover rounded-full shadow-2xl border-4 border-white/10 animate-float-slow">
            <div
              class="absolute bottom-12 -left-4 md:left-8 bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl animate-float-fast shadow-xl">
              <p class="text-amber-400 font-bold text-lg">Diskon 20%</p>
              <p class="text-xs text-slate-200">Koleksi Single Malt</p>
            </div>
          </div>
        </div>

        <!-- Slide 2: Full Banner Image (Gambar Banner Penuh) -->
        <div class="min-w-full relative h-[380px] md:h-[480px] flex items-center justify-center overflow-hidden">
          <img src="https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=1200&q=80"
            alt="Full Banner Promo" class="w-full h-full object-cover">
          <div
            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent flex flex-col justify-end p-8 md:p-14">
            <span
              class="px-3 py-1 bg-rose-500 text-white font-bold rounded-full text-[10px] uppercase tracking-widest w-max mb-2">Flash
              Sale Weekend</span>
            <h2 class="text-2xl md:text-4xl font-serif font-bold text-white mb-2">Diskon Spesial Koleksi Wine Pilihan
            </h2>
            <p class="text-xs md:text-sm text-slate-200 max-w-lg mb-4">Pesan sekarang sebelum kehabisan stok terbatas
              minggu ini khusus Sobat KT.</p>
            <a href="{{ route('catalog.index') }}"
              class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-6 py-2.5 rounded-full text-xs w-max transition-colors">Cek
              Promo Ini</a>
          </div>
        </div>

      </div>

      <!-- Slider Navigation Buttons -->
      <button onclick="prevSlide()"
        class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-amber-500 text-white flex items-center justify-center transition-colors z-20"><i
          class="fa-solid fa-chevron-left"></i></button>
      <button onclick="nextSlide()"
        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-amber-500 text-white flex items-center justify-center transition-colors z-20"><i
          class="fa-solid fa-chevron-right"></i></button>

    </div>
  </section>
  <!-- ================= END HERO BANNER ================= -->

  <!-- ================= 2. EVENT LIGHT PROMO / BEST DEAL (Flash Sale Countdown) ================= -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div
      class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-3xl p-6 md:p-8 text-slate-950 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
      <div class="space-y-2 text-center md:text-left">
        <span class="bg-slate-950 text-amber-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">🔥
          Best Deal Hari Ini</span>
        <h2 class="text-2xl md:text-3xl font-serif font-extxl font-bold">Lagi Cari yang Segar dengan Harga Spesial?</h2>
        <p class="text-xs md:text-sm text-slate-900 font-medium">Jangan sampai kelewatan potongan harga khusus untuk
          varian pilihan minggu ini!</p>
      </div>
      <div class="flex items-center gap-4">
        <div class="bg-slate-950 text-white px-4 py-3 rounded-2xl text-center">
          <span class="block text-xs text-amber-400 font-bold uppercase">Berakhir dalam</span>
          <span id="countdown" class="font-mono text-lg font-bold">04 : 32 : 18</span>
        </div>
        <a href="{{ route('catalog.index') }}"
          class="bg-slate-950 hover:bg-slate-900 text-amber-400 font-bold px-6 py-3.5 rounded-2xl text-sm transition-colors whitespace-nowrap shadow-md">
          Sikat Sekarang <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- ================= 3. PILIH SELERA ANDA (Category) ================= -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-end justify-between mb-6">
      <div>
        <h2 class="text-2xl font-serif font-bold text-slate-900 dark:text-white">Mau Teman Minum yang Mana Nih?</h2>
        <p class="text-sm text-slate-500 mt-1">Pilih kategori favorit yang pas buat nemenin santai kamu.</p>
      </div>
      <a href="{{ route('catalog.index') }}"
        class="text-sm text-amber-600 dark:text-amber-400 font-medium hover:underline flex items-center gap-1">
        Lihat Semua <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>

    <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
      <a href="{{ route('catalog.index') }}?category=beer" class="flex-shrink-0 group">
        <div
          class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900 flex items-center justify-center mb-3 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/30 transition-colors duration-300">
          <i
            class="fa-solid fa-beer-mug-empty text-2xl text-slate-400 group-hover:text-amber-500 transition-colors"></i>
        </div>
        <h3 class="text-center text-sm font-semibold text-slate-800 dark:text-slate-200">Beer</h3>
        <p class="text-center text-[10px] text-slate-400">45 Varian</p>
      </a>
      <a href="{{ route('catalog.index') }}?category=wine" class="flex-shrink-0 group">
        <div
          class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900 flex items-center justify-center mb-3 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/30 transition-colors duration-300">
          <i class="fa-solid fa-wine-glass text-2xl text-slate-400 group-hover:text-amber-500 transition-colors"></i>
        </div>
        <h3 class="text-center text-sm font-semibold text-slate-800 dark:text-slate-200">Wine</h3>
        <p class="text-center text-[10px] text-slate-400">120 Varian</p>
      </a>
      <a href="{{ route('catalog.index') }}?category=spirits" class="flex-shrink-0 group">
        <div
          class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900 flex items-center justify-center mb-3 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/30 transition-colors duration-300">
          <i class="fa-solid fa-whiskey-glass text-2xl text-slate-400 group-hover:text-amber-500 transition-colors"></i>
        </div>
        <h3 class="text-center text-sm font-semibold text-slate-800 dark:text-slate-200">Spirits</h3>
        <p class="text-center text-[10px] text-slate-400">85 Varian</p>
      </a>
      <a href="{{ route('catalog.index') }}?category=soju" class="flex-shrink-0 group">
        <div
          class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900 flex items-center justify-center mb-3 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/30 transition-colors duration-300">
          <i
            class="fa-solid fa-bottle-droplet text-2xl text-slate-400 group-hover:text-amber-500 transition-colors"></i>
        </div>
        <h3 class="text-center text-sm font-semibold text-slate-800 dark:text-slate-200">Soju</h3>
        <p class="text-center text-[10px] text-slate-400">32 Varian</p>
      </a>
    </div>
  </section>

  <!-- ================= 4. MINUM UNTUK ACARA APA? (Vibes) ================= -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
      <h2 class="text-3xl font-serif font-bold text-slate-900 dark:text-white">Malam Ini Agendanya Apa Nih?</h2>
      <p class="text-sm text-slate-500 mt-1">Biar gak salah pilih, yuk sesuaikan minuman dengan suasana acara kamu.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <a href="{{ route('catalog.index') }}?vibe=party" class="relative h-40 md:h-56 rounded-3xl overflow-hidden group">
        <img src="https://images.unsplash.com/photo-1516997180214-3a5c2f829f8f?auto=format&fit=crop&w=500&q=80"
          alt="Party" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
        <div
          class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-4">
          <h3 class="text-white font-serif font-bold text-lg">Crazy Party</h3>
          <p class="text-slate-300 text-[10px]">Tequila, Vodka, Jäger</p>
        </div>
      </a>
      <a href="{{ route('catalog.index') }}?vibe=chill" class="relative h-40 md:h-56 rounded-3xl overflow-hidden group">
        <img src="https://images.unsplash.com/photo-1549488344-c71b12b50428?auto=format&fit=crop&w=500&q=80" alt="Chill"
          class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
        <div
          class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-4">
          <h3 class="text-white font-serif font-bold text-lg">Netflix & Chill</h3>
          <p class="text-slate-300 text-[10px]">Craft Beer, Soju, RTD</p>
        </div>
      </a>
      <a href="{{ route('catalog.index') }}?vibe=romantic" class="relative h-40 md:h-56 rounded-3xl overflow-hidden group">
        <img src="https://images.unsplash.com/photo-1510018572596-a4039ce4a462?auto=format&fit=crop&w=500&q=80"
          alt="Dinner" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
        <div
          class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-4">
          <h3 class="text-white font-serif font-bold text-lg">Romantic Dinner</h3>
          <p class="text-slate-300 text-[10px]">Red Wine, Champagne</p>
        </div>
      </a>
      <a href="{{ route('catalog.index') }}?vibe=gift" class="relative h-40 md:h-56 rounded-3xl overflow-hidden group">
        <img src="https://images.unsplash.com/photo-1513201099705-a9746e1e201f?auto=format&fit=crop&w=500&q=80"
          alt="Gift" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
        <div
          class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-4">
          <h3 class="text-white font-serif font-bold text-lg">Gift & Hampers</h3>
          <p class="text-slate-300 text-[10px]">Single Malt, Premium Box</p>
        </div>
      </a>
    </div>
  </section>

  <!-- ================= 5. NEW: KEUNTUNGAN BELANJA DI KT (Why Choose Us & Point of Sale) ================= -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
      <div class="text-center max-w-xl mx-auto mb-10">
        <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 dark:text-white">Kenapa Harus Belanja di
          Kawan Tuang?</h2>
        <p class="text-xs md:text-sm text-slate-500 mt-2">Biar kamu gak ragu, ini nih keistimewaan yang bakal kamu
          dapetin setiap kali order.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="flex items-start gap-4">
          <div
            class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <div>
            <h4 class="font-bold text-slate-900 dark:text-white mb-1">100% Original Bergaransi</h4>
            <p class="text-xs text-slate-500 leading-relaxed">Gak usah takut barang palsu. Semua produk diimpor langsung
              dari distributor resmi bersertifikat.</p>
          </div>
        </div>
        <div class="flex items-start gap-4">
          <div
            class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-bolt"></i>
          </div>
          <div>
            <h4 class="font-bold text-slate-900 dark:text-white mb-1">Kirim Kilat 1 Jam Sampai</h4>
            <p class="text-xs text-slate-500 leading-relaxed">Minuman langsung diantar pakai kurir instant dalam kondisi
              dingin terjaga siap teguk.</p>
          </div>
        </div>
        <div class="flex items-start gap-4">
          <div
            class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-gift"></i>
          </div>
          <div>
            <h4 class="font-bold text-slate-900 dark:text-white mb-1">Poin & Reward Member</h4>
            <p class="text-xs text-slate-500 leading-relaxed">Setiap transaksi otomatis ngumpulin poin yang bisa kamu
              tukar voucher diskon di pesanan berikutnya!</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= 6. BRAND & KOLEKSI GLOBAL (Brand Carousel) ================= -->
  <section class="py-10 relative overflow-hidden bg-[#F8F9FA] dark:bg-[#0B0F19]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
      <h2 class="text-xl font-serif font-bold text-slate-900 dark:text-white text-center">Brand Global Pilihan Sobat KT
      </h2>
    </div>

    <div
      class="absolute inset-y-0 left-0 w-16 md:w-32 bg-gradient-to-r from-[#F8F9FA] dark:from-[#0B0F19] to-transparent z-10 pointer-events-none">
    </div>
    <div
      class="absolute inset-y-0 right-0 w-16 md:w-32 bg-gradient-to-l from-[#F8F9FA] dark:from-[#0B0F19] to-transparent z-10 pointer-events-none">
    </div>

    <div class="flex flex-col gap-5 relative z-0">
      <!-- Baris 1: Ke Kanan -->
      <div class="flex w-max animate-marquee-right hover:[animation-play-state:paused]">
        <div class="flex gap-5 px-2.5">
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            MACALLAN</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            PENFOLDS</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            HENDRICK'S</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            GUINNESS</div>
        </div>
        <div class="flex gap-5 px-2.5">
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            MACALLAN</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            PENFOLDS</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            HENDRICK'S</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            GUINNESS</div>
        </div>
      </div>
      <!-- Baris 2: Ke Kiri -->
      <div class="flex w-max animate-marquee-left hover:[animation-play-state:paused]">
        <div class="flex gap-5 px-2.5">
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            MOËT & CHANDON</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            JOHNNIE WALKER</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            JÄGERMEISTER</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            ABSOLUT</div>
        </div>
        <div class="flex gap-5 px-2.5">
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            MOËT & CHANDON</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            JOHNNIE WALKER</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            JÄGERMEISTER</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            ABSOLUT</div>
        </div>
      </div>
      <!-- Baris 3: Ke Kanan -->
      <div class="flex w-max animate-marquee-right hover:[animation-play-state:paused]">
        <div class="flex gap-5 px-2.5">
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            BAILEYS</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            CORONA</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            HEINEKEN</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            JINRO</div>
        </div>
        <div class="flex gap-5 px-2.5">
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            BAILEYS</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            CORONA</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            HEINEKEN</div>
          <div
            class="px-8 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 font-serif font-bold tracking-widest">
            JINRO</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= 7. KOLEKSI TERFAVORIT (Product) ================= -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
      <h2 class="text-3xl font-serif font-bold text-slate-900 dark:text-white">Paling Sering Diburu Sobat KT</h2>
      <p class="text-sm text-slate-500 mt-1">Nih rekomendasi botol paling juara yang lagi hits minggu ini.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">

      <!-- Product Card 1 -->
      <div class="group cursor-pointer">
        <div
          class="relative aspect-[4/5] rounded-3xl overflow-hidden bg-white border border-slate-100 dark:border-slate-800 dark:bg-slate-900 mb-4 shadow-sm">
          <img src="https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80"
            alt="Beer"
            class="w-full h-full object-cover mix-blend-multiply dark:mix-blend-normal group-hover:scale-105 transition-transform duration-500">
          <div
            class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-slate-900 text-[10px] font-bold px-3 py-1 rounded-full z-10 shadow-sm">
            TERLARIS</div>
          <button
            class="absolute bottom-3 right-3 w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center opacity-100 md:opacity-0 md:group-hover:opacity-100 md:translate-y-4 md:group-hover:translate-y-0 transition-all duration-300 shadow-lg">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
        <div>
          <div class="flex items-center justify-between">
            <span class="text-[10px] uppercase font-semibold text-amber-600 dark:text-amber-400 tracking-wider">Beer &
              Cider</span>
            <span
              class="text-[10px] bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 px-2 py-0.5 rounded-full font-bold">Sisa
              5 Botol</span>
          </div>
          <h3
            class="text-base font-serif font-bold text-slate-900 dark:text-white mt-1 group-hover:text-amber-500 transition-colors">
            Craft Lager Premium 500ml</h3>
          <div class="flex items-center gap-1 my-2">
            <i class="fa-solid fa-star text-amber-400 text-xs"></i>
            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">4.8 (120 Ulasan)</span>
          </div>
          <div class="flex items-center gap-2">
            <p class="text-sm font-bold text-slate-900 dark:text-white">Rp 65.000</p>
            <span class="text-xs text-slate-400 line-through">Rp 75.000</span>
          </div>
        </div>
      </div>

      <!-- Product Card 2 -->
      <div class="group cursor-pointer">
        <div
          class="relative aspect-[4/5] rounded-3xl overflow-hidden bg-white border border-slate-100 dark:border-slate-800 dark:bg-slate-900 mb-4 shadow-sm">
          <img src="https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&w=400&q=80"
            alt="Wine"
            class="w-full h-full object-cover mix-blend-multiply dark:mix-blend-normal group-hover:scale-105 transition-transform duration-500">
          <button
            class="absolute bottom-3 right-3 w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center opacity-100 md:opacity-0 md:group-hover:opacity-100 md:translate-y-4 md:group-hover:translate-y-0 transition-all duration-300 shadow-lg">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
        <div>
          <div class="flex items-center justify-between">
            <span class="text-[10px] uppercase font-semibold text-amber-600 dark:text-amber-400 tracking-wider">Fine
              Wine</span>
            <span
              class="text-[10px] bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-0.5 rounded-full font-bold">Stok
              Aman</span>
          </div>
          <h3
            class="text-base font-serif font-bold text-slate-900 dark:text-white mt-1 group-hover:text-amber-500 transition-colors">
            Cabernet Sauvignon 750ml</h3>
          <div class="flex items-center gap-1 my-2">
            <i class="fa-solid fa-star text-amber-400 text-xs"></i>
            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">4.9 (85 Ulasan)</span>
          </div>
          <div class="flex items-center gap-2">
            <p class="text-sm font-bold text-slate-900 dark:text-white">Rp 420.000</p>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- ================= 8. KUNJUNGI TOKO OFFLINE KAMI (Store) ================= -->
  <section
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-8 border-t border-slate-200/50 dark:border-slate-800/50">
    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
      <div>
        <h2 class="text-3xl font-serif font-bold text-slate-900 dark:text-white">Mau Mampir Langsung ke Toko KT?</h2>
        <p class="text-sm text-slate-500 mt-1">Bisa banget! Pilih opsi 'Store Pickup' saat checkout kalau mau ambil
          sendiri tanpa ongkir.</p>
      </div>
      <button
        class="text-sm font-semibold text-amber-500 border border-amber-500 rounded-full px-6 py-2 hover:bg-amber-500 hover:text-white transition-colors">Lihat
        Semua Cabang</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div
        class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 flex items-start gap-4">
        <div
          class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-location-dot text-xl"></i>
        </div>
        <div>
          <div class="flex items-center gap-2 mb-1">
            <h4 class="font-bold text-slate-900 dark:text-white">Kawan Tuang - Senopati</h4>
            <span
              class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 text-[10px] px-2 py-0.5 rounded-full font-bold">Pusat</span>
          </div>
          <p class="text-xs text-slate-500 leading-relaxed mb-3">Jl. Senopati No.45, Kebayoran Baru, Jakarta Selatan.
            Buka setiap hari 10:00 - 24:00.</p>
          <a href="#" class="text-xs font-bold text-amber-500 hover:underline"><i class="fa-solid fa-map"></i> Buka di
            Google Maps</a>
        </div>
      </div>

      <div
        class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 flex items-start gap-4">
        <div
          class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-location-dot text-xl"></i>
        </div>
        <div>
          <h4 class="font-bold text-slate-900 dark:text-white mb-1">Kawan Tuang - PIK</h4>
          <p class="text-xs text-slate-500 leading-relaxed mb-3">Ruko Golf Island Blok A No.12, Pantai Indah Kapuk,
            Jakarta Utara. Buka setiap hari 12:00 - 02:00.</p>
          <a href="#" class="text-xs font-bold text-amber-500 hover:underline"><i class="fa-solid fa-map"></i> Buka di
            Google Maps</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= 9. KATA MEREKA (Review) ================= -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8 flex items-end justify-between">
      <div>
        <h2 class="text-3xl font-serif font-bold text-slate-900 dark:text-white">Apa Kata Sobat KT Lainnya?</h2>
        <p class="text-sm text-slate-500 mt-1">Cerita jujur dari mereka yang sudah nemenin malamnya pakai Kawan Tuang.
        </p>
      </div>
    </div>

    <div class="flex gap-6 overflow-x-auto pb-8 scrollbar-hide snap-x">
      <div
        class="snap-center shrink-0 w-80 md:w-96 bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-sm relative group">
        <div class="flex gap-1 text-amber-400 text-xs mb-4">
          <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
            class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
        </div>
        <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed mb-8 font-medium">
          "Pengiriman super cepat! Pesan Macallan buat acara dadakan, 45 menit sampai dengan packaging yang sangat aman
          dan rapih."
        </p>
        <div class="flex items-center gap-3 mt-auto">
          <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=f59e0b&color=fff&bold=true" alt="User"
            class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-800">
          <div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-white">Budi Santoso</h4>
            <p class="text-[10px] text-slate-500">Verified Buyer • Macallan 12 Y.O</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer
    class="bg-white dark:bg-[#060910] border-t border-slate-200/50 dark:border-slate-800/50 mt-4 pt-16 pb-24 md:pb-12 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
        <div class="md:col-span-1">
          <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6">
            <div
              class="w-8 h-8 rounded-full bg-gradient-to-tr from-amber-600 to-amber-300 flex items-center justify-center font-serif font-bold text-slate-950 text-sm">
              KT</div>
            <span class="text-xl font-serif font-bold tracking-tight text-slate-900 dark:text-white">Kawan<span
                class="text-amber-500 italic">Tuang</span></span>
          </a>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
            Penyedia minuman premium, fine wine, dan craft beer terpercaya. Selalu siap nemenin setiap momen berharga
            kamu.
          </p>
        </div>

        <div>
          <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-5 uppercase tracking-wider">Katalog Produk</h4>
          <ul class="space-y-3 text-sm text-slate-500 dark:text-slate-400">
            <li><a href="#">Whiskey & Single Malt</a></li>
            <li><a href="#">Fine Wine & Champagne</a></li>
            <li><a href="#">Promo Spesial</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-5 uppercase tracking-wider">Bantuan</h4>
          <ul class="space-y-3 text-sm text-slate-500 dark:text-slate-400">
            <li><a href="#">Cara Pemesanan</a></li>
            <li><a href="#">Layanan Pengiriman</a></li>
            <li><a href="#">Syarat & Ketentuan</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-5 uppercase tracking-wider">Pembayaran Aman
          </h4>
          <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 leading-relaxed">Transaksi dijamin terenkripsi aman
            didukung penuh oleh Xendit.</p>
          <div class="flex flex-wrap gap-2 mb-6">
            <span
              class="bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded px-2.5 py-1.5 text-xs font-bold text-indigo-700 dark:text-indigo-400">VISA</span>
            <span
              class="bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded px-2.5 py-1.5 text-xs font-bold text-teal-600 dark:text-teal-400">QRIS</span>
          </div>
        </div>
      </div>
      <div
        class="border-t border-slate-200/50 dark:border-slate-800/50 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs font-medium text-slate-500 dark:text-slate-400">
        <p>&copy; 2026 Kawan Tuang (KT). All rights reserved.</p>
        <p class="flex items-center gap-2">Drink Responsibly <i class="fa-solid fa-wine-glass text-amber-500"></i></p>
      </div>
    </div>
  </footer>

  <!-- MOBILE BOTTOM NAVBAR -->
  <nav
    class="fixed bottom-0 left-0 right-0 z-50 bg-white/90 dark:bg-[#0B0F19]/90 backdrop-blur-xl border-t border-slate-100 dark:border-slate-800/80 md:hidden pb-safe transition-colors">
    <div class="flex items-center justify-around px-2 py-3">
      <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-amber-500">
        <i class="fa-solid fa-house text-xl"></i><span class="text-[10px] font-medium">Beranda</span>
      </a>
      <a href="{{ route('catalog.index') }}" class="flex flex-col items-center gap-1 text-slate-400">
        <i class="fa-solid fa-wine-glass-empty text-xl"></i><span class="text-[10px] font-medium">Katalog</span>
      </a>
      <a href="{{ route('orders.index') }}" class="flex flex-col items-center gap-1 text-slate-400">
        <i class="fa-solid fa-receipt text-xl"></i><span class="text-[10px] font-medium">Pesanan</span>
      </a>
      <a href="{{ route('profile.index') }}" class="flex flex-col items-center gap-1 text-slate-400">
        <i class="fa-regular fa-user text-xl"></i><span class="text-[10px] font-medium">Akun</span>
      </a>
    </div>
  </nav>

  <!-- JAVASCRIPT LOGIC (Theme & Slider & Countdown) -->
  <script>
    // Theme Toggle
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');

    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
      lightIcon.classList.remove('hidden'); darkIcon.classList.add('hidden');
    } else {
      document.documentElement.classList.remove('dark');
      darkIcon.classList.add('hidden'); lightIcon.classList.remove('hidden');
    }

    themeToggleBtn.addEventListener('click', function () {
      lightIcon.classList.toggle('hidden'); darkIcon.classList.toggle('hidden');
      if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark'); localStorage.setItem('color-theme', 'light');
      } else {
        document.documentElement.classList.add('dark'); localStorage.setItem('color-theme', 'dark');
      }
    });

    // Hero Slider Logic
    let currentSlide = 0;
    const heroSlider = document.getElementById('hero-slider');
    const totalSlides = 2;

    function updateHeroSlider() {
      heroSlider.style.transform = `translateX(-${currentSlide * 100}%)`;
    }

    function nextSlide() {
      currentSlide = (currentSlide + 1) % totalSlides;
      updateHeroSlider();
    }

    function prevSlide() {
      currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
      updateHeroSlider();
    }

    // Auto slide setiap 6 detik
    setInterval(nextSlide, 10000);
  </script>
</body>

</html>