@extends('welcome')

@section('title', 'Pemulihan Sandi - Kawan Tuang')

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

    <!-- Tombol Kembali ke Login -->
    <a href="{{ route('login') }}" class="absolute top-6 left-6 text-slate-400 hover:text-amber-500 transition-colors" title="Kembali ke Masuk">
      <i class="fa-solid fa-arrow-left text-lg"></i>
    </a>

    <!-- Icon / Header -->
    <div class="text-center mb-6 mt-2">
      <div class="w-12 h-12 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl mx-auto mb-3">
        <i class="fa-solid fa-key"></i>
      </div>
      <h1 class="text-2xl font-serif-custom font-bold text-slate-900 dark:text-white">Lupa Kata Sandi?</h1>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Masukkan email terdaftar Anda dan kami akan mengirimkan tautan instruksi pemulihan sandi.</p>
    </div>

    <!-- Form Forgot Password -->
    <form action="{{ route('forgot-password.send') }}" method="POST" class="space-y-4">
      @csrf

      @if (session('status'))
      <div class="p-3 text-xs text-emerald-700 bg-emerald-100 rounded-xl">
        {{ session('status') }}
      </div>
      @endif

      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Email Terdaftar</label>
        <div class="relative">
          <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-11 pr-4 outline-none border border-slate-200 dark:border-slate-700/60 focus:border-amber-500 transition-all">
          <i class="fa-regular fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm"></i>
        </div>
        @error('email')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
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
</div>

<!-- MODAL TAILWIND CUSTOM -->
<div id="customModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 max-w-sm w-full shadow-2xl text-center transform scale-95 transition-transform duration-300">

    <!-- Icon Sukses -->
    <div class="w-14 h-14 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-2xl mx-auto mb-4">
      <i class="fa-solid fa-paper-plane"></i>
    </div>

    <!-- Judul & Deskripsi -->
    <h3 class="text-xl font-serif-custom font-bold text-slate-900 dark:text-white mb-2">Tautan Terkirim!</h3>
    <p class="text-xs text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
      Kami telah mengirimkan instruksi pemulihan kata sandi ke <span id="displayEmail" class="font-bold text-slate-800 dark:text-slate-200"></span>.
    </p>

    <!-- Action Button -->
    <button onclick="redirectToLogin()" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3 rounded-2xl text-sm transition-all shadow-md shadow-amber-500/20">
      Kembali ke Masuk
    </button>

  </div>
</div>
@endsection

@push('scripts')
<script>
  const modal = document.getElementById('customModal');
  const modalCard = modal.querySelector('div');
  const displayEmail = document.getElementById('displayEmail');
  const emailInput = document.getElementById('emailInput');

  function openModal(event) {
    event.preventDefault();

    // Masukkan teks email ke modal
    displayEmail.textContent = emailInput.value;

    // Tampilkan backdrop
    modal.classList.remove('hidden');
    setTimeout(() => {
      modal.classList.remove('opacity-0');
      modalCard.classList.remove('scale-95');
      modalCard.classList.add('scale-100');
    }, 10);
  }

  function redirectToLogin() {
    // Efek animasi keluar
    modal.classList.add('opacity-0');
    modalCard.classList.remove('scale-100');
    modalCard.classList.add('scale-95');

    setTimeout(() => {
      window.location.href = "{{ route('login') }}";
    }, 300);
  }
</script>
@endpush