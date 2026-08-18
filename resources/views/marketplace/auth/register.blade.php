<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun - Kawan Tuang</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            serif: ['"Playfair Display"', 'serif'],
          }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#F8F9FA] text-slate-800 dark:bg-[#0B0F19] dark:text-slate-200 font-sans min-h-screen flex items-center justify-center p-4 transition-colors duration-500">

  <div class="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-xl relative transition-colors duration-500">
    
    <!-- Tombol Kembali -->
    <a href="{{ route('home') }}" class="absolute top-6 left-6 text-slate-400 hover:text-amber-500 transition-colors" title="Kembali ke Beranda">
      <i class="fa-solid fa-arrow-left text-lg"></i>
    </a>

    <!-- Logo Brand -->
    <div class="text-center mb-6 mt-2">
      <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-amber-600 to-amber-300 flex items-center justify-center font-serif font-bold text-slate-950 text-2xl mx-auto mb-3 shadow-lg shadow-amber-500/30">KT</div>
      <h1 class="text-2xl font-serif font-bold text-slate-900 dark:text-white">Buat Akun Baru</h1>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Nikmati kemudahan pesan minuman bergaransi original 100%</p>
    </div>

    <!-- Form Register -->
    <form action="{{ route('login') }}" method="GET" class="space-y-3.5">
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
        <div class="relative">
          <input type="text" placeholder="Budi Santoso" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border border-slate-200 dark:border-slate-700/60 focus:border-amber-500 transition-all">
          <i class="fa-regular fa-user absolute left-4 top-3.5 text-slate-400 text-sm"></i>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
        <div class="relative">
          <input type="email" placeholder="budi@email.com" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border border-slate-200 dark:border-slate-700/60 focus:border-amber-500 transition-all">
          <i class="fa-regular fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm"></i>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Handphone (WhatsApp)</label>
        <div class="relative">
          <input type="tel" placeholder="08123456789" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border border-slate-200 dark:border-slate-700/60 focus:border-amber-500 transition-all">
          <i class="fa-solid fa-phone absolute left-4 top-3.5 text-slate-400 text-sm"></i>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi</label>
        <div class="relative">
          <input type="password" placeholder="Min. 8 karakter" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border border-slate-200 dark:border-slate-700/60 focus:border-amber-500 transition-all">
          <i class="fa-solid fa-lock absolute left-4 top-3.5 text-slate-400 text-sm"></i>
        </div>
      </div>

      <!-- Verifikasi Umur Checkbox -->
      <div class="flex items-start gap-2 pt-1">
        <input type="checkbox" required class="mt-0.5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
        <span class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight">
          Saya menyatakan bahwa saya telah berusia <strong class="text-slate-700 dark:text-slate-300">21 tahun ke atas</strong> dan menyetujui <a href="#" class="text-amber-500 underline">Syarat & Ketentuan</a> Kawan Tuang.
        </span>
      </div>

      <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-amber-500/20 mt-2">
        Daftar Sekarang
      </button>
    </form>

    <!-- Login Link -->
    <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-6">
      Sudah punya akun Kawan Tuang? 
      <a href="{{ route('login') }}" class="text-amber-600 dark:text-amber-400 font-bold hover:underline">Masuk di sini</a>
    </p>

  </div>

  <script>
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  </script>
</body>
</html>