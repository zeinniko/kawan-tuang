<!DOCTYPE html>
<html lang="id" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Craft Lager Premium 500ml - Kawan Tuang</title>
  
  <!-- Tailwind CSS & Config for Dark Mode -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
    }
  </script>

  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans pb-28 md:pb-12 transition-colors duration-300">

  <!-- 21+ Age Banner Disclaimer -->
  <div class="bg-amber-100 border-b border-amber-200 text-amber-900 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 px-4 py-1.5 text-xs text-center font-medium">
    <i class="fa-solid fa-triangle-exclamation me-1"></i> Khusus Usia 21+. Nikmati Minuman Anda Secara Bertanggung Jawab.
  </div>

  <!-- MAIN NAVBAR -->
  <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
      
      <!-- Back Button & Brand -->
      <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="p-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
          <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <a href="{{ route('home') }}" class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-amber-600 to-amber-400 flex items-center justify-center font-bold text-slate-950 text-base shadow-md shadow-amber-500/20">
            TT
          </div>
          <span class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-white hidden sm:inline">Kawan<span class="text-amber-500 dark:text-amber-400">Tuang</span></span>
        </a>
      </div>

      <!-- Actions -->
      <div class="flex items-center gap-3">
        <!-- DARK / LIGHT MODE TOGGLE -->
        <button id="theme-toggle" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Ubah Mode">
          <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-amber-400 text-lg hidden"></i>
          <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-slate-600 text-lg"></i>
        </button>

        <!-- Share Button -->
        <button class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <i class="fa-solid fa-share-nodes text-lg"></i>
        </button>

        <!-- Cart Icon -->
        <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-600 dark:text-slate-300 hover:text-amber-500 dark:hover:text-amber-400 transition-colors">
          <i class="fa-solid fa-bag-shopping text-xl"></i>
          <span class="absolute top-1 right-1 bg-amber-500 text-slate-950 font-bold text-[10px] w-4 h-4 rounded-full flex items-center justify-center">3</span>
        </a>
      </div>

    </div>
  </header>

  <!-- BREADCRUMB -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <nav class="flex text-xs text-slate-500 dark:text-slate-400 gap-2">
      <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
      <span>/</span>
      <a href="{{ route('catalog.index') }}" class="hover:underline">Beer & Cider</a>
      <span>/</span>
      <span class="text-slate-900 dark:text-slate-200 font-medium truncate">Craft Lager Premium 500ml</span>
    </nav>
  </div>

  <!-- PRODUCT DETAIL CONTENT -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      
      <!-- LEFT: PRODUCT IMAGE & GALLERY -->
      <div class="space-y-4">
        <!-- Main Display Image -->
        <div class="relative aspect-square rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center p-8 shadow-sm overflow-hidden">
          <span class="absolute top-4 left-4 bg-rose-500 text-white text-xs font-bold px-3 py-1 rounded-lg shadow-md z-10">BEST SELLER</span>
          <span class="absolute top-4 right-4 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-xs font-semibold px-2.5 py-1 rounded-lg z-10">
            <i class="fa-solid fa-circle-check me-1"></i>100% Original
          </span>
          <i class="fa-solid fa-wine-bottle text-9xl text-amber-500/80 dark:text-amber-500/60 drop-shadow-2xl"></i>
        </div>

        <!-- Thumbnails -->
        <div class="flex gap-3">
          <button class="w-20 h-20 rounded-2xl bg-white dark:bg-slate-900 border-2 border-amber-500 flex items-center justify-center text-amber-500">
            <i class="fa-solid fa-wine-bottle text-2xl"></i>
          </button>
          <button class="w-20 h-20 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-400 hover:border-amber-400 transition-colors">
            <i class="fa-solid fa-glass-water text-2xl"></i>
          </button>
          <button class="w-20 h-20 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-400 hover:border-amber-400 transition-colors">
            <i class="fa-solid fa-box text-2xl"></i>
          </button>
        </div>
      </div>

      <!-- RIGHT: PRODUCT INFO & SPECS -->
      <div class="space-y-6">
        
        <!-- Category & Title -->
        <div>
          <span class="text-xs uppercase font-bold text-amber-600 dark:text-amber-400 tracking-wider">Beer & Cider</span>
          <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">Craft Lager Premium 500ml</h1>
          
          <!-- Rating & Sold Count -->
          <div class="flex items-center gap-3 mt-2">
            <div class="flex items-center gap-1 bg-amber-500/10 px-2.5 py-1 rounded-lg">
              <i class="fa-solid fa-star text-amber-400 text-xs"></i>
              <span class="text-xs font-bold text-amber-600 dark:text-amber-400">4.8</span>
            </div>
            <span class="text-xs text-slate-500 dark:text-slate-400">(120 Ulasan)</span>
            <span class="text-slate-300 dark:text-slate-700">•</span>
            <span class="text-xs text-slate-500 dark:text-slate-400">Terjual 500+ botol</span>
          </div>
        </div>

        <!-- Price Section -->
        <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
          <div class="flex items-baseline gap-2">
            <span class="text-2xl md:text-3xl font-black text-amber-600 dark:text-amber-400">Rp 65.000</span>
            <span class="text-sm text-slate-400 line-through">Rp 75.000</span>
            <span class="text-xs bg-rose-500/10 text-rose-500 font-bold px-2 py-0.5 rounded">Diskon 13%</span>
          </div>
          <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
            <i class="fa-solid fa-truck-fast text-amber-500 me-1"></i> Gratis ongkir instant untuk pembelian min. Rp 300.000
          </p>
        </div>

        <!-- ALCOHOL SPECS GRID (ABV, Volume, Origin, Type) -->
        <div>
          <h3 class="text-xs font-bold uppercase text-slate-400 mb-2">Spesifikasi Minuman</h3>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Kadar Alkohol</span>
              <span class="text-sm font-bold text-amber-600 dark:text-amber-400">4.8% ABV</span>
            </div>
            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Volume</span>
              <span class="text-sm font-bold text-slate-900 dark:text-white">500 ml</span>
            </div>
            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Asal Negara</span>
              <span class="text-sm font-bold text-slate-900 dark:text-white">Indonesia</span>
            </div>
            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-center">
              <span class="text-[10px] text-slate-400 uppercase font-semibold block">Gaya Beer</span>
              <span class="text-sm font-bold text-slate-900 dark:text-white">Craft Lager</span>
            </div>
          </div>
        </div>

        <!-- TASTING NOTES (Profil Rasa) -->
        <div class="p-4 bg-amber-500/5 border border-amber-500/20 rounded-2xl space-y-2">
          <h4 class="text-xs font-bold uppercase text-amber-600 dark:text-amber-400 flex items-center gap-2">
            <i class="fa-solid fa-whiskey-glass"></i> Tasting Notes / Profil Rasa
          </h4>
          <div class="flex flex-wrap gap-2 pt-1">
            <span class="px-3 py-1 bg-white dark:bg-slate-900 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-800">🍋 Citrus Hint</span>
            <span class="px-3 py-1 bg-white dark:bg-slate-900 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-800">🌾 Golden Malt</span>
            <span class="px-3 py-1 bg-white dark:bg-slate-900 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-800">⚡ Crisp Finish</span>
            <span class="px-3 py-1 bg-white dark:bg-slate-900 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-800">🌿 Smooth Hops</span>
          </div>
        </div>

        <!-- Description -->
        <div class="space-y-2">
          <h3 class="text-sm font-bold text-slate-900 dark:text-white">Deskripsi Produk</h3>
          <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
            Craft Lager Premium diracik dengan murni menggunakan biji gandum pilihan dan hops impor berkualitas. Menghasilkan cita rasa tajam, segar, dengan aroma sitrus yang lembut di akhir tegukan. Sangat cocok dinikmati dingin saat santai bersama Kawan.
          </p>
        </div>

        <!-- DESKTOP ACTION BAR (Quantity & Cart Buttons) -->
        <div class="hidden md:flex items-center gap-4 pt-4 border-t border-slate-200 dark:border-slate-800">
          
          <!-- Quantity Counter -->
          <div class="flex items-center bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-1">
            <button class="w-9 h-9 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg font-bold text-lg">-</button>
            <input type="number" value="1" class="w-12 text-center bg-transparent text-sm font-bold text-slate-900 dark:text-white focus:outline-none">
            <button class="w-9 h-9 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg font-bold text-lg">+</button>
          </div>

          <!-- Add to Cart Button -->
          <button class="flex-1 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-900 dark:text-white font-bold py-3 px-6 rounded-xl text-sm transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-bag-shopping"></i> + Keranjang
          </button>

          <!-- Buy Now Button -->
          <a href="{{ route('cart.index') }}" class="flex-1 bg-amber-500 hover:bg-amber-600 dark:bg-amber-400 dark:hover:bg-amber-500 text-slate-950 font-bold py-3 px-6 rounded-xl text-sm transition-all text-center shadow-lg shadow-amber-500/20">
            Beli Sekarang
          </a>
        </div>

      </div>
    </div>
  </main>

  <!-- MOBILE STICKY BOTTOM ACTION BAR (Floating CTA di Mobile) -->
  <div class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 md:hidden p-4">
    <div class="flex items-center justify-between gap-3 max-w-md mx-auto">
      <div>
        <span class="text-[10px] text-slate-400 block">Total Harga</span>
        <span class="text-base font-extrabold text-amber-600 dark:text-amber-400">Rp 65.000</span>
      </div>
      <div class="flex items-center gap-2">
        <button class="bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white p-3 rounded-xl font-bold">
          <i class="fa-solid fa-bag-shopping"></i>
        </button>
        <a href="{{ route('cart.index') }}" class="bg-amber-500 dark:bg-amber-400 text-slate-950 px-5 py-3 rounded-xl font-bold text-xs">
          Beli Sekarang
        </a>
      </div>
    </div>
  </div>

  <!-- TOGGLE THEME JS -->
  <script>
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');

    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
      lightIcon.classList.remove('hidden');
      darkIcon.classList.add('hidden');
    } else {
      document.documentElement.classList.remove('dark');
      darkIcon.classList.remove('hidden');
      lightIcon.classList.add('hidden');
    }

    themeToggleBtn.addEventListener('click', function() {
      lightIcon.classList.toggle('hidden');
      darkIcon.classList.toggle('hidden');

      if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('color-theme', 'light');
      } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('color-theme', 'dark');
      }
    });
  </script>
</body>
</html>