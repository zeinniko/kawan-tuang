@extends('welcome')

@inject('storageService', 'App\Services\StorageService')

@section('title', 'Profil Saya & Verifikasi Usia - Tipsy More')

@section('content')
<!-- MAIN CONTAINER -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

  <!-- PROFILE HEADER CARD -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 transition-colors">

    <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
      <!-- Avatar dari S3 Public / Fallback UI Avatars -->
      <div class="relative shrink-0">
        <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-amber-500/30 bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-950 font-black text-2xl shadow-lg shadow-amber-500/20">
          @if($user->avatar)
          <img src="{{ $storageService->getUrl($user->avatar, 'public') }}" alt="{{ $user->full_name }}" class="w-full h-full object-cover">
          @else
          <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=f59e0b&color=fff&bold=true" alt="{{ $user->full_name }}" class="w-full h-full object-cover">
          @endif
        </div>

        @if(($kycData['status'] ?? null) === 'approved')
        <span class="absolute -bottom-1 -right-1 bg-emerald-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs shadow" title="Akun Terverifikasi 21+">
          <i class="fa-solid fa-shield-check"></i>
        </span>
        @endif
      </div>

      <!-- User Info -->
      <div class="space-y-1">
        <div class="flex items-center gap-2 justify-center sm:justify-start">
          <h1 class="text-xl font-extrabold text-slate-900 dark:text-white">
            {{ $user->full_name }}
          </h1>
          <span class="px-2.5 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 text-[10px] font-bold rounded-full uppercase">
            Member
          </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          {{ $user->email }} • {{ $user->phone_number }}
        </p>

        <!-- Dynamic Verification Status Tag -->
        @if(($kycData['status'] ?? null) === 'approved')
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-lg text-xs font-semibold mt-1">
          <i class="fa-solid fa-circle-check"></i> Terverifikasi 21+
        </div>
        @elseif(($kycData['status'] ?? null) === 'pending')
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-lg text-xs font-semibold mt-1">
          <i class="fa-solid fa-clock"></i> Verifikasi Diproses
        </div>
        @elseif(($kycData['status'] ?? null) === 'rejected')
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-lg text-xs font-semibold mt-1">
          <i class="fa-solid fa-circle-xmark"></i> Verifikasi Ditolak
        </div>
        @else
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-lg text-xs font-semibold mt-1">
          <i class="fa-solid fa-shield-slash"></i> Belum Upload KTP (Opsional)
        </div>
        @endif
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="flex items-center gap-4 sm:gap-6 border-t sm:border-t-0 sm:border-l border-slate-200 dark:border-slate-800 pt-4 sm:pt-0 sm:pl-6 w-full sm:w-auto justify-around sm:justify-start">

      <!-- Total Pesanan -->
      <div class="text-center">
        <span class="text-lg font-black text-amber-600 dark:text-amber-400 block">{{ $user->orders()->count() }}</span>
        <span class="text-[10px] text-slate-400 uppercase font-semibold">Pesanan</span>
      </div>

      <!-- Total Alamat -->
      <div class="text-center">
        <span class="text-lg font-black text-amber-600 dark:text-amber-400 block">{{ $user->addresses()->count() }}</span>
        <span class="text-[10px] text-slate-400 uppercase font-semibold">Alamat</span>
      </div>

      <!-- Poin Tipsy (Hanya Aktif jika KYC Verified) -->
      <div class="text-center">
        @if(($kycData['status'] ?? null) === 'approved')
        <span class="text-lg font-black text-amber-600 dark:text-amber-400 block">
          {{ number_format($user->points ?? 0, 0, ',', '.') }}
        </span>
        @else
        <!-- CUSTOM TOOLTIP CONTAINER -->
        <div class="relative group inline-block cursor-pointer">
          <span class="text-lg font-black text-slate-400 dark:text-slate-500 inline-flex items-center gap-1">
            <i class="fa-solid fa-lock text-xs text-amber-500"></i> 0
          </span>

          <!-- BALON TOOLTIP (MUNCUL SAAT HOVER / DI-TAP) -->
          <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 hidden group-hover:flex flex-col items-center z-30 w-48 pointer-events-none transition-all duration-200">
            <div class="bg-slate-900 text-white dark:bg-white dark:text-slate-900 text-[10px] font-bold py-1.5 px-3 rounded-xl shadow-xl text-center leading-snug">
              Verifikasi KTP 21+ untuk mulai mengumpulkan Poin Tipsy
            </div>
            <!-- Segitiga Panah Tooltip -->
            <div class="w-2 h-2 -mt-1 rotate-45 bg-slate-900 dark:bg-white"></div>
          </div>
        </div>
        @endif
        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Poin Tipsy</span>
      </div>

    </div>

  </div>

  <!-- DYNAMIC AGE & ID VERIFICATION BANNER (HYBRID / SOFT-WARNING) -->
  @php $kycStatus = $kycData['status'] ?? 'none'; @endphp

  @if($kycStatus === 'approved')
  <!-- STATUS 1: VERIFIED (GREEN) -->
  <div class="bg-gradient-to-r from-emerald-500/10 via-emerald-500/5 to-transparent border border-emerald-500/20 rounded-2xl p-4 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-lg shrink-0">
        <i class="fa-solid fa-id-card"></i>
      </div>
      <div>
        <h3 class="text-xs font-bold text-slate-900 dark:text-white">Status Identitas Hukum (KTP 21+)</h3>
        <p class="text-[11px] text-slate-500 dark:text-slate-400">
          Terverifikasi. Bebas belanja produk alkohol tanpa hambatan.
        </p>
      </div>
    </div>
    <span class="px-3 py-1.5 bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-xs font-bold border border-emerald-500/30 shrink-0">
      Aktif 21+
    </span>
  </div>

  @elseif($kycStatus === 'pending')
  <!-- STATUS 2: PENDING (AMBER) -->
  <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-500/20 rounded-2xl p-4 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-lg shrink-0">
        <i class="fa-solid fa-hourglass-half animate-spin"></i>
      </div>
      <div>
        <h3 class="text-xs font-bold text-slate-900 dark:text-white">Verifikasi KTP Sedang Ditinjau</h3>
        <p class="text-[11px] text-slate-500 dark:text-slate-400">
          Dokumen Anda sedang diperiksa. Anda tetap bisa bertransaksi seperti biasa.
        </p>
      </div>
    </div>
  </div>

  @elseif($kycStatus === 'rejected')
  <!-- STATUS 3: REJECTED (RED) -->
  <div class="bg-gradient-to-r from-rose-500/10 via-rose-500/5 to-transparent border border-rose-500/20 rounded-2xl p-4 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold text-lg shrink-0">
        <i class="fa-solid fa-circle-exclamation"></i>
      </div>
      <div>
        <h3 class="text-xs font-bold text-slate-900 dark:text-white">Verifikasi KTP Ditolak</h3>
        <p class="text-[11px] text-rose-500 dark:text-rose-400 font-medium">
          Alasan: {{ $kycData['rejection_reason'] ?? 'Dokumen KTP tidak terbaca jelas.' }}
        </p>
      </div>
    </div>
    <button onclick="openKycModal()" type="button" class="px-3.5 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-bold transition-colors shrink-0 cursor-pointer shadow-sm">
      Upload Ulang
    </button>
  </div>

  @else
  <!-- STATUS 4: PROMPT VERIFICATION (OPSIONAL / NON-BLOCKING) -->
  <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-500/20 rounded-2xl p-4 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-lg shrink-0">
        <i class="fa-solid fa-id-card"></i>
      </div>
      <div>
        <h3 class="text-xs font-bold text-slate-900 dark:text-white">Verifikasi Usia Legal</h3>
        <p class="text-[11px] text-slate-500 dark:text-slate-400">
          Upload KTP kapan saja untuk melengkapi profil dan mendapatkan badge terverifikasi.
        </p>
      </div>
    </div>
    <button onclick="openKycModal()" type="button" class="px-3.5 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 rounded-xl text-xs font-bold transition-colors shrink-0 shadow-sm cursor-pointer">
      Verifikasi KTP
    </button>
  </div>
  @endif

  <!-- SESSION ALERTS -->
  @if(session('success'))
  <div class="p-4 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 rounded-2xl text-xs font-semibold border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-between">
    <span><i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}</span>
  </div>
  @endif

  @if($errors->any())
  <div class="p-4 bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 rounded-2xl text-xs font-semibold border border-rose-200 dark:border-rose-500/20 space-y-1">
    @foreach($errors->all() as $error)
    <p><i class="fa-solid fa-circle-xmark me-1"></i> {{ $error }}</p>
    @endforeach
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
          <p class="text-xs text-slate-500 dark:text-slate-400">Aturan ketentuan usia 21+ dan batasan pembelian</p>
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
    <button type="submit" class="w-full bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/20 font-bold py-3.5 rounded-2xl text-xs transition-colors flex items-center justify-center gap-2 cursor-pointer">
      <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Akun
    </button>
  </form>

  <!-- MODAL POPUP FORM UPLOAD KTP (PERSUASIF & EKSKLUSIF) -->
  <div id="kycModal" onclick="closeKycModal()" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">

    <!-- Modal Card (Max height scrollable untuk layar HP) -->
    <div onclick="event.stopPropagation()" class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto border border-slate-200 dark:border-slate-800 shadow-2xl space-y-5">

      <!-- Header Modal -->
      <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-id-card text-lg"></i>
          </div>
          <div>
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white leading-tight">Verifikasi Usia Legal (21+)</h3>
            <p class="text-[11px] text-slate-500 dark:text-slate-400">Lengkapi data untuk membuka semua fitur</p>
          </div>
        </div>
        <button type="button" onclick="closeKycModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-white flex items-center justify-center transition-colors cursor-pointer">
          <i class="fa-solid fa-xmark text-sm"></i>
        </button>
      </div>

      <!-- BANNER PERSUASIF: KEUNTUNGAN VERIFIKASI -->
      <div class="bg-gradient-to-br from-amber-500/15 via-amber-500/5 to-transparent border border-amber-500/30 rounded-2xl p-4 space-y-2.5">
        <div class="flex items-center gap-2">
          <span class="text-xs font-black uppercase tracking-wider text-amber-600 dark:text-amber-400">
            ✨ Keuntungan Khusus Member Verifikasi:
          </span>
        </div>

        <ul class="space-y-2 text-xs text-slate-700 dark:text-slate-300">
          <li class="flex items-start gap-2.5">
            <div class="w-5 h-5 rounded-full bg-amber-500/20 text-amber-500 flex items-center justify-center shrink-0 mt-0.5">
              <i class="fa-solid fa-coins text-[10px]"></i>
            </div>
            <span class="leading-tight"><strong>Buka Fitur Poin Tipsy:</strong> Kumpulkan poin dari setiap transaksi & tukar diskon belanja.</span>
          </li>
          <li class="flex items-start gap-2.5">
            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center shrink-0 mt-0.5">
              <i class="fa-solid fa-shield-check text-[10px]"></i>
            </div>
            <span class="leading-tight"><strong>Badge Verified Member 21+:</strong> Akun sah secara legalitas & prioritas pengiriman.</span>
          </li>
          <li class="flex items-start gap-2.5">
            <div class="w-5 h-5 rounded-full bg-amber-500/20 text-amber-500 flex items-center justify-center shrink-0 mt-0.5">
              <i class="fa-solid fa-ticket-simple text-[10px]"></i>
            </div>
            <span class="leading-tight"><strong>Voucher & Promo Eksklusif:</strong> Akses penawaran terbatas khusus member terverifikasi.</span>
          </li>
        </ul>
      </div>

      <!-- FORM UPLOAD KYC -->
      <form action="{{ route('profile.kyc.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- NIK -->
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
            NIK KTP <span class="text-slate-400 font-normal">(16 Digit)</span>
          </label>
          <input type="text" name="nik" maxlength="16" placeholder="Masukkan 16 digit NIK..." required class="w-full bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white rounded-xl py-3 px-4 border border-slate-200 dark:border-slate-700 outline-none focus:border-amber-500 transition-all">
        </div>

        <!-- FOTO KTP -->
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Foto KTP Asli</label>
          <input type="file" name="ktp_image" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-3 file:py-2 px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-slate-950 hover:file:bg-amber-400 cursor-pointer">
          <p class="text-[10px] text-slate-400 mt-1">Pastikan tulisan & foto pada KTP terbaca jelas</p>
        </div>

        <!-- FOTO SELFIE KTP -->
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Foto Selfie Memegang KTP</label>
          <input type="file" name="selfie_image" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-3 file:py-2 px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-slate-950 hover:file:bg-amber-400 cursor-pointer">
          <p class="text-[10px] text-slate-400 mt-1">Posisikan KTP di samping wajah dengan pencahayaan cukup</p>
        </div>

        <!-- JAMINAN KEAMANAN DATA -->
        <div class="flex items-start gap-2.5 p-3 bg-slate-100 dark:bg-slate-800/60 rounded-xl border border-slate-200/60 dark:border-slate-700/50">
          <i class="fa-solid fa-lock text-amber-500 text-xs mt-0.5 shrink-0"></i>
          <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-relaxed">
            <strong>Privasi Terjamin Aman:</strong> Data Anda dienkripsi secara aman di <em>Cloud Encrypted Private Storage</em> dan hanya digunakan untuk verifikasi kelayakan usia.
          </p>
        </div>

        <!-- TOMBOL AKSI -->
        <div class="flex gap-2.5 pt-1">
          <button type="button" onclick="closeKycModal()" class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold transition-colors cursor-pointer">
            Nanti Saja
          </button>
          <button type="submit" class="flex-1 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 rounded-xl text-xs font-extrabold shadow-lg shadow-amber-500/20 transition-all cursor-pointer">
            Verifikasi Sekarang
          </button>
        </div>
      </form>

    </div>
  </div>

</div>

@push('scripts')
<script>
  function openKycModal() {
    const modal = document.getElementById('kycModal');
    if (modal) {
      modal.classList.remove('hidden');
    }
  }

  function closeKycModal() {
    const modal = document.getElementById('kycModal');
    if (modal) {
      modal.classList.add('hidden');
    }
  }

  // Tutup modal dengan tombol ESC
  window.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      closeKycModal();
    }
  });
</script>
@endpush
@endsection