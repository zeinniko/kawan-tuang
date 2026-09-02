@extends('welcome')

@section('title', 'Daftar Akun - Tipsy More')

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
    <div class="text-center mb-6 mt-2">
      <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-amber-600 to-amber-300 flex items-center justify-center font-serif-custom font-bold text-slate-950 text-2xl mx-auto mb-3 shadow-lg shadow-amber-500/30">
        TM
      </div>
      <h1 class="text-2xl font-serif-custom font-bold text-slate-900 dark:text-white">Buat Akun Baru</h1>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Nikmati kemudahan pesan minuman bergaransi original 100%</p>
    </div>

    <!-- Form Register POST -->
    <form action="{{ route('register.post') }}" method="POST" class="space-y-3.5">
      @csrf

      <!-- Nama Lengkap -->
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
        <div class="relative">
          <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Nama Lengkap" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border {{ $errors->has('full_name') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700/60' }} focus:border-amber-500 transition-all">
          <i class="fa-regular fa-user absolute left-4 top-3.5 text-slate-400 text-sm pointer-events-none"></i>
        </div>
        @error('full_name')<span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span>@enderror
      </div>

      <!-- Alamat Email -->
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
        <div class="relative">
          <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@domain.com" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border {{ $errors->has('email') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700/60' }} focus:border-amber-500 transition-all">
          <i class="fa-regular fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm pointer-events-none"></i>
        </div>
        @error('email')<span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span>@enderror
      </div>

      <!-- Nomor Handphone -->
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Handphone (WhatsApp)</label>
        <div class="relative">
          <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="08123xxxx" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border {{ $errors->has('phone_number') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700/60' }} focus:border-amber-500 transition-all">
          <i class="fa-solid fa-phone absolute left-4 top-3.5 text-slate-400 text-sm pointer-events-none"></i>
        </div>
        @error('phone_number')<span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span>@enderror
      </div>

      <!-- Tanggal Lahir (Kalender Native - Di-lock Usia < 21) -->
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Lahir (Khusus 21+)</label>
        <div class="relative">
          <input
            type="date"
            id="birth_date"
            name="birth_date"
            value="{{ old('birth_date') }}"
            max="{{ \Carbon\Carbon::now()->subYears(21)->format('Y-m-d') }}"
            required
            class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border {{ $errors->has('birth_date') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700/60' }} focus:border-amber-500 transition-all cursor-pointer">
          <i class="fa-regular fa-calendar absolute left-4 top-3.5 text-slate-400 text-sm pointer-events-none"></i>
        </div>
        @error('birth_date')<span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span>@enderror
      </div>

      <!-- Kata Sandi -->
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi</label>
        <div class="relative">
          <input type="password" id="password" name="password" placeholder="Min. 8 karakter" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-11 outline-none border {{ $errors->has('password') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700/60' }} focus:border-amber-500 transition-all">
          <i class="fa-solid fa-lock absolute left-4 top-3.5 text-slate-400 text-sm pointer-events-none"></i>

          <!-- Tombol Ikon Mata -->
          <button type="button" onclick="togglePasswordVisibility('password', 'toggle-password-icon')" class="absolute right-4 top-3.5 text-slate-400 hover:text-amber-500 focus:outline-none transition-colors cursor-pointer" title="Tampilkan/Sembunyikan Kata Sandi">
            <i id="toggle-password-icon" class="fa-regular fa-eye text-sm"></i>
          </button>
        </div>
        @error('password')<span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span>@enderror
      </div>
      <div class="flex items-start gap-2 pt-1">
        <input type="checkbox" name="is_age_verified" value="1" {{ old('is_age_verified') ? 'checked' : '' }} required class="mt-0.5 rounded border-slate-300 text-amber-500 focus:ring-amber-500 cursor-pointer">
        <span class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight">
          Saya menyatakan bahwa saya telah berusia <strong class="text-slate-700 dark:text-slate-300">21 tahun ke atas</strong> dan menyetujui
          <a href="{{ route('terms') }}" target="_blank" class="text-amber-600 dark:text-amber-400 font-semibold hover:underline">Syarat & Ketentuan</a>.
        </span>
      </div>
      @error('is_age_verified')<span class="text-[11px] text-rose-500 block">{{ $message }}</span>@enderror

      <!-- Submit Button -->
      <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-amber-500/20 mt-2">
        Daftar Sekarang
      </button>
    </form>

    <!-- Login Link -->
    <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-6">
      Sudah punya akun Tipsy More?
      <a href="{{ route('login') }}" class="text-amber-600 dark:text-amber-400 font-bold hover:underline">Masuk di sini</a>
    </p>

  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const birthInput = document.getElementById('birth_date');

    if (birthInput) {
      // Buka kalender saat klik di mana saja dalam kotak input
      birthInput.addEventListener('click', function() {
        if (typeof this.showPicker === 'function') {
          this.showPicker();
        }
      });
    }
  });

  function togglePasswordVisibility(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon = document.getElementById(iconId);

  if (input && icon) {
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
}
</script>
@endpush