<!DOCTYPE html>
<html lang="id" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang & Checkout - Kawan Tuang</title>
  
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
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans pb-28 lg:pb-12 transition-colors duration-300">

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
          <span class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">Keranjang & <span class="text-amber-500 dark:text-amber-400">Checkout</span></span>
        </a>
      </div>

      <!-- Actions -->
      <div class="flex items-center gap-3">
        <!-- DARK / LIGHT MODE TOGGLE -->
        <button id="theme-toggle" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Ubah Mode">
          <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-amber-400 text-lg hidden"></i>
          <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-slate-600 text-lg"></i>
        </button>

        <span class="text-xs text-slate-500 dark:text-slate-400 hidden sm:inline"><i class="fa-solid fa-lock text-emerald-500 me-1"></i> Transaksi Aman</span>
      </div>

    </div>
  </header>

  <!-- CART & CHECKOUT CONTAINER -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- LEFT COLUMN: CART ITEMS, SHIPPING, PAYMENT (8 Cols) -->
      <div class="lg:col-span-8 space-y-6">
        
        <!-- SECTION 1: DAFTAR PESANAN -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
          <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
              <i class="fa-solid fa-bag-shopping text-amber-500"></i> Item Pesanan (2 Varian)
            </h2>
            <button class="text-xs text-rose-500 hover:underline font-medium">Hapus Semua</button>
          </div>

          <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
            
            <!-- Item 1 -->
            <div class="py-4 flex gap-3 sm:gap-4 items-center">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-wine-bottle text-2xl sm:text-3xl text-amber-500"></i>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">Craft Lager Premium 500ml</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">4.8% ABV • Can 500ml</p>
                  </div>
                  <button class="text-slate-400 hover:text-rose-500 text-xs p-1"><i class="fa-solid fa-trash-can"></i></button>
                </div>
                <div class="flex items-center justify-between mt-3">
                  <span class="text-sm font-extrabold text-amber-600 dark:text-amber-400">Rp 130.000 <span class="text-[10px] text-slate-400 font-normal">(2x @65k)</span></span>
                  <div class="flex items-center bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                    <button class="w-7 h-7 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-l-lg">-</button>
                    <span class="w-8 text-center text-xs font-bold text-slate-900 dark:text-white">2</span>
                    <button class="w-7 h-7 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-r-lg">+</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Item 2 -->
            <div class="py-4 flex gap-3 sm:gap-4 items-center">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-glass-water text-2xl sm:text-3xl text-amber-500"></i>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">Cabernet Sauvignon 750ml</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">13.5% ABV • Bottle 750ml</p>
                  </div>
                  <button class="text-slate-400 hover:text-rose-500 text-xs p-1"><i class="fa-solid fa-trash-can"></i></button>
                </div>
                <div class="flex items-center justify-between mt-3">
                  <span class="text-sm font-extrabold text-amber-600 dark:text-amber-400">Rp 420.000</span>
                  <div class="flex items-center bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                    <button class="w-7 h-7 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-l-lg">-</button>
                    <span class="w-8 text-center text-xs font-bold text-slate-900 dark:text-white">1</span>
                    <button class="w-7 h-7 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-r-lg">+</button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- SECTION 2: ALAMAT PENGIRIMAN -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
              <i class="fa-solid fa-location-dot text-amber-500"></i> Alamat Pengiriman
            </h2>
            <button class="text-xs text-amber-600 dark:text-amber-400 font-bold hover:underline">Ubah Alamat</button>
          </div>
          <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-1">
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold text-slate-900 dark:text-white">Alex Wijaya</span>
              <span class="px-2 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 font-semibold text-[10px] rounded">Utama</span>
              <span class="text-xs text-slate-500 dark:text-slate-400">(+62 812-3456-7890)</span>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
              Jl. Senopati No. 45, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12190
            </p>
            <input type="text" placeholder="Catatan lokasi/driver (cth: Titip di Satpam)" class="w-full mt-2 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-3 py-2 text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
          </div>
        </div>

        <!-- SECTION 3: PILIHAN KURIR / EKSPEDISI -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
          <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-3">
            <i class="fa-solid fa-truck-fast text-amber-500"></i> Opsi Pengiriman (Suhu Terjaga)
          </h2>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            
            <!-- Option 1: Gojek Instant -->
            <label class="relative flex flex-col justify-between p-3 rounded-xl border-2 border-amber-500 bg-amber-500/5 cursor-pointer">
              <input type="radio" name="shipping" checked class="hidden">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-900 dark:text-white">Gojek Instant</span>
                <i class="fa-solid fa-circle-check text-amber-500 text-sm"></i>
              </div>
              <div class="mt-2">
                <span class="text-xs font-extrabold text-amber-600 dark:text-amber-400 block">Rp 25.000</span>
                <span class="text-[10px] text-slate-500 dark:text-slate-400">Estimasi 1 Jam Sampai</span>
              </div>
            </label>

            <!-- Option 2: GrabExpress -->
            <label class="relative flex flex-col justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer">
              <input type="radio" name="shipping" class="hidden">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-900 dark:text-white">GrabExpress</span>
              </div>
              <div class="mt-2">
                <span class="text-xs font-extrabold text-slate-900 dark:text-white block">Rp 27.000</span>
                <span class="text-[10px] text-slate-500 dark:text-slate-400">Estimasi 1-2 Jam Sampai</span>
              </div>
            </label>

            <!-- Option 3: Paxel Same Day -->
            <label class="relative flex flex-col justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer">
              <input type="radio" name="shipping" class="hidden">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-900 dark:text-white">Paxel Cold Chain</span>
              </div>
              <div class="mt-2">
                <span class="text-xs font-extrabold text-slate-900 dark:text-white block">Rp 35.000</span>
                <span class="text-[10px] text-slate-500 dark:text-slate-400">Same Day Cooling Box</span>
              </div>
            </label>

          </div>
        </div>

        <!-- SECTION 4: METODE PEMBAYARAN -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
          <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-3">
            <i class="fa-solid fa-wallet text-amber-500"></i> Payment Gateway
          </h2>
          <div class="space-y-2">
            
            <!-- QRIS / Instant E-Wallet -->
            <label class="flex items-center justify-between p-3 rounded-xl border-2 border-amber-500 bg-amber-500/5 cursor-pointer">
              <div class="flex items-center gap-3">
                <input type="radio" name="payment" checked class="text-amber-500 focus:ring-amber-500">
                <div>
                  <span class="text-xs font-bold text-slate-900 dark:text-white block">QRIS (GoPay, OVO, ShopeePay, DANA)</span>
                  <span class="text-[10px] text-slate-500 dark:text-slate-400">Scan otomatis via aplikasi e-wallet / mobile banking</span>
                </div>
              </div>
              <span class="px-2 py-1 bg-amber-500/20 text-amber-600 dark:text-amber-400 font-bold text-[10px] rounded">Instan</span>
            </label>

            <!-- Virtual Account Bank -->
            <label class="flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer">
              <div class="flex items-center gap-3">
                <input type="radio" name="payment" class="text-amber-500 focus:ring-amber-500">
                <div>
                  <span class="text-xs font-bold text-slate-900 dark:text-white block">Virtual Account (BCA, Mandiri, BRI, BNI)</span>
                  <span class="text-[10px] text-slate-500 dark:text-slate-400">Verifikasi otomatis 24/7</span>
                </div>
              </div>
            </label>

            <!-- Kartu Kredit / Debit -->
            <label class="flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer">
              <div class="flex items-center gap-3">
                <input type="radio" name="payment" class="text-amber-500 focus:ring-amber-500">
                <div>
                  <span class="text-xs font-bold text-slate-900 dark:text-white block">Kartu Kredit / Debit Online</span>
                  <span class="text-[10px] text-slate-500 dark:text-slate-400">Visa / Mastercard Secured 3D</span>
                </div>
              </div>
            </label>

          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: ORDER SUMMARY (4 Cols) -->
      <div class="lg:col-span-4">
        <div class="sticky top-20 bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 transition-colors">
          
          <h2 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-3">
            Ringkasan Pembayaran
          </h2>

          <!-- VOUCHER INPUT -->
          <div>
            <label class="text-xs text-slate-500 dark:text-slate-400 block mb-1">Mempunyai Kode Promo?</label>
            <div class="flex gap-2">
              <input type="text" placeholder="Cth: TT21PLUS" class="flex-1 bg-slate-100 dark:bg-slate-800 text-xs text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 uppercase font-mono focus:outline-none focus:ring-1 focus:ring-amber-500">
              <button class="bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-amber-400 font-bold px-3 py-2 rounded-xl text-xs transition-colors">Pakai</button>
            </div>
          </div>

          <!-- COST BREAKDOWN -->
          <div class="space-y-2 text-xs text-slate-600 dark:text-slate-400 border-t border-b border-slate-200 dark:border-slate-800 py-3">
            <div class="flex justify-between">
              <span>Subtotal Produk (3 item)</span>
              <span class="font-medium text-slate-900 dark:text-white">Rp 550.000</span>
            </div>
            <div class="flex justify-between">
              <span>Ongkos Kirim (Gojek Instant)</span>
              <span class="font-medium text-slate-900 dark:text-white">Rp 25.000</span>
            </div>
            <div class="flex justify-between">
              <span>Pengemasan Extra (Bubble Wrap + Kayu)</span>
              <span class="font-semibold text-emerald-600 dark:text-emerald-400">GRATIS</span>
            </div>
            <div class="flex justify-between text-rose-500">
              <span>Diskon Promo</span>
              <span class="font-medium">-Rp 20.000</span>
            </div>
          </div>

          <!-- TOTAL -->
          <div class="flex items-center justify-between">
            <div>
              <span class="text-xs text-slate-400 block">Total Pembayaran</span>
              <span class="text-xl font-black text-amber-600 dark:text-amber-400">Rp 555.000</span>
            </div>
          </div>

          <!-- PACKAGING GUARANTEE NOTE -->
          <div class="p-3 bg-amber-500/10 rounded-xl border border-amber-500/20 text-[11px] text-amber-700 dark:text-amber-300 flex items-center gap-2">
            <i class="fa-solid fa-box-archive text-sm flex-shrink-0"></i>
            <span>Kemasan terjamin aman, tertutup rapi, dan menjaga kerahasiaan isi produk.</span>
          </div>

          <!-- CHECKOUT BUTTON (DESKTOP) -->
          <a href="{{ route('orders.index') }}" class="hidden lg:block w-full bg-amber-500 hover:bg-amber-600 dark:bg-amber-400 dark:hover:bg-amber-500 text-slate-950 font-extrabold py-3.5 rounded-xl text-center text-sm transition-all shadow-lg shadow-amber-500/20">
            Bayar Sekarang <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>

        </div>
      </div>

    </div>
  </main>

  <!-- MOBILE STICKY CHECKOUT BAR -->
  <div class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 lg:hidden p-4">
    <div class="flex items-center justify-between gap-3 max-w-md mx-auto">
      <div>
        <span class="text-[10px] text-slate-400 block">Total (3 Item)</span>
        <span class="text-lg font-black text-amber-600 dark:text-amber-400">Rp 555.000</span>
      </div>
      <a href="{{ route('orders.index') }}" class="bg-amber-500 dark:bg-amber-400 text-slate-950 font-extrabold px-6 py-3 rounded-xl text-xs shadow-md">
        Bayar Sekarang <i class="fa-solid fa-arrow-right me-1"></i>
      </a>
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