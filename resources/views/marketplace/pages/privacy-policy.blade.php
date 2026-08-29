@extends('welcome')

@section('title', 'Kebijakan Privasi - Tipsy More')

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
        <i class="fa-solid fa-shield-halved text-2xl"></i>
      </div>
      <div>
        <div class="flex items-center gap-2 justify-center sm:justify-start mb-1">
          <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
            Kebijakan Privasi
          </h1>
          <span class="px-2.5 py-0.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-[10px] font-bold rounded-full uppercase">
            Perlindungan Data
          </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          Terakhir diperbarui: {{ $lastUpdated ?? date('d F Y') }} • Tipsy More Indonesia
        </p>
      </div>
    </div>

    <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-2xl text-xs font-bold border border-amber-500/20 shrink-0">
      <i class="fa-solid fa-lock"></i> Deklarasi Keamanan
    </div>
  </div>

  <!-- PRIVACY POLICY HIGHLIGHT BANNER -->
  <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-500/20 rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-start gap-3.5">
      <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-lg shrink-0 shadow-lg shadow-amber-500/20">
        <i class="fa-solid fa-user-shield"></i>
      </div>
      <div class="space-y-0.5">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Komitmen Privasi Pengguna Tipsy More</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
          Kami menghargai privasi Anda. Data pribadi Anda diolah secara aman dan bertanggung jawab semata-mata untuk memproses transaksi, verifikasi kelayakan usia (21+), serta pengalaman pengiriman instan yang akurat.
        </p>
      </div>
    </div>
  </div>

  <!-- PRIVACY CONTENT SECTION -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm divide-y divide-slate-100 dark:divide-slate-800 transition-colors overflow-hidden">

    <!-- 1. Data yang Kami Kumpulkan -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          01
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Informasi yang Kami Kumpulkan</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>1.1. <strong>Data Akun Pendaftaran:</strong> Saat Anda mendaftar akun di Tipsy More, kami mengumpulkan data seperti Nama Lengkap, Alamat Email, Nomor Telepon/WhatsApp, dan Tanggal Lahir (digunakan untuk validasi batasan usia 21+).</p>
        <p>1.2. <strong>Data Pengiriman & Lokasi:</strong> Kami mencatat detail Alamat Tujuan, Catatan Penerima, dan koordinat GPS (Latitude & Longitude) yang Anda tetapkan untuk mengalkulasi jarak pengiriman instan serta pencarian toko terdekat.</p>
        <p>1.3. <strong>Tanpa Upload Dokumen Identitas:</strong> Aplikasi Tipsy More tidak menyimpan atau meminta dokumen verifikasi KTP/SIM di dalam database aplikasi (pemeriksaan usia dilakukan secara fisik oleh kurir/staf saat penyerahan produk).</p>
      </div>
    </div>

    <!-- 2. Penggunaan Data Lokasi GPS & Maps -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          02
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Penggunaan Data Lokasi GPS & Layanan Peta</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>2.1. <strong>Penentuan Cabang Toko Terdekat:</strong> Data koordinat GPS Anda digunakan secara otomatis untuk mengunci stok cabang toko terdekat agar pengiriman cepat saji dapat diproses.</p>
        <p>2.2. <strong>Integrasi Layanan Kurir Instan:</strong> Koordinat titik pengiriman dibagikan kepada mitra API penyedia logistik (seperti Biteship, GoSend, GrabExpress) semata-mata untuk pencarian kurir dan estimasi tarif ongkir secara presisi.</p>
        <p>2.3. <strong>Efisiensi Pemanggilan API (Caching Session):</strong> Data koordinat dan pencarian lokasi akan disimpan dalam sesi pengguna (*session storage / cache*) untuk menghindari permintaan data berulang (*re-fetching*) demi menjaga efisiensi dan kestabilan sistem.</p>
      </div>
    </div>

    <!-- 3. Pemrosesan Transaksi & Pembayaran -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          03
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Keamanan & Pemrosesan Pembayaran</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>3.1. Seluruh proses pembayaran (QRIS, Virtual Account, E-Wallet) ditangani langsung secara aman oleh mitra penyedia <em>Payment Gateway</em> terenkripsi yang tersertifikasi.</p>
        <p>3.2. <strong>Tipsy More tidak pernah menyimpan</strong> rahasia nomor kartu kredit, PIN bank, atau kata sandi dompet digital Anda di server internal kami.</p>
        <p>3.3. Kami hanya menyimpan status konfirmasi transaksi (*Pending*, *Paid*, atau *Expired*) dan id referensi transaksi dari gateway pembayaran.</p>
      </div>
    </div>

    <!-- 4. Pembagian Data ke Pihak Ketiga -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          04
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Pembagian Informasi ke Pihak Ketiga</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>4.1. Kami **tidak akan menjual, menyewakan, atau memperjualbelikan** data pribadi Anda kepada pihak mana pun untuk keperluan pemasaran tanpa izin Anda.</p>
        <p>4.2. Informasi nama, alamat pengiriman, dan nomor kontak penerima hanya dibagikan kepada mitra pengiriman resmi (kurir) terbatas pada tujuan pelaksanaan pemenuhan pesanan (*order fulfillment*).</p>
        <p>4.3. Informasi dapat diungkapkan apabila diwajibkan oleh ketentuan perundang-undangan atau perintah resmi dari lembaga penegak hukum yang berwenang di Republik Indonesia.</p>
      </div>
    </div>

    <!-- 5. Keamanan Sistem & Penyimpanan Data -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          05
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Penyimpanan Data & Keamanan Infrastruktur</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>5.1. Kami menerapkan protokol keamanan teknis dan enkripsi standar industri untuk melindungi data Anda dari akses tanpa izin, kehilangan, atau peretasan.</p>
        <p>5.2. Akses API dilindungi oleh mekanisme otentikasi ketat (Sanctum Tokens) serta pembatasan pemanggilan (*rate limiting*) untuk mencegah serangan penyerangan lalu lintas data otomatis (scrapers/bots).</p>
      </div>
    </div>

    <!-- 6. Penggunaan Cookie & Tracking -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          06
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Penggunaan Cookie & Teknologi Sesi</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>6.1. Platform kami menggunakan *cookie* dan teknologi penyimpanan lokal (*session storage*) untuk mengingat status login, menyimpan daftar item keranjang belanja, serta preferensi cabang toko Anda.</p>
        <p>6.2. Anda dapat mengatur atau menonaktifkan penggunaan *cookie* melalui pengaturan peramban (browser) Anda, namun hal tersebut mungkin dapat mempengaruhi beberapa fungsi utama aplikasi.</p>
      </div>
    </div>

    <!-- 7. Hak Pengguna & Penghapusan Akun -->
    <div class="p-5 sm:p-6 space-y-3">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold text-xs shrink-0">
          07
        </div>
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Hak Pengguna & Penghapusan Data</h2>
      </div>
      <div class="pl-11 space-y-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        <p>7.1. Anda berhak memperbarui, mengubah, atau memperbaiki data profil Anda sewaktu-waktu melalui menu **Edit Data Profile** di dalam aplikasi.</p>
        <p>7.2. Anda berhak mengajukan permintaan penutupan akun dan penghapusan data pribadi Anda dari server kami dengan menghubungi Layanan Pelanggan Tipsy More.</p>
      </div>
    </div>

  </div>

  <!-- HELP & CONTACT CARD -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
    <div class="flex items-center gap-3.5 text-center sm:text-left">
      <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
        <i class="fa-solid fa-user-lock text-lg"></i>
      </div>
      <div>
        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Ada Pertanyaan Mengenai Kerahasiaan Data Anda?</h4>
        <p class="text-xs text-slate-500 dark:text-slate-400">Tim Data Protection Officer kami siap membantu Anda</p>
      </div>
    </div>
    <a href="mailto:privacy@tipsymore.id" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition-colors shrink-0 flex items-center gap-2">
      <i class="fa-solid fa-envelope"></i> Hubungi Tim Privasi
    </a>
  </div>

</div>
@endsection