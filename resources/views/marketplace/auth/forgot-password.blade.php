<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pemulihan Sandi - Kawan Tuang</title>
  
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
    
    <!-- Tombol Kembali ke Login -->
    <a href="{{ route('login') }}" class="absolute top-6 left-6 text-slate-400 hover:text-amber-500 transition-colors" title="Kembali ke Masuk">
      <i class="fa-solid fa-arrow-left text-lg"></i>
    </a>

    <!-- Icon / Logo -->
    <div class="text-center mb-6 mt-2">
      <div class="w-12 h-12 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl mx-auto mb-3">
        <i class="fa-solid fa-key"></i>
      </div>
      <h1 class="text-2xl font-serif font-bold text-slate-900 dark:text-white">Lupa Kata Sandi?</h1>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Masukkan email terdaftar Anda dan kami akan mengirimkan tautan instruksi pemulihan sandi.</p>
    </div>

    <!-- Form Forgot Password -->
    <form onsubmit="event.preventDefault(); alert('Tautan pemulihan sandi telah dikirim ke email Anda!'); window.location.href=`{{ route('login') }}`;" class="space-y-4">
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Email Terdaftar</label>
        <div class="relative">
          <input type="email" placeholder="nama@email.com" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border border-slate-200 dark:border-slate-700/60 focus:border-amber-500 transition-all">
          <i class="fa-regular fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm"></i>
        </div>
      </div>

      <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-amber-500/20 mt-2">
        Kirim Tautan Pemulihan
      </button>
    </form>

    <!-- Back to Login Link -->
    <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-8">
      Sudah ingat kata sandi Anda? 
      <a href="{{ route('login') }}" class="text-amber-600 dark:text-amber-400 font-bold hover:underline">Masuk kembali</a>
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