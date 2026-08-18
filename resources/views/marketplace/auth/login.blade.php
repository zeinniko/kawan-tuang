<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - Kawan Tuang</title>
  
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
    
    <!-- Tombol Kembali ke Beranda -->
    <a href="{{ route('home') }}" class="absolute top-6 left-6 text-slate-400 hover:text-amber-500 transition-colors" title="Kembali ke Beranda">
      <i class="fa-solid fa-arrow-left text-lg"></i>
    </a>

    <!-- Logo Brand -->
    <div class="text-center mb-8 mt-2">
      <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-amber-600 to-amber-300 flex items-center justify-center font-serif font-bold text-slate-950 text-2xl mx-auto mb-3 shadow-lg shadow-amber-500/30">KT</div>
      <h1 class="text-2xl font-serif font-bold text-slate-900 dark:text-white">Selamat Datang Kembali</h1>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Masuk untuk melanjutkan pesanan Anda di Kawan Tuang</p>
    </div>

    <!-- Form Login -->
    <form action="{{ route('home') }}" method="GET" class="space-y-4">
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email atau No. Handphone</label>
        <div class="relative">
          <input type="text" placeholder="nama@email.com / 0812xxx" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border border-slate-200 dark:border-slate-700/60 focus:border-amber-500 transition-all">
          <i class="fa-regular fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm"></i>
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between mb-1">
          <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Kata Sandi</label>
          <a href="{{ route('forgot-password') }}" class="text-xs text-amber-600 dark:text-amber-400 hover:underline">Lupa sandi?</a>
        </div>
        <div class="relative">
          <input type="password" id="password-input" placeholder="••••••••" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-12 outline-none border border-slate-200 dark:border-slate-700/60 focus:border-amber-500 transition-all">
          <i class="fa-solid fa-lock absolute left-4 top-3.5 text-slate-400 text-sm"></i>
          <button type="button" onclick="togglePassword()" class="absolute right-4 top-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <i id="eye-icon" class="fa-regular fa-eye text-sm"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-amber-500/20 mt-2">
        Masuk Sekarang
      </button>
    </form>

    <!-- Divider -->
    <div class="relative my-6 text-center">
      <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200 dark:border-slate-800"></div></div>
      <span class="relative px-4 bg-white dark:bg-slate-900 text-[10px] uppercase font-semibold text-slate-400">Atau masuk dengan</span>
    </div>

    <!-- Social Logins -->
    <div class="grid grid-cols-2 gap-3">
      <button onclick="alert('Simulasi Google Login')" class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-2xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 text-xs font-semibold transition-colors">
        <i class="fa-brands fa-google text-rose-500"></i> Google
      </button>
      <button onclick="alert('Simulasi Apple Login')" class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-2xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 text-xs font-semibold transition-colors">
        <i class="fa-brands fa-apple text-slate-900 dark:text-white"></i> Apple
      </button>
    </div>

    <!-- Register Link -->
    <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-8">
      Belum punya akun Kawan Tuang? 
      <a href="{{ route('register') }}" class="text-amber-600 dark:text-amber-400 font-bold hover:underline">Daftar di sini</a>
    </p>

  </div>

  <script>
    // Sinkronisasi Theme dari LocalStorage
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }

    function togglePassword() {
      const input = document.getElementById('password-input');
      const icon = document.getElementById('eye-icon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>