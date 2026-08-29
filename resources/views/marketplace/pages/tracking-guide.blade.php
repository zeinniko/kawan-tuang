@extends('welcome')

@section('title', 'Pantau Pengiriman - Tipsy More')

@section('content')
<!-- MAIN CONTAINER -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

  <!-- BREADCRUMB / BACK LINK -->
  <div>
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-amber-500 dark:hover:text-amber-400 transition-colors">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- PAGE HEADER CARD -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 transition-colors">
    <div class="flex items-center gap-4 text-center sm:text-left">
      <div class="w-16 h-16 rounded-2xl bg-amber-500/10 text-amber-500 border border-amber-500/20 flex items-center justify-center shrink-0">
        <i class="fa-solid fa-truck-fast text-2xl"></i>
      </div>
      <div>
        <div class="flex items-center gap-2 justify-center sm:justify-start mb-1">
          <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
            Pantau Pengiriman & Tracking
          </h1>
          <span class="px-2.5 py-0.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-[10px] font-bold rounded-full uppercase">
            Real-Time
          </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          Cara memantau status pesanan instan & penukaran kode pickup di outlet Tipsy More
        </p>
      </div>
    </div>

    <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-2xl text-xs font-bold border border-amber-500/20 shrink-0">
      <i class="fa-solid fa-location-crosshairs"></i> Pelacakan Live Kurir
    </div>
  </div>

  <!-- FEATURE BANNER -->
  <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-500/20 rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-start gap-3.5">
      <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-lg shrink-0 shadow-lg shadow-amber-500/20">
        <i class="fa-solid fa-map-location-dot"></i>
      </div>
      <div class="space-y-0.5">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Fitur Tracking Instan Biteship</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
          Pesanan instan Anda terintegrasi langsung dengan armada kurir. Anda dapat melihat posisi driver di peta interaktif secara *real-time* hingga paket tiba di depan pintu.
        </p>
      </div>
    </div>
  </div>

  <!-- GUIDE SECTIONS CONTAINER -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm divide-y divide-slate-100 dark:divide-slate-800 transition-colors overflow-hidden">

    <!-- Section 1: Cara Membuka Halaman Tracking -->
    <div class="p-5 sm:p-6 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          01
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Buka Menu Riwayat Transaksi</h2>
      </div>
      <div class="pl-0 sm:pl-11 space-y-3">
        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
          Masuk ke menu profil Anda, lalu klik <strong>Riwayat Transaksi & Tracking</strong>. Cari pesanan aktif yang sedang berlangsung dan tekan tombol <strong>"Detail / Lacak Pesanan"</strong>.
        </p>

        <!-- PLACEHOLDER GAMBAR 1 -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/50 p-2">
          <img src="{{ asset('images/guides/track-step-1-history.png') }}" alt="[GAMBAR 1]: Screenshot Daftar Riwayat Transaksi Mengeset Filter Status Pesanan Aktif" class="w-full h-auto rounded-xl object-cover">
        </div>
      </div>
    </div>

    <!-- Section 2: Pelacakan Kurir Instant Delivery -->
    <div class="p-5 sm:p-6 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          02
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Lacak Kurir Instant Delivery (Live Tracking)</h2>
      </div>
      <div class="pl-0 sm:pl-11 space-y-3">
        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
          Pada halaman Detail Pesanan pengiriman instan, Anda dapat melihat informasi nama driver, nomor telepon kurir, serta tombol <strong>"Live Tracking Map"</strong> untuk membuka lokasi GPS driver secara langsung.
        </p>

        <!-- PLACEHOLDER GAMBAR 2 -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/50 p-2">
          <img src="{{ asset('images/guides/track-step-2-live-map.png') }}" alt="[GAMBAR 2]: Screenshot Halaman Detail Pesanan Menampilkan Peta Live Tracking Kurir Biteship dan Info Nama Driver" class="w-full h-auto rounded-xl object-cover">
        </div>
      </div>
    </div>

    <!-- Section 3: Pengambilan Pick Up Store -->
    <div class="p-5 sm:p-6 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          03
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Pengambilan di Outlet (QR Code & PIN Pickup)</h2>
      </div>
      <div class="pl-0 sm:pl-11 space-y-3">
        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
          Jika Anda memilih metode <strong>Pick Up in Store</strong>, halaman detail pesanan akan menampilkan <strong>QR Code Unik</strong> dan <strong>PIN 6 Digit</strong> setelah pembayaran lunas. Tunjukkan QR Code/PIN tersebut kepada staf outlet saat mengambil barang.
        </p>

        <!-- PLACEHOLDER GAMBAR 3 -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/50 p-2">
          <img src="{{ asset('images/guides/track-step-3-pickup-qr.png') }}" alt="[GAMBAR 3]: Screenshot Tampilan QR Code Pengambilan Toko dan Kode PIN Pickup pada Halaman Detail Pesanan" class="w-full h-auto rounded-xl object-cover">
        </div>
      </div>
    </div>

    <!-- Section 4: Arti Status Pesanan -->
    <div class="p-5 sm:p-6 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          04
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Penjelasan Status Pesanan</h2>
      </div>
      <div class="pl-0 sm:pl-11 space-y-3">
        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
          Berikut adalah tahapan indikator status pesanan Anda di Tipsy More:
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="p-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 space-y-1">
            <span class="px-2 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-bold rounded-md uppercase">Pending Payment</span>
            <h4 class="text-xs font-bold text-slate-900 dark:text-white">Menunggu Pembayaran</h4>
            <p class="text-[11px] text-slate-500 dark:text-slate-400">Pesanan dibuat, menunggu konfirmasi pembayaran sebelum batas waktu habis.</p>
          </div>

          <div class="p-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 space-y-1">
            <span class="px-2 py-0.5 bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-bold rounded-md uppercase">Processing</span>
            <h4 class="text-xs font-bold text-slate-900 dark:text-white">Pesanan Diproses</h4>
            <p class="text-[11px] text-slate-500 dark:text-slate-400">Staf toko menyiapkan produk dan kondisi suhu minuman pesanan Anda.</p>
          </div>

          <div class="p-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 space-y-1">
            <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold rounded-md uppercase">Delivering</span>
            <h4 class="text-xs font-bold text-slate-900 dark:text-white">Dalam Pengiriman</h4>
            <p class="text-[11px] text-slate-500 dark:text-slate-400">Kurir instan telah mengambil pesanan di outlet dan menuju alamat Anda.</p>
          </div>

          <div class="p-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 space-y-1">
            <span class="px-2 py-0.5 bg-purple-500/10 text-purple-600 dark:text-purple-400 text-[10px] font-bold rounded-md uppercase">Ready for Pickup</span>
            <h4 class="text-xs font-bold text-slate-900 dark:text-white">Siap Diambil</h4>
            <p class="text-[11px] text-slate-500 dark:text-slate-400">Pesanan Pick Up sudah dikemas dan siap Anda ambil di cabang toko pilihan.</p>
          </div>
        </div>

      </div>
    </div>

  </div>

  <!-- HELP FOOTER CARD -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
    <div class="flex items-center gap-3.5 text-center sm:text-left">
      <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
        <i class="fa-solid fa-headset text-lg"></i>
      </div>
      <div>
        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Kurir Terlambat atau Pesanan Tertahan?</h4>
        <p class="text-xs text-slate-500 dark:text-slate-400">Tim operasional toko kami siap membantu memantau pengiriman</p>
      </div>
    </div>
    <a href="mailto:support@tipsymore.id" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition-colors shrink-0 flex items-center gap-2">
      <i class="fa-solid fa-envelope"></i> Hubungi CS
    </a>
  </div>

</div>
@endsection