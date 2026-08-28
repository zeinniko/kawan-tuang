@extends('welcome')

@section('title', 'Masuk - Tipsy More')

@push('styles')
  <!-- Custom Font Playfair Display -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <style>
    .font-serif-custom {
      font-family: 'Playfair Display', serif;
    }
  </style>
@endpush

@section('content')
  <!-- MAIN CONTAINER -->
  <div class="min-h-[80vh] flex items-center justify-center p-4">
    
    <div class="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-xl relative transition-colors duration-500">
      
      <!-- Back Button -->
      <a href="{{ route('home') }}" class="absolute top-6 left-6 text-slate-400 hover:text-amber-500 transition-colors" title="Kembali ke Beranda">
        <i class="fa-solid fa-arrow-left text-lg"></i>
      </a>

      <!-- Header Card -->
      <div class="text-center mb-8 mt-2">
        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-amber-600 to-amber-300 flex items-center justify-center font-serif-custom font-bold text-slate-950 text-2xl mx-auto mb-3 shadow-lg shadow-amber-500/30">
          TM
        </div>
        <h1 class="text-2xl font-serif-custom font-bold text-slate-900 dark:text-white">Selamat Datang Kembali</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Masuk untuk melanjutkan pesanan Anda di Tipsy More</p>
      </div>

      <!-- Alert Sukses (Jika Ada) -->
      @if (session('status'))
        <div class="mb-4 p-3 rounded-xl text-xs font-semibold text-center border bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
          {{ session('status') }}
        </div>
      @endif

      <!-- Form Login POST -->
      <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
        @csrf
        
        <!-- Email / Phone Input -->
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email atau No. Handphone</label>
          <div class="relative">
            <input type="text" name="login_id" value="{{ old('login_id') }}" placeholder="nama@email.com / 0812xxx" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border {{ $errors->has('login_id') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700/60' }} focus:border-amber-500 transition-all">
            <i class="fa-regular fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm"></i>
          </div>
          @error('login_id')
            <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span>
          @enderror
        </div>

        <!-- Password Input -->
        <div>
          <div class="flex items-center justify-between mb-1">
            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Kata Sandi</label>
            <a href="{{ route('forgot-password') }}" class="text-xs text-amber-600 dark:text-amber-400 hover:underline">Lupa sandi?</a>
          </div>
          <div class="relative">
            <input type="password" id="password-input" name="password" placeholder="••••••••" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-12 outline-none border border-slate-200 dark:border-slate-700/60 focus:border-amber-500 transition-all">
            <i class="fa-solid fa-lock absolute left-4 top-3.5 text-slate-400 text-sm"></i>
            <button type="button" onclick="togglePassword()" class="absolute right-4 top-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
              <i id="eye-icon" class="fa-regular fa-eye text-sm"></i>
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-amber-500/20 mt-2">
          Masuk Sekarang
        </button>
      </form>

      <!-- Divider -->
      <div class="relative my-6 text-center">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200 dark:border-slate-800"></div></div>
        <span class="relative px-4 bg-white dark:bg-slate-900 text-[10px] uppercase font-semibold text-slate-400">
          <!-- Atau masuk dengan -->
        </span>
      </div>

      <!-- Register Link -->
      <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-8">
        Belum punya akun Tipsy More? 
        <a href="{{ route('register') }}" class="text-amber-600 dark:text-amber-400 font-bold hover:underline">Daftar di sini</a>
      </p>

    </div>
  </div>
@endsection

@push('scripts')
  <script>
    function togglePassword() {
      const input = document.getElementById('password-input');
      const icon = document.getElementById('eye-icon');
      if (input && icon) {
        if (input.type === 'password') { 
          input.type = 'text'; 
          icon.classList.replace('fa-eye', 'fa-eye-slash'); 
        } else { 
          input.type = 'password'; 
          icon.classList.replace('fa-eye-slash', 'fa-eye'); 
        }
      }
    }
  </script>
@endpush