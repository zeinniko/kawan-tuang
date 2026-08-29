@extends('welcome')

@section('title', 'Cara Pemesanan - Tipsy More')

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
        <i class="fa-solid fa-bag-shopping text-2xl"></i>
      </div>
      <div>
        <div class="flex items-center gap-2 justify-center sm:justify-start mb-1">
          <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
            Panduan Cara Pemesanan
          </h1>
          <span class="px-2.5 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 text-[10px] font-bold rounded-full uppercase">
            Mudah & Cepat
          </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          Langkah praktis memesan minuman favorit Anda di Tipsy More
        </p>
      </div>
    </div>

    <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl text-xs font-bold border border-emerald-500/20 shrink-0">
      <i class="fa-solid fa-circle-check"></i> Layanan Instan 21+
    </div>
  </div>

  <!-- HIGHLIGHT BANNER -->
  <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-500/20 rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-start gap-3.5">
      <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-lg shrink-0 shadow-lg shadow-amber-500/20">
        <i class="fa-solid fa-lightbulb"></i>
      </div>
      <div class="space-y-0.5">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Tips Pesanan Cepat Sampai</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
          Pastikan Anda mengaktifkan **GPS** dan memilih cabang toko terdekat agar opsi pengiriman instan serta ketersediaan stok produk dingin (*Cold Ready*) tampil secara akurat.
        </p>
      </div>
    </div>
  </div>

  <!-- STEPS CONTAINER -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm divide-y divide-slate-100 dark:divide-slate-800 transition-colors overflow-hidden">

    <!-- Langkah 1 -->
    <div class="p-5 sm:p-6 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          01
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Atur Lokasi Pengiriman & Cabang Toko</h2>
      </div>
      <div class="pl-0 sm:pl-11 space-y-3">
        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
          Buka aplikasi Tipsy More, lalu tentukan titik lokasi pengiriman Anda (*pin-point GPS*) atau pilih outlet cabang toko terdekat yang ingin Anda tuju.
        </p>
        
        <!-- PLACEHOLDER GAMBAR 1 -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/50 p-2">
          <img src="{{ asset('images/guides/step-1-location.png') }}" alt="[GAMBAR 1]: Screenshot Halaman Pemilihan Alamat & Cabang Toko Terdekat dengan Peta Pin-point GPS" class="w-full h-auto rounded-xl object-cover">
        </div>
      </div>
    </div>

    <!-- Langkah 2 -->
    <div class="p-5 sm:p-6 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          02
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Pilih Produk & Opsi Suhu (Cold / Room Temp)</h2>
      </div>
      <div class="pl-0 sm:pl-11 space-y-3">
        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
          Jelajahi katalog produk berdasarkan Kategori, Brand, atau Vibe. Pada halaman produk, Anda bisa memilih preferensi kondisi minuman: **Cold Ready** (Dingin Siap Minum) atau **Room Temp** (Suhu Ruangan).
        </p>

        <!-- PLACEHOLDER GAMBAR 2 -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/50 p-2">
          <img src="{{ asset('images/guides/step-2-product.png') }}" alt="[GAMBAR 2]: Screenshot Halaman Detail Produk (PDP) Menampilkan Opsi Suhu Cold Ready dan Button Tambah ke Keranjang" class="w-full h-auto rounded-xl object-cover">
        </div>
      </div>
    </div>

    <!-- Langkah 3 -->
    <div class="p-5 sm:p-6 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          03
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Cek Keranjang & Gunakan Voucher Diskon</h2>
      </div>
      <div class="pl-0 sm:pl-11 space-y-3">
        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
          Tinjau item yang Anda pilih di menu Keranjang Belanja. Masukkan kode voucher promo aktif untuk mendapatkan potongan harga atau diskon pengiriman.
        </p>

        <!-- PLACEHOLDER GAMBAR 3 -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/50 p-2">
          <img src="{{ asset('images/guides/step-3-cart.png') }}" alt="[GAMBAR 3]: Screenshot Halaman Keranjang Belanja dengan Input Kode Voucher dan Subtotal" class="w-full h-auto rounded-xl object-cover">
        </div>
      </div>
    </div>

    <!-- Langkah 4 -->
    <div class="p-5 sm:p-6 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          04
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Pilih Metode Fulfillment (Delivery / Pick Up)</h2>
      </div>
      <div class="pl-0 sm:pl-11 space-y-3">
        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
          Pilih jenis pemenuhan pesanan:
          <br>• <strong>Instant Delivery:</strong> Pesanan diantar kurir langsung ke lokasi Anda.
          <br>• <strong>Pick Up in Store:</strong> Anda mengambil pesanan sendiri di cabang toko tanpa ongkir.
        </p>

        <!-- PLACEHOLDER GAMBAR 4 -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/50 p-2">
          <img src="{{ asset('images/guides/step-4-fulfillment.png') }}" alt="[GAMBAR 4]: Screenshot Halaman Checkout Opsi Instant Delivery vs Pick Up Store" class="w-full h-auto rounded-xl object-cover">
        </div>
      </div>
    </div>

    <!-- Langkah 5 -->
    <div class="p-5 sm:p-6 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          05
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Lakukan Pembayaran Secara Aman</h2>
      </div>
      <div class="pl-0 sm:pl-11 space-y-3">
        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
          Pilih metode pembayaran (QRIS, Bank Virtual Account, atau E-Wallet). Selesaikan pembayaran sebelum batas waktu countdown habis agar pesanan otomatis diproses.
        </p>

        <!-- PLACEHOLDER GAMBAR 5 -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/50 p-2">
          <img src="{{ asset('images/guides/step-5-payment.png') }}" alt="[GAMBAR 5]: Screenshot Pilihan Metode Pembayaran Payment Gateway (QRIS, VA, E-Wallet) dan Timer Kadaluarsa" class="w-full h-auto rounded-xl object-cover">
        </div>
      </div>
    </div>

    <!-- Langkah 6 -->
    <div class="p-5 sm:p-6 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          06
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Terima Barang & Tunjukkan KTP Usia 21+</h2>
      </div>
      <div class="pl-0 sm:pl-11 space-y-3">
        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
          Saat kurir tiba atau saat mengambil barang di outlet, siapkan dokumen identitas fisik (KTP/SIM/Paspor) asli Anda. Kurir/staf wajib memverifikasi bahwa penerima barang berusia minimal 21 tahun.
        </p>

        <!-- PLACEHOLDER GAMBAR 6 -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/50 p-2">
          <img src="{{ asset('images/guides/step-6-receive.png') }}" alt="[GAMBAR 6]: Grafik Ilustrasi Penyerahan Paket oleh Kurir dengan Pemeriksaan Fisik KTP Pengguna Usia 21+" class="w-full h-auto rounded-xl object-cover">
        </div>
      </div>
    </div>

  </div>

  <!-- HELP FOOTER CARD -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
    <div class="flex items-center gap-3.5 text-center sm:text-left">
      <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
        <i class="fa-solid fa-circle-question text-lg"></i>
      </div>
      <div>
        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Mengalami Kendala Saat Memesan?</h4>
        <p class="text-xs text-slate-500 dark:text-slate-400">Tim kami siap membantu Anda menyelesaikan pesanan</p>
      </div>
    </div>
    <a href="mailto:support@tipsymore.id" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition-colors shrink-0 flex items-center gap-2">
      <i class="fa-solid fa-envelope"></i> Bantuan CS
    </a>
  </div>

</div>
@endsection