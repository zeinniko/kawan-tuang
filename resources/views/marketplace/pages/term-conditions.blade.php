@extends('welcome')

@section('title', 'Syarat & Ketentuan - Tipsy More')

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
        <i class="fa-solid fa-file-contract text-2xl"></i>
      </div>
      <div>
        <div class="flex items-center gap-2 justify-center sm:justify-start mb-1">
          <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
            Syarat & Ketentuan
          </h1>
          <span class="px-2.5 py-0.5 bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 text-[10px] font-bold rounded-full uppercase">
            Wajib 21+
          </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          Terakhir diperbarui: {{ $lastUpdated ?? date('d F Y') }} • Tipsy More Indonesia
        </p>
      </div>
    </div>

    <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl text-xs font-bold border border-emerald-500/20 shrink-0">
      <i class="fa-solid fa-shield-check"></i> Kebijakan Legal Resmi
    </div>
  </div>

  <!-- AGE POLICY HIGHLIGHT BANNER -->
  <div class="bg-gradient-to-r from-rose-500/10 via-amber-500/5 to-transparent border border-rose-500/20 rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-start gap-3.5">
      <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-lg shadow-rose-500/20">
        <i class="fa-solid fa-triangle-exclamation"></i>
      </div>
      <div class="space-y-0.5">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pemberitahuan Batas Usia Legal (21+)</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
          Tipsy More hanya melayani konsumen berusia <strong>21 tahun ke atas</strong>. Validasi usia dilakukan otomatis saat registrasi akun dan <strong>pemeriksaan fisik KTP/Identitas wajib dilakukan oleh Kurir / Staf Outlet</strong> saat penyerahan pesanan.
        </p>
      </div>
    </div>
  </div>

  <!-- TERMS CONTENT SECTION -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm divide-y divide-slate-100 dark:divide-slate-800 transition-colors overflow-hidden">

    <!-- 1. Validasi Usia & Pendaftaran -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          01
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Ketentuan Umum & Validasi Registrasi (21+)</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>1.1. Dengan membuat akun di Tipsy More, Anda menyatakan bahwa Anda telah berusia minimal <strong>21 (dua puluh satu) tahun</strong> serta memiliki legalitas hukum untuk melakukan pembelian minuman beralkohol.</p>
        <p>1.2. <strong>Validasi Pendaftaran:</strong> Sistem kami secara otomatis menolak pendaftaran akun apabila tanggal lahir yang dimasukkan mengindikasikan usia kurang dari 21 tahun.</p>
        <p>1.3. <strong>Pernyataan Palsu:</strong> Memalsukan tanggal lahir saat registrasi merupakan bentuk pelanggaran ketentuan layanan dan dapat mengakibatkan pemblokiran akun secara permanen.</p>
      </div>
    </div>

    <!-- 2. Pemeriksaan Usia Fisik Saat Serah Terima -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          02
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Pemeriksaan Identitas Fisik (Serah Terima Pesanan)</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>2.1. Kami tidak meminta unggah foto KTP di aplikasi. Sebagai gantinya, <strong>pemeriksaan identitas fisik (KTP / SIM / Paspor) wajib dilakukan saat penyerahan produk</strong>.</p>
        <p>2.2. <strong>Pengiriman Instan (Delivery):</strong> Kurir pengantar berhak meminta penerima menunjukkan KTP asli untuk memastikan penerima barang berusia &ge; 21 tahun.</p>
        <p>2.3. <strong>Pengambilan di Toko (Pick Up):</strong> Staf toko Tipsy More wajib memeriksa identitas fisik pengambil barang sebelum menyerahkan pesanan.</p>
        <p>2.4. <strong>Gagal Verifikasi Fisik:</strong> Jika penerima tidak dapat menunjukkan KTP asli atau terbukti belum 21 tahun, <strong>penyerahan barang wajib dibatalkan</strong>. Dana pengiriman tidak dapat dikembalikan.</p>
      </div>
    </div>

    <!-- 3. Akun & Batasan Sistem -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          03
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Keamanan Akun & Batasan Sistem (Rate Limiting)</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>3.1. Pengguna bertanggung jawab menjaga kerahasiaan kata sandi dan seluruh transaksi yang terjadi di bawah akun pribadi.</p>
        <p>3.2. <strong>Pengamanan API & Infrastruktur:</strong> Pengguna dilarang melakukan aksi otomatisasi (<em>botting</em>, <em>scraping</em>, atau penembakan API berlebihan). Sistem kami menerapkan pembatasan <em>rate-limit</em> untuk menjaga kestabilan server.</p>
        <p>3.3. Tipsy More berhak memblokir akun yang terdeteksi melakukan aktivitas penyerangan API atau manipulasi lalu lintas data secara anomali.</p>
      </div>
    </div>

    <!-- 4. Multi-Store Inventory & Suhu -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          04
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Stok Multi-Cabang & Opsi Suhu Produk</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>4.1. Ketersediaan barang dan opsi suhu (<em>Cold Ready</em> vs Suhu Ruangan) terikat langsung pada masing-masing cabang toko (<em>Store Branch</em>).</p>
        <p>4.2. Jika persediaan produk di suatu toko habis, pengguna dapat memilih cabang toko terdekat lainnya melalui fitur pengubah outlet.</p>
        <p>4.3. Apabila terjadi kesalahan teknis penayangan harga atau stok fisik kosong secara bersamaan, Tipsy More berhak membatalkan pesanan dan mengembalikan dana penuh.</p>
      </div>
    </div>

    <!-- 5. Pembayaran & Fulfillment -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          05
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Transaksi, Pembayaran & Metode Fulfillment</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>5.1. Pembayaran dilakukan via <em>Payment Gateway</em> resmi (QRIS, Virtual Account, E-Wallet). Transaksi dianggap sah setelah terkonfirmasi <em>Paid</em> oleh sistem.</p>
        <p>5.2. <strong>Pengiriman Instan:</strong> Pengguna wajib memastikan titik GPS dan nomor kontak akurat. Kegagalan kurir akibat kesalahan titik peta oleh pengguna berada di luar tanggung jawab platform.</p>
        <p>5.3. <strong>Pick Up Store:</strong> Pesanan wajib diambil maksimal 1 &times; 24 jam setelah status berubah menjadi <em>Ready for Pickup</em>.</p>
      </div>
    </div>

    <!-- 6. Return & Refund Policy -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          06
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Pengembalian Barang & Dana (Refund)</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>6.1. Klaim pesanan rusak/pecah wajib dilaporkan dalam kurun waktu <strong>48 jam</strong> setelah pesanan diterima.</p>
        <p>6.2. Pelaporan klaim <strong>wajib menyertakan video unboxing utuh tanpa edit/jeda</strong> beserta foto label pengiriman.</p>
        <p>6.3. Tipsy More tidak menerima refund atau pengembalian barang karena pembatalan sepihak / perubahan keputusan pembeli (<em>Change of Mind</em>).</p>
      </div>
    </div>

    <!-- 7. Responsible Drinking -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          07
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Konsumsi Bijak (Drink Responsibly) & Hukum</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>7.1. Tipsy More mendukung penuh konsumsi alkohol secara bertanggung jawab. <strong>Dilarang berkendara di bawah pengaruh alkohol</strong>.</p>
        <p>7.2. Segala sengketa hukum diatur berdasarkan hukum Republik Indonesia dan diselesaikan via Pengadilan Negeri.</p>
      </div>
    </div>

  </div>

  <!-- HELP & CONTACT CARD -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
    <div class="flex items-center gap-3.5 text-center sm:text-left">
      <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
        <i class="fa-solid fa-headset text-lg"></i>
      </div>
      <div>
        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Ada Pertanyaan Mengenai Ketentuan Layanan?</h4>
        <p class="text-xs text-slate-500 dark:text-slate-400">Tim Customer Support kami siap membantu Anda 24/7</p>
      </div>
    </div>
    <a href="mailto:support@tipsymore.id" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition-colors shrink-0 flex items-center gap-2">
      <i class="fa-solid fa-envelope"></i> Hubungi CS
    </a>
  </div>

</div>
@endsection