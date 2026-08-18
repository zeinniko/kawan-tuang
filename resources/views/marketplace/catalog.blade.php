<!DOCTYPE html>
<html lang="id" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Katalog Produk Lengkap - Kawan Tuang</title>
  
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
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans pb-24 md:pb-12 transition-colors duration-300">

  <!-- 21+ Age Banner Disclaimer -->
  <div class="bg-amber-100 border-b border-amber-200 text-amber-900 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 px-4 py-1.5 text-xs text-center font-medium">
    <i class="fa-solid fa-triangle-exclamation me-1"></i> Khusus Usia 21+. Nikmati Minuman Anda Secara Bertanggung Jawab.
  </div>

  <!-- DESKTOP & MOBILE MAIN NAVBAR -->
  <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-3">
      
      <!-- Brand Logo -->
      <a href="{{ route('home') }}" class="flex items-center gap-2">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-600 to-amber-400 flex items-center justify-center font-bold text-slate-950 text-lg shadow-lg shadow-amber-500/20">
          TT
        </div>
        <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">Kawan<span class="text-amber-500 dark:text-amber-400">Tuang</span></span>
      </a>

      <!-- Search Bar (Desktop & Tablet) -->
      <div class="hidden sm:flex flex-1 max-w-md relative">
        <input type="text" placeholder="Cari beer, wine, whiskey, atau cocktail..." class="w-full bg-slate-100 dark:bg-slate-800/90 text-sm text-slate-900 dark:text-slate-200 placeholder-slate-400 rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-amber-500 border border-slate-200 dark:border-slate-700/60 transition-colors">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-slate-400 text-sm"></i>
      </div>

      <!-- Actions (Theme Toggle & Cart Side-by-Side) -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Dark / Light Mode Toggle -->
        <button id="theme-toggle" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Ubah Mode">
          <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-amber-400 text-lg hidden"></i>
          <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-slate-600 text-lg"></i>
        </button>

        <!-- Cart Icon (Paling Kanan di Mobile) -->
        <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-600 dark:text-slate-300 hover:text-amber-500 dark:hover:text-amber-400 transition-colors" title="Keranjang">
          <i class="fa-solid fa-bag-shopping text-xl"></i>
          <span class="absolute top-1 right-1 bg-amber-500 text-slate-950 font-bold text-[10px] w-4 h-4 rounded-full flex items-center justify-center">3</span>
        </a>

        <!-- Login Button (Desktop Only) -->
        <button class="hidden md:flex items-center gap-2 bg-amber-500 hover:bg-amber-600 dark:bg-amber-400 dark:hover:bg-amber-500 text-slate-950 font-semibold px-4 py-2 rounded-full text-sm transition-all shadow-md shadow-amber-500/10 ms-1">
          <i class="fa-regular fa-user"></i> Masuk
        </button>
      </div>

    </div>
  </header>

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
      <div class="flex items-center gap-2">
        <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">Urutkan:</span>
        <select class="bg-white dark:bg-slate-900 text-xs font-semibold text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 focus:outline-none focus:ring-1 focus:ring-amber-500">
          <option>Terpopuler (Best Seller)</option>
          <option>Harga: Terendah ke Tertinggi</option>
          <option>Harga: Tertinggi ke Terendah</option>
          <option>Rating Tertinggi</option>
          <option>Varian Terbaru</option>
        </select>
      </div>
    </div>
  </div>

  <!-- SEARCH & FILTER SECTION -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- MOBILE SEARCH INPUT -->
    <div class="sm:hidden mb-4 relative">
      <input type="text" placeholder="Cari beer, wine, whiskey..." class="w-full bg-white dark:bg-slate-900 text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 rounded-xl py-2.5 pl-9 pr-4 border border-slate-200 dark:border-slate-800 focus:outline-none focus:ring-1 focus:ring-amber-500">
      <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
    </div>

    <!-- CATEGORY PILLS & FILTER BAR -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
      
      <!-- Category Pills -->
      <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none text-xs flex-1">
        <button class="px-4 py-2 rounded-full bg-amber-500 dark:bg-amber-400 text-slate-950 font-bold whitespace-nowrap shadow-sm">Semua Varian</button>
        <button class="px-4 py-2 rounded-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:border-amber-400 whitespace-nowrap">Beer & Cider</button>
        <button class="px-4 py-2 rounded-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:border-amber-400 whitespace-nowrap">Fine Wine</button>
        <button class="px-4 py-2 rounded-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:border-amber-400 whitespace-nowrap">Spirits & Whiskey</button>
        <button class="px-4 py-2 rounded-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:border-amber-400 whitespace-nowrap">Soju & Cocktail</button>
      </div>

      <!-- Quick ABV Filter -->
      <div class="flex items-center gap-2 text-xs">
        <span class="text-slate-400 text-[11px] hidden sm:inline">Kadar Alkohol:</span>
        <button class="px-3 py-1 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-lg border border-slate-200 dark:border-slate-800 text-[11px] font-medium hover:border-amber-400">&lt; 5% ABV</button>
        <button class="px-3 py-1 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-lg border border-slate-200 dark:border-slate-800 text-[11px] font-medium hover:border-amber-400">5% - 15% ABV</button>
        <button class="px-3 py-1 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-lg border border-slate-200 dark:border-slate-800 text-[11px] font-medium hover:border-amber-400">&gt; 15% ABV</button>
      </div>

    </div>

    <!-- CATALOG PRODUCT GRID (8 ITEMS) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
      
      <!-- Product 1 -->
      <div class="bg-white dark:bg-slate-900 rounded-2xl p-3 border border-slate-200 dark:border-slate-800/80 hover:border-amber-500/50 transition-all group flex flex-col justify-between shadow-sm dark:shadow-none">
        <a href="{{ route('catalog.show', 'slug-produk') }}" class="relative aspect-square rounded-xl bg-slate-100 dark:bg-slate-950 flex items-center justify-center overflow-hidden mb-3">
          <span class="absolute top-2 left-2 bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-md z-10">BEST SELLER</span>
          <i class="fa-solid fa-wine-bottle text-5xl text-slate-400 dark:text-slate-700 group-hover:scale-110 group-hover:text-amber-500 transition-all duration-300"></i>
        </a>
        <div>
          <div class="flex items-center justify-between">
            <span class="text-[10px] uppercase font-semibold text-amber-600 dark:text-amber-400 tracking-wider">Beer & Cider</span>
            <span class="text-[10px] text-slate-400 font-medium">4.8% ABV</span>
          </div>
          <a href="{{ route('catalog.show', 'slug-produk') }}" class="block">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 mt-0.5 hover:text-amber-500 transition-colors">Craft Lager Premium 500ml</h3>
          </a>
          <div class="flex items-center gap-1 my-1">
            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">4.8</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500">(120)</span>
          </div>
          <div class="flex items-center justify-between mt-3">
            <div>
              <span class="text-xs text-slate-400 dark:text-slate-500 line-through">Rp 75.000</span>
              <p class="text-sm font-extrabold text-amber-600 dark:text-amber-400">Rp 65.000</p>
            </div>
            <button class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-amber-500 dark:hover:bg-amber-400 hover:text-slate-950 text-slate-700 dark:text-slate-200 flex items-center justify-center transition-colors">
              <i class="fa-solid fa-plus text-xs"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Product 2 -->
      <div class="bg-white dark:bg-slate-900 rounded-2xl p-3 border border-slate-200 dark:border-slate-800/80 hover:border-amber-500/50 transition-all group flex flex-col justify-between shadow-sm dark:shadow-none">
        <a href="{{ route('catalog.show', 'slug-produk') }}" class="relative aspect-square rounded-xl bg-slate-100 dark:bg-slate-950 flex items-center justify-center overflow-hidden mb-3">
          <i class="fa-solid fa-glass-water text-5xl text-slate-400 dark:text-slate-700 group-hover:scale-110 group-hover:text-amber-500 transition-all duration-300"></i>
        </a>
        <div>
          <div class="flex items-center justify-between">
            <span class="text-[10px] uppercase font-semibold text-amber-600 dark:text-amber-400 tracking-wider">Fine Wine</span>
            <span class="text-[10px] text-slate-400 font-medium">13.5% ABV</span>
          </div>
          <a href="{{ route('catalog.show', 'slug-produk') }}" class="block">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 mt-0.5 hover:text-amber-500 transition-colors">Cabernet Sauvignon 750ml</h3>
          </a>
          <div class="flex items-center gap-1 my-1">
            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">4.9</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500">(85)</span>
          </div>
          <div class="flex items-center justify-between mt-3">
            <div>
              <p class="text-sm font-extrabold text-amber-600 dark:text-amber-400">Rp 420.000</p>
            </div>
            <button class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-amber-500 dark:hover:bg-amber-400 hover:text-slate-950 text-slate-700 dark:text-slate-200 flex items-center justify-center transition-colors">
              <i class="fa-solid fa-plus text-xs"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Product 3 -->
      <div class="bg-white dark:bg-slate-900 rounded-2xl p-3 border border-slate-200 dark:border-slate-800/80 hover:border-amber-500/50 transition-all group flex flex-col justify-between shadow-sm dark:shadow-none">
        <a href="{{ route('catalog.show', 'slug-produk') }}" class="relative aspect-square rounded-xl bg-slate-100 dark:bg-slate-950 flex items-center justify-center overflow-hidden mb-3">
          <i class="fa-solid fa-bottle-droplet text-5xl text-slate-400 dark:text-slate-700 group-hover:scale-110 group-hover:text-amber-500 transition-all duration-300"></i>
        </a>
        <div>
          <div class="flex items-center justify-between">
            <span class="text-[10px] uppercase font-semibold text-amber-600 dark:text-amber-400 tracking-wider">Soju</span>
            <span class="text-[10px] text-slate-400 font-medium">13.0% ABV</span>
          </div>
          <a href="{{ route('catalog.show', 'slug-produk') }}" class="block">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 mt-0.5 hover:text-amber-500 transition-colors">Green Grape Soju 360ml</h3>
          </a>
          <div class="flex items-center gap-1 my-1">
            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">4.7</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500">(210)</span>
          </div>
          <div class="flex items-center justify-between mt-3">
            <div>
              <p class="text-sm font-extrabold text-amber-600 dark:text-amber-400">Rp 85.000</p>
            </div>
            <button class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-amber-500 dark:hover:bg-amber-400 hover:text-slate-950 text-slate-700 dark:text-slate-200 flex items-center justify-center transition-colors">
              <i class="fa-solid fa-plus text-xs"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Product 4 -->
      <div class="bg-white dark:bg-slate-900 rounded-2xl p-3 border border-slate-200 dark:border-slate-800/80 hover:border-amber-500/50 transition-all group flex flex-col justify-between shadow-sm dark:shadow-none">
        <a href="{{ route('catalog.show', 'slug-produk') }}" class="relative aspect-square rounded-xl bg-slate-100 dark:bg-slate-950 flex items-center justify-center overflow-hidden mb-3">
          <i class="fa-solid fa-whiskey-glass text-5xl text-slate-400 dark:text-slate-700 group-hover:scale-110 group-hover:text-amber-500 transition-all duration-300"></i>
        </a>
        <div>
          <div class="flex items-center justify-between">
            <span class="text-[10px] uppercase font-semibold text-amber-600 dark:text-amber-400 tracking-wider">Spirits</span>
            <span class="text-[10px] text-slate-400 font-medium">40.0% ABV</span>
          </div>
          <a href="{{ route('catalog.show', 'slug-produk') }}" class="block">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 mt-0.5 hover:text-amber-500 transition-colors">Single Malt Whiskey 12Y</h3>
          </a>
          <div class="flex items-center gap-1 my-1">
            <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">5.0</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500">(42)</span>
          </div>
          <div class="flex items-center justify-between mt-3">
            <div>
              <p class="text-sm font-extrabold text-amber-600 dark:text-amber-400">Rp 1.150.000</p>
            </div>
            <button class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-amber-500 dark:hover:bg-amber-400 hover:text-slate-950 text-slate-700 dark:text-slate-200 flex items-center justify-center transition-colors">
              <i class="fa-solid fa-plus text-xs"></i>
            </button>
          </div>
        </div>
      </div>

    </div>

    <!-- PAGINATION -->
    <div class="mt-10 text-center">
      <button class="bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-800 font-bold px-8 py-3 rounded-2xl text-xs transition-colors shadow-sm">
        Muat Lebih Banyak Produk...
      </button>
    </div>
  </main>

  <!-- MOBILE BOTTOM NAVBAR (4 Menu - Katalog Active) -->
  <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 md:hidden px-4 py-2 transition-colors">
    <div class="grid grid-cols-4 gap-1 max-w-md mx-auto text-center">
      <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-amber-500">
        <i class="fa-solid fa-house text-lg"></i>
        <span class="text-[10px] font-medium">Beranda</span>
      </a>
      <a href="{{ route('catalog.index') }}" class="flex flex-col items-center gap-1 text-amber-600 dark:text-amber-400">
        <i class="fa-solid fa-compass text-lg"></i>
        <span class="text-[10px] font-medium">Katalog</span>
      </a>
      <a href="{{ route('orders.index') }}" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-amber-500">
        <i class="fa-solid fa-receipt text-lg"></i>
        <span class="text-[10px] font-medium">Pesanan</span>
      </a>
      <a href="{{ route('profile.index') }}" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-amber-500">
        <i class="fa-regular fa-user text-lg"></i>
        <span class="text-[10px] font-medium">Akun</span>
      </a>
    </div>
  </nav>

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