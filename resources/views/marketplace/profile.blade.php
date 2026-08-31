@extends('welcome')

@section('title', 'Profil Saya & Verifikasi Usia - Tipsy More')

@section('content')
<!-- MAIN CONTAINER -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

  <!-- PROFILE HEADER CARD -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 transition-colors">

    <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
      <!-- Avatar dengan Dukungan Foto Profil Upload -->
      <div class="relative shrink-0">
        <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-amber-500/30 bg-gradient-to-tr from-amber-500 to-amber-300 flex items-center justify-center text-slate-950 font-black text-2xl shadow-lg shadow-amber-500/20">
          @if($user->avatar ?? Auth::user()->avatar ?? false)
          <img src="{{ asset('storage/' . ($user->avatar ?? Auth::user()->avatar)) }}" alt="{{ $user->full_name ?? Auth::user()->full_name }}" class="w-full h-full object-cover">
          @else
          {{ strtoupper(substr($user->full_name ?? Auth::user()->full_name ?? 'KT', 0, 2)) }}
          @endif
        </div>
        <span class="absolute -bottom-1 -right-1 bg-emerald-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs shadow" title="Akun Terverifikasi 21+">
          <i class="fa-solid fa-shield-check"></i>
        </span>
      </div>

      <!-- User Info -->
      <div class="space-y-1">
        <div class="flex items-center gap-2 justify-center sm:justify-start">
          <h1 class="text-xl font-extrabold text-slate-900 dark:text-white">
            {{ $user->full_name ?? Auth::user()->full_name ?? 'Pengguna' }}
          </h1>
          <span class="px-2.5 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 text-[10px] font-bold rounded-full uppercase">
            Gold Member
          </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          {{ $user->email ?? Auth::user()->email ?? '-' }} • {{ $user->phone_number ?? Auth::user()->phone_number ?? '-' }}
        </p>

        <!-- Verification Status Tag -->
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-lg text-xs font-semibold mt-1">
          <i class="fa-solid fa-circle-check"></i> Terverifikasi 21+
        </div>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="flex items-center gap-4 border-t sm:border-t-0 sm:border-l border-slate-200 dark:border-slate-800 pt-4 sm:pt-0 sm:pl-6 w-full sm:w-auto justify-around">
      <div class="text-center">
        <span class="text-lg font-black text-amber-600 dark:text-amber-400 block">12</span>
        <span class="text-[10px] text-slate-400 uppercase font-semibold">Pesanan</span>
      </div>
      <div class="text-center">
        <span class="text-lg font-black text-amber-600 dark:text-amber-400 block">4</span>
        <span class="text-[10px] text-slate-400 uppercase font-semibold">Favorit</span>
      </div>
      <div class="text-center">
        <span class="text-lg font-black text-amber-600 dark:text-amber-400 block">1.250</span>
        <span class="text-[10px] text-slate-400 uppercase font-semibold">Poin Tipsy</span>
      </div>
    </div>

  </div>

  <!-- AGE & ID VERIFICATION BANNER -->
  <!-- ACTIVE IF AVAILABLE TO VERIFICATION WITH ID CARD -->
  <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-500/20 rounded-2xl p-4 flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-lg flex-shrink-0">
          <i class="fa-solid fa-id-card"></i>
        </div>
        <div>
          <h3 class="text-xs font-bold text-slate-900 dark:text-white">Status Identitas Hukum (KTP 21+)</h3>
          <p class="text-[11px] text-slate-500 dark:text-slate-400">
            Terverifikasi. Bebas belanja produk alkohol tanpa hambatan.
          </p>
        </div>
      </div>
      <button onclick="openAgeModal()" class="px-3 py-1.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold border border-slate-200 dark:border-slate-700 hover:border-amber-400 transition-colors flex-shrink-0">
        Cek Status
      </button>
    </div>

  <!-- SESSION ALERTS -->
  @if(session('success'))
  <div class="p-4 mb-4 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 rounded-2xl text-sm font-semibold border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-between">
    <span><i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}</span>
  </div>
  @endif

  <!-- MENU LIST SECTION -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm divide-y divide-slate-100 dark:divide-slate-800 transition-colors overflow-hidden">

    <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
      <div class="flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-user-pen text-base"></i>
        </div>
        <div>
          <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors">Edit Data Profile</h4>
          <p class="text-xs text-slate-500 dark:text-slate-400">Ubah foto profil, nama lengkap, email, dan no HP</p>
        </div>
      </div>
      <i class="fa-solid fa-chevron-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
    </a>

    <a href="{{ route('orders.index') }}" class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
      <div class="flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-receipt text-base"></i>
        </div>
        <div>
          <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors">Riwayat Transaksi & Tracking</h4>
          <p class="text-xs text-slate-500 dark:text-slate-400">Cek status pesanan aktif dan histori pembelian</p>
        </div>
      </div>
      <i class="fa-solid fa-chevron-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
    </a>

    <a href="{{ route('profile.addresses.index') }}" class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
      <div class="flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-location-dot text-base"></i>
        </div>
        <div>
          <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors">Alamat Pengiriman</h4>
          <p class="text-xs text-slate-500 dark:text-slate-400">Kelola daftar lokasi tujuan pengiriman Anda</p>
        </div>
      </div>
      <i class="fa-solid fa-chevron-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
    </a>

    <a href="{{ route('our-store.index') }}" class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
      <div class="flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-store text-base"></i>
        </div>
        <div>
          <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors">Our Store</h4>
          <p class="text-xs text-slate-500 dark:text-slate-400">Lokasi cabang toko resmi KT & opsi Store Pickup</p>
        </div>
      </div>
      <i class="fa-solid fa-chevron-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
    </a>

    <a href="#" class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
      <div class="flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-file-contract text-base"></i>
        </div>
        <div>
          <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors">Syarat & Ketentuan</h4>
          <p class="text-xs text-slate-500 dark:text-slate-400">Aturan ketentuaan usia 21+ dan batasan pembelian</p>
        </div>
      </div>
      <i class="fa-solid fa-chevron-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
    </a>

    <a href="#" class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
      <div class="flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-shield-halved text-base"></i>
        </div>
        <div>
          <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors">Kebijakan Privasi</h4>
          <p class="text-xs text-slate-500 dark:text-slate-400">Perlindungan data pribadi pengguna Tipsy More</p>
        </div>
      </div>
      <i class="fa-solid fa-chevron-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
    </a>

  </div>

  <!-- LOGOUT BUTTON -->
  <form action="{{ route('logout') }}" method="POST" class="w-full">
    @csrf
    <button type="submit" class="w-full bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/20 font-bold py-3.5 rounded-2xl text-xs transition-colors flex items-center justify-center gap-2">
      <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Akun
    </button>
  </form>

</div>
@endsection