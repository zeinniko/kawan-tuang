@extends('welcome')

@section('title', 'Keranjang & Checkout - Kawan Tuang')

@push('styles')
<script
  src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
  data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
@endpush

@section('content')
<!-- MAIN CONTAINER -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-28 lg:pb-12">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- LEFT COLUMN -->
    <div class="lg:col-span-8 space-y-6">

      <!-- SECTION 1: METODE FULFILLMENT -->
      <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-4">
          <i class="fa-solid fa-hand-holding-box text-amber-500"></i> Pilih Cara Pengambilan
        </h2>

        <div class="grid grid-cols-2 gap-4">
          <!-- Option Delivery -->
          <label id="label-fulfillment-delivery" class="relative flex items-center justify-between p-4 rounded-xl border-2 border-amber-500 bg-amber-500/5 cursor-pointer transition-all">
            <input type="radio" name="fulfillment_type" value="delivery" checked class="hidden" onchange="switchFulfillment('delivery')">
            <div class="flex items-center gap-3">
              <i id="icon-fulfillment-delivery" class="fa-solid fa-truck-ramp-box text-amber-500 text-xl transition-colors"></i>
              <div>
                <span id="title-fulfillment-delivery" class="text-sm font-bold block text-amber-600 dark:text-amber-400 transition-colors">Delivery</span>
                <span class="text-[11px] text-slate-500">Dikirim ke Alamat Anda</span>
              </div>
            </div>
            <i class="fa-solid fa-circle-check text-amber-500 check-icon"></i>
          </label>

          <!-- Option Pick Up -->
          <label id="label-fulfillment-pickup" class="relative flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer transition-all">
            <input type="radio" name="fulfillment_type" value="pickup" class="hidden" onchange="switchFulfillment('pickup')">
            <div class="flex items-center gap-3">
              <i id="icon-fulfillment-pickup" class="fa-solid fa-store text-slate-400 text-xl transition-colors"></i>
              <div>
                <span id="title-fulfillment-pickup" class="text-sm font-bold block text-slate-900 dark:text-white transition-colors">Pick Up</span>
                <span class="text-[11px] text-slate-500">Ambil Sendiri di Toko</span>
              </div>
            </div>
            <i class="fa-solid fa-circle-check text-amber-500 check-icon hidden"></i>
          </label>
        </div>
      </div>

      <!-- SECTION 2: DAFTAR ITEM PESANAN -->
      <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
          <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-bag-shopping text-amber-500"></i> Item Pesanan
          </h2>

          <!-- Tombol Trigger Modal Hapus Semua -->
          <button type="button" onclick="openClearCartModal()" class="text-xs text-rose-500 hover:text-rose-600 hover:underline font-bold transition-colors">
            Hapus Semua
          </button>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-800/60" id="cart-items-container">
          @php
          $items = data_get($cart, 'items', data_get($cart, 'data.items', []));
          @endphp

          @forelse($items as $item)
          @php
          $itemId = data_get($item, 'id');
          $prodName = data_get($item, 'product.name', data_get($item, 'product.data.name', 'Produk'));
          $prodImg = data_get($item, 'product.thumbnail_url',
          data_get($item, 'product.data.thumbnail_url',
          data_get($item, 'product.primary_image.image_url',
          'https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80')));
          $unitPrice = (float) data_get($item, 'unit_price',
          data_get($item, 'product.price',
          data_get($item, 'product.data.price', 0)));
          $qty = (int) data_get($item, 'quantity', 1);
          $subtotal = (float) data_get($item, 'subtotal', $unitPrice * $qty);
          @endphp

          <div class="py-4 flex gap-3 sm:gap-4 items-start sm:items-center cart-item-row" id="cart-item-{{ $itemId }}">

            <!-- Gambar Produk -->
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center flex-shrink-0 overflow-hidden">
              <img src="{{ $prodImg }}" alt="{{ $prodName }}" class="w-full h-full object-cover">
            </div>

            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0 flex-1 pr-2">
                  <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white leading-snug break-words line-clamp-2">
                    {{ $prodName }}
                  </h3>
                  <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                    Rp <span class="unit-price">{{ number_format($unitPrice, 0, ',', '.') }}</span> / botol
                  </p>
                </div>

                <!-- Tombol Hapus Item -->
                <button type="button" onclick="removeItem('{{ $itemId }}')" class="text-slate-400 hover:text-rose-500 text-xs p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors shrink-0" title="Hapus Item">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </div>

              <div class="flex items-center justify-between mt-3 pt-1">
                <span class="text-xs sm:text-sm font-extrabold text-amber-600 dark:text-amber-400">
                  Rp <span class="item-subtotal" id="subtotal-{{ $itemId }}" data-raw="{{ $subtotal }}">{{ number_format($subtotal, 0, ',', '.') }}</span>
                </span>

                <!-- Increment / Decrement Realtime -->
                <div class="flex items-center bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shrink-0">
                  <button type="button" onclick="updateQty('{{ $itemId }}', -1)" class="w-7 h-7 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-l-lg transition-colors">-</button>
                  <span class="w-8 text-center text-xs font-bold text-slate-900 dark:text-white" id="qty-{{ $itemId }}" data-unit-price="{{ $unitPrice }}">{{ $qty }}</span>
                  <button type="button" onclick="updateQty('{{ $itemId }}', 1)" class="w-7 h-7 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-r-lg transition-colors">+</button>
                </div>
              </div>
            </div>
          </div>
          @empty
          <div class="py-12 text-center text-slate-400 text-xs" id="empty-cart-msg">
            Keranjang Anda masih kosong. <a href="{{ route('catalog.index') }}" class="text-amber-500 underline font-bold">Mulai belanja</a>
          </div>
          @endforelse
        </div>
      </div>

      <!-- SECTION 3: ALAMAT PENGIRIMAN (DELIVERY MODE) -->
      <div id="section-address" class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm transition-all">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-amber-500"></i> Alamat Pengiriman
          </h2>
          <button type="button" onclick="openAddressModal()" class="text-xs text-amber-600 dark:text-amber-400 font-bold hover:underline">Ubah Alamat</button>
        </div>
        <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-1">
          <div class="flex items-center gap-2 flex-wrap">
            <span id="display-address-name" class="text-xs font-bold text-slate-900 dark:text-white">Alex Wijaya</span>
            <span class="px-2 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 font-semibold text-[10px] rounded">Utama</span>
            <span id="display-address-phone" class="text-xs text-slate-500 dark:text-slate-400">(+62 812-3456-7890)</span>
          </div>
          <p id="display-address-detail" class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed pt-0.5">
            Jl. Senopati No. 45, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12190
          </p>
          <input type="text" id="delivery-note" placeholder="Catatan lokasi/driver (cth: Titip di Satpam)" class="w-full mt-2.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-3 py-2 text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
        </div>
      </div>

      <!-- SECTION 4: PILIHAN CABANG (PICKUP MODE) -->
      <div id="section-store-pickup" class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm hidden transition-all">
        <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-3">
          <i class="fa-solid fa-store text-amber-500"></i> Pilih Outlet Pengambilan
        </h2>
        <div class="space-y-3">
          <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 border-amber-500 bg-amber-500/5 cursor-pointer">
            <input type="radio" name="pickup_store_id" value="store-senopati" checked class="mt-1 text-amber-500 focus:ring-amber-500">
            <div>
              <span class="text-xs font-bold text-slate-900 dark:text-white block">Kawan Tuang - Senopati (Pusat)</span>
              <span class="text-[11px] text-slate-500 dark:text-slate-400 block">Jl. Senopati No.45, Kebayoran Baru, Jakarta Selatan</span>
              <span class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold mt-1 block"><i class="fa-regular fa-clock me-1"></i> Buka Hari Ini: 10:00 - 24:00</span>
            </div>
          </label>
          <label class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer">
            <input type="radio" name="pickup_store_id" value="store-pik" class="mt-1 text-amber-500 focus:ring-amber-500">
            <div>
              <span class="text-xs font-bold text-slate-900 dark:text-white block">Kawan Tuang - PIK</span>
              <span class="text-[11px] text-slate-500 dark:text-slate-400 block">Ruko Golf Island Blok A No.12, PIK, Jakarta Utara</span>
              <span class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold mt-1 block"><i class="fa-regular fa-clock me-1"></i> Buka Hari Ini: 12:00 - 02:00</span>
            </div>
          </label>
        </div>
      </div>

      <!-- SECTION 5: OPSI EKSPEDISI (DELIVERY MODE) -->
      <div id="section-courier" class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm transition-all">
        <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-3">
          <i class="fa-solid fa-truck-fast text-amber-500"></i> Opsi Pengiriman
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

          <label class="relative flex flex-col justify-between p-3.5 rounded-xl border-2 border-amber-500 bg-amber-500/5 cursor-pointer courier-option transition-all" onclick="selectCourier(25000, 'Gojek Instant', event)">
            <input type="radio" name="shipping" value="25000" checked class="hidden">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-slate-900 dark:text-white">Gojek Instant</span>
              <i class="fa-solid fa-circle-check text-amber-500 text-sm check-icon"></i>
            </div>
            <div class="mt-2">
              <span class="text-xs font-extrabold text-amber-600 dark:text-amber-400 block courier-price">Rp 25.000</span>
              <span class="text-[10px] text-slate-500">Estimasi 1 Jam Sampai</span>
            </div>
          </label>

          <label class="relative flex flex-col justify-between p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer courier-option transition-all" onclick="selectCourier(27000, 'GrabExpress', event)">
            <input type="radio" name="shipping" value="27000" class="hidden">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-slate-900 dark:text-white">GrabExpress</span>
              <i class="fa-solid fa-circle-check text-amber-500 text-sm check-icon hidden"></i>
            </div>
            <div class="mt-2">
              <span class="text-xs font-extrabold text-slate-900 dark:text-white block courier-price">Rp 27.000</span>
              <span class="text-[10px] text-slate-500">Estimasi 1-2 Jam Sampai</span>
            </div>
          </label>

          <label class="relative flex flex-col justify-between p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer courier-option transition-all" onclick="selectCourier(35000, 'Paxel Cold Chain', event)">
            <input type="radio" name="shipping" value="35000" class="hidden">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-slate-900 dark:text-white">Paxel Cold</span>
              <i class="fa-solid fa-circle-check text-amber-500 text-sm check-icon hidden"></i>
            </div>
            <div class="mt-2">
              <span class="text-xs font-extrabold text-slate-900 dark:text-white block courier-price">Rp 35.000</span>
              <span class="text-[10px] text-slate-500">Same Day Cooling Box</span>
            </div>
          </label>

        </div>
      </div>

    </div>

    <!-- RIGHT COLUMN: RINGKASAN & PAYMENT -->
    <div class="lg:col-span-4">
      <div class="sticky top-24 bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">

        <h2 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-3">
          Ringkasan Pembayaran
        </h2>

        <!-- VOUCHER PROMO -->
        <div>
          <label class="text-xs text-slate-500 dark:text-slate-400 block mb-1">Kode Promo / Voucher</label>
          <div class="flex gap-2">
            <input type="text" id="voucher-code" placeholder="Cth: KT21PLUS" class="flex-1 bg-slate-100 dark:bg-slate-800 text-xs text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 uppercase font-mono focus:outline-none focus:ring-1 focus:ring-amber-500">
            <button type="button" id="btn-apply-voucher" onclick="applyVoucher()" class="bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 text-amber-400 font-bold px-3 py-2 rounded-xl text-xs transition-colors shrink-0">Pakai</button>
            <button type="button" id="btn-remove-voucher" onclick="removeVoucher()" class="hidden bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 font-bold px-3 py-2 rounded-xl text-xs transition-colors shrink-0">Hapus</button>
          </div>
          <!-- Feedback Message -->
          <p id="voucher-message" class="text-[11px] mt-1.5 hidden font-medium"></p>
        </div>

        <!-- COST BREAKDOWN -->
        @php
        $computedSubtotal = collect($items)->sum(function($itm) {
        $p = (float) data_get($itm, 'unit_price', data_get($itm, 'product.price', data_get($itm, 'product.data.price', 0)));
        $q = (int) data_get($itm, 'quantity', 1);
        return $p * $q;
        });
        $rawTotal = (float) data_get($cart, 'total_price', data_get($cart, 'data.total_price', $computedSubtotal));
        @endphp

        <div class="space-y-2 text-xs text-slate-600 dark:text-slate-400 border-t border-b border-slate-200 dark:border-slate-800 py-3">
          <div class="flex justify-between">
            <span>Subtotal Produk</span>
            <span class="font-medium text-slate-900 dark:text-white">
              Rp <span id="summary-subtotal" data-raw="{{ $rawTotal }}">{{ number_format($rawTotal, 0, ',', '.') }}</span>
            </span>
          </div>
          <div class="flex justify-between">
            <span id="summary-shipping-label">Ongkos Kirim (Gojek Instant)</span>
            <span class="font-medium text-slate-900 dark:text-white">
              Rp <span id="summary-shipping-cost">25.000</span>
            </span>
          </div>
          <div class="flex justify-between">
            <span>Pengemasan Khusus Aman</span>
            <span class="font-semibold text-emerald-600 dark:text-emerald-400">GRATIS</span>
          </div>
          <div class="flex justify-between text-rose-500">
            <span>Diskon Voucher</span>
            <span class="font-medium">-Rp <span id="summary-discount">0</span></span>
          </div>
        </div>

        <!-- TOTAL AKHIR -->
        <div class="flex items-center justify-between">
          <div>
            <span class="text-xs text-slate-400 block">Total Pembayaran</span>
            <span class="text-xl font-black text-amber-600 dark:text-amber-400">
              Rp <span id="summary-grand-total">0</span>
            </span>
          </div>
        </div>

        <!-- NOTE SAFETY -->
        <div class="p-3 bg-amber-500/10 rounded-xl border border-amber-500/20 text-[11px] text-amber-700 dark:text-amber-300 flex items-center gap-2">
          <i class="fa-solid fa-shield-halved text-sm flex-shrink-0"></i>
          <span>Pembayaran aman diproses secara terenkripsi oleh Payment Gateway.</span>
        </div>

        <!-- BUTTON TRIGGER BAYAR SEKARANG DESKTOP -->
        <button type="button" id="btn-checkout" onclick="processCheckout()" class="w-full bg-amber-500 hover:bg-amber-600 dark:bg-amber-400 dark:hover:bg-amber-500 text-slate-950 font-extrabold py-3.5 rounded-xl text-center text-sm transition-all shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
          <span>Bayar Sekarang</span> <i class="fa-solid fa-arrow-right"></i>
        </button>

      </div>
    </div>

  </div>
</div>

<!-- CUSTOM MODAL KONFIRMASI HAPUS SEMUA (CLEAR CART) -->
<div id="clear-cart-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 p-4">
  <div id="clear-cart-modal-content" class="w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-2xl border border-slate-200 dark:border-slate-800 transform scale-95 transition-transform duration-300 text-center">
    <div class="w-12 h-12 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto mb-4 text-xl">
      <i class="fa-solid fa-trash-arrow-up"></i>
    </div>
    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">Kosongkan Keranjang?</h3>
    <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">
      Semua produk yang ada di dalam keranjang belanja Anda akan dihapus. Action ini tidak dapat dibatalkan.
    </p>
    <div class="flex items-center gap-3">
      <button type="button" onclick="closeClearCartModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold py-2.5 rounded-xl text-xs transition-colors">
        Batal
      </button>
      <button type="button" onclick="confirmClearCart()" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white font-bold py-2.5 rounded-xl text-xs transition-colors shadow-md shadow-rose-500/20">
        Ya, Kosongkan
      </button>
    </div>
  </div>
</div>

<!-- BOTTOM SHEET MODAL DAFTAR ALAMAT -->
<div id="address-modal" class="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
  <div id="address-modal-content" class="w-full max-w-xl bg-white dark:bg-slate-900 rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl border border-slate-200 dark:border-slate-800 transform translate-y-full transition-transform duration-300 max-h-[85vh] flex flex-col">

    <!-- Modal Header -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
      <div>
        <h3 class="text-base font-bold text-slate-900 dark:text-white">Pilih Alamat Pengiriman</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400">Pilih salah satu alamat tersimpan untuk pengiriman pesanan kamu.</p>
      </div>
      <button type="button" onclick="closeAddressModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- Address Item List -->
    <div class="py-4 space-y-3 overflow-y-auto flex-1">

      <!-- Alamat 1 (Aktif) -->
      <label class="address-option flex items-start gap-3 p-3.5 rounded-xl border-2 border-amber-500 bg-amber-500/5 cursor-pointer transition-all" onclick="highlightAddressOption(this)">
        <input type="radio" name="selected_address_id" value="1" checked onchange="selectAddress('Alex Wijaya', '(+62 812-3456-7890)', 'Jl. Senopati No. 45, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12190')" class="mt-1 text-amber-500 focus:ring-amber-500">
        <div class="flex-1">
          <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-900 dark:text-white">Alex Wijaya</span>
            <span class="px-2 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 font-semibold text-[10px] rounded">Utama</span>
            <span class="text-xs text-slate-500 dark:text-slate-400">(+62 812-3456-7890)</span>
          </div>
          <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
            Jl. Senopati No. 45, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12190
          </p>
        </div>
      </label>

      <!-- Alamat 2 -->
      <label class="address-option flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer transition-all" onclick="highlightAddressOption(this)">
        <input type="radio" name="selected_address_id" value="2" onchange="selectAddress('Alex Wijaya (Kantor)', '(+62 812-9988-7766)', 'Gedung SCBD Tower B Lt. 12, Jl. Jend. Sudirman Kav. 52, Jakarta Selatan')" class="mt-1 text-amber-500 focus:ring-amber-500">
        <div class="flex-1">
          <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-900 dark:text-white">Alex Wijaya (Kantor)</span>
            <span class="text-xs text-slate-500 dark:text-slate-400">(+62 812-9988-7766)</span>
          </div>
          <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
            Gedung SCBD Tower B Lt. 12, Jl. Jend. Sudirman Kav. 52, Jakarta Selatan
          </p>
        </div>
      </label>

    </div>

    <!-- Modal Footer Action -->
    <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex gap-3">
      <button type="button" onclick="closeAddressModal()" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 rounded-xl text-xs transition-colors shadow-sm">
        Gunakan Alamat Ini
      </button>
    </div>

  </div>
</div>

<!-- MOBILE STICKY CHECKOUT BAR (Z-40) -->
<div class="fixed bottom-14 sm:bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 lg:hidden p-4 shadow-2xl">
  <div class="flex items-center justify-between gap-3 max-w-md mx-auto">
    <div>
      <span class="text-[10px] text-slate-400 block">Total Pembayaran</span>
      <span class="text-lg font-black text-amber-600 dark:text-amber-400">
        Rp <span id="mobile-grand-total">0</span>
      </span>
    </div>
    <button type="button" onclick="processCheckout()" class="bg-amber-500 dark:bg-amber-400 text-slate-950 font-extrabold px-6 py-3 rounded-xl text-xs shadow-md active:scale-95 transition-transform">
      Bayar Sekarang <i class="fa-solid fa-arrow-right ms-1"></i>
    </button>
  </div>
</div>
@endsection

@push('scripts')
<!-- JAVASCRIPT LOGIC REAL-TIME CHECKOUT & VOUCHER -->
<script>
  let currentFulfillment = 'delivery';
  let currentShippingCost = 25000;
  let currentDiscount = 0;
  let activeVoucherCode = '';

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Initialization
  document.addEventListener('DOMContentLoaded', () => {
    recalculateSummary();
  });

  // 1. Switch Delivery vs Pick Up
  function switchFulfillment(type) {
    currentFulfillment = type;
    const secAddress = document.getElementById('section-address');
    const secPickup = document.getElementById('section-store-pickup');
    const secCourier = document.getElementById('section-courier');

    const lblDelivery = document.getElementById('label-fulfillment-delivery');
    const lblPickup = document.getElementById('label-fulfillment-pickup');

    const iconDelivery = document.getElementById('icon-fulfillment-delivery');
    const titleDelivery = document.getElementById('title-fulfillment-delivery');

    const iconPickup = document.getElementById('icon-fulfillment-pickup');
    const titlePickup = document.getElementById('title-fulfillment-pickup');

    if (type === 'pickup') {
      secAddress.classList.add('hidden');
      secCourier.classList.add('hidden');
      secPickup.classList.remove('hidden');

      lblPickup.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
      lblPickup.classList.remove('border-slate-200', 'dark:border-slate-800');
      lblPickup.querySelector('.check-icon').classList.remove('hidden');

      iconPickup.className = 'fa-solid fa-store text-amber-500 text-xl transition-colors';
      titlePickup.className = 'text-sm font-bold block text-amber-600 dark:text-amber-400 transition-colors';

      lblDelivery.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
      lblDelivery.classList.add('border-slate-200', 'dark:border-slate-800');
      lblDelivery.querySelector('.check-icon').classList.add('hidden');

      iconDelivery.className = 'fa-solid fa-truck-ramp-box text-slate-400 text-xl transition-colors';
      titleDelivery.className = 'text-sm font-bold block text-slate-900 dark:text-white transition-colors';

      document.getElementById('summary-shipping-label').innerText = 'Pengambilan Toko (Self Pickup)';
      document.getElementById('summary-shipping-cost').innerText = '0';
    } else {
      secAddress.classList.remove('hidden');
      secCourier.classList.remove('hidden');
      secPickup.classList.add('hidden');

      lblDelivery.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
      lblDelivery.classList.remove('border-slate-200', 'dark:border-slate-800');
      lblDelivery.querySelector('.check-icon').classList.remove('hidden');

      iconDelivery.className = 'fa-solid fa-truck-ramp-box text-amber-500 text-xl transition-colors';
      titleDelivery.className = 'text-sm font-bold block text-amber-600 dark:text-amber-400 transition-colors';

      lblPickup.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
      lblPickup.classList.add('border-slate-200', 'dark:border-slate-800');
      lblPickup.querySelector('.check-icon').classList.add('hidden');

      iconPickup.className = 'fa-solid fa-store text-slate-400 text-xl transition-colors';
      titlePickup.className = 'text-sm font-bold block text-slate-900 dark:text-white transition-colors';

      document.getElementById('summary-shipping-label').innerText = 'Ongkos Kirim';
      document.getElementById('summary-shipping-cost').innerText = formatRupiah(currentShippingCost);
    }
    recalculateSummary();
  }

  // 2. Bottom Sheet Modal Address
  function openAddressModal() {
    const modal = document.getElementById('address-modal');
    const content = document.getElementById('address-modal-content');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    content.classList.remove('translate-y-full');
  }

  function closeAddressModal() {
    const modal = document.getElementById('address-modal');
    const content = document.getElementById('address-modal-content');
    content.classList.add('translate-y-full');
    modal.classList.add('opacity-0', 'pointer-events-none');
  }

  function highlightAddressOption(selectedEl) {
    document.querySelectorAll('.address-option').forEach(el => {
      el.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
      el.classList.add('border', 'border-slate-200', 'dark:border-slate-800');
    });
    selectedEl.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
    selectedEl.classList.remove('border-slate-200', 'dark:border-slate-800');
  }

  function selectAddress(name, phone, detail) {
    document.getElementById('display-address-name').innerText = name;
    document.getElementById('display-address-phone').innerText = phone;
    document.getElementById('display-address-detail').innerText = detail;
  }

  // 3. Select Courier Option
  function selectCourier(cost, name, evt) {
    currentShippingCost = cost;
    document.getElementById('summary-shipping-label').innerText = `Ongkos Kirim (${name})`;
    document.getElementById('summary-shipping-cost').innerText = formatRupiah(cost);

    document.querySelectorAll('.courier-option').forEach(el => {
      el.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
      el.classList.add('border', 'border-slate-200', 'dark:border-slate-800');
      el.querySelector('.check-icon').classList.add('hidden');

      const priceEl = el.querySelector('.courier-price');
      if (priceEl) {
        priceEl.className = 'text-xs font-extrabold text-slate-900 dark:text-white block courier-price';
      }
    });

    const activeEl = evt.currentTarget;
    activeEl.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
    activeEl.querySelector('.check-icon').classList.remove('hidden');

    const activePriceEl = activeEl.querySelector('.courier-price');
    if (activePriceEl) {
      activePriceEl.className = 'text-xs font-extrabold text-amber-600 dark:text-amber-400 block courier-price';
    }

    recalculateSummary();
  }

  // 4. Custom Clear Cart Modal Logic
  function openClearCartModal() {
    const cartContainer = document.getElementById('cart-items-container');
    if (!cartContainer || cartContainer.querySelectorAll('.cart-item-row').length === 0) {
      return;
    }
    const modal = document.getElementById('clear-cart-modal');
    const content = document.getElementById('clear-cart-modal-content');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    content.classList.remove('scale-95');
    content.classList.add('scale-100');
  }

  function closeClearCartModal() {
    const modal = document.getElementById('clear-cart-modal');
    const content = document.getElementById('clear-cart-modal-content');
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    modal.classList.add('opacity-0', 'pointer-events-none');
  }

  function confirmClearCart() {
    closeClearCartModal();

    const cartContainer = document.getElementById('cart-items-container');
    cartContainer.innerHTML = `
        <div class="py-12 text-center text-slate-400 text-xs" id="empty-cart-msg">
          Keranjang Anda masih kosong. <a href="{{ route('catalog.index') }}" class="text-amber-500 underline font-bold">Mulai belanja</a>
        </div>
      `;

    removeVoucher();
    recalculateSummary();

    fetch("{{ route('cart.clear') }}", {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        console.log(data.message || 'Keranjang berhasil dikosongkan.');
      })
      .catch(err => console.error('Error clearing cart:', err));
  }

  // 5. Increment / Decrement Quantity Via AJAX (Named Route: cart.update)
  function updateQty(itemId, delta) {
    const qtyElement = document.getElementById(`qty-${itemId}`);
    let currentQty = parseInt(qtyElement.innerText);
    let newQty = currentQty + delta;

    if (newQty < 1) return;

    const unitPrice = parseFloat(qtyElement.getAttribute('data-unit-price'));

    qtyElement.innerText = newQty;
    const newSubtotal = unitPrice * newQty;
    const subtotalEl = document.getElementById(`subtotal-${itemId}`);
    subtotalEl.innerText = formatRupiah(newSubtotal);
    subtotalEl.setAttribute('data-raw', newSubtotal);

    recalculateSummary();

    if (activeVoucherCode) {
      applyVoucher(true);
    }

    const updateUrl = "{{ route('cart.update', ':id') }}".replace(':id', itemId);

    fetch(updateUrl, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        quantity: newQty
      })
    }).catch(err => console.error('Error updating cart:', err));
  }

  // 6. Hapus Item Realtime (Named Route: cart.destroy)
  function removeItem(itemId) {
    const itemRow = document.getElementById(`cart-item-${itemId}`);
    if (itemRow) itemRow.remove();

    checkEmptyState();
    recalculateSummary();

    if (activeVoucherCode) {
      applyVoucher(true);
    }

    const deleteUrl = "{{ route('cart.destroy', ':id') }}".replace(':id', itemId);

    fetch(deleteUrl, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    }).catch(err => console.error('Error deleting item:', err));
  }

  function checkEmptyState() {
    const cartContainer = document.getElementById('cart-items-container');
    if (cartContainer && cartContainer.querySelectorAll('.cart-item-row').length === 0) {
      cartContainer.innerHTML = `
          <div class="py-12 text-center text-slate-400 text-xs" id="empty-cart-msg">
            Keranjang Anda masih kosong. <a href="{{ route('catalog.index') }}" class="text-amber-500 underline font-bold">Mulai belanja</a>
          </div>
        `;
      removeVoucher();
    }
  }

  // 7. REALTIME APPLY & REMOVE VOUCHER (AJAX Via cart.voucher.apply)
  function applyVoucher(isSilent = false) {
    const voucherInput = document.getElementById('voucher-code');
    const applyBtn = document.getElementById('btn-apply-voucher');
    const removeBtn = document.getElementById('btn-remove-voucher');
    const code = voucherInput.value.trim().toUpperCase();

    if (!code) {
      showVoucherMsg('Masukkan kode promo terlebih dahulu.', 'error');
      return;
    }

    if (!isSilent) {
      applyBtn.disabled = true;
      applyBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
    }

    fetch("{{ route('cart.voucher.apply') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ code: code })
    })
    .then(res => res.json().then(data => ({ status: res.status, body: data })))
    .then(res => {
      applyBtn.disabled = false;
      applyBtn.innerHTML = 'Pakai';

      if ((res.status === 200 || res.status === 201) && res.body.data) {
        currentDiscount = parseFloat(res.body.data.discount_amount || 0);
        activeVoucherCode = code;

        voucherInput.value = code;
        voucherInput.disabled = true;
        applyBtn.classList.add('hidden');
        removeBtn.classList.remove('hidden');

        showVoucherMsg(res.body.message || 'Voucher berhasil digunakan!', 'success');
        recalculateSummary();
      } else {
        currentDiscount = 0;
        activeVoucherCode = '';
        showVoucherMsg(res.body.message || 'Kode promo tidak valid.', 'error');
        recalculateSummary();
      }
    })
    .catch(err => {
      applyBtn.disabled = false;
      applyBtn.innerHTML = 'Pakai';
      showVoucherMsg('Gagal memproses voucher. Coba beberapa saat lagi.', 'error');
      console.error(err);
    });
  }

  function removeVoucher() {
    const voucherInput = document.getElementById('voucher-code');
    const applyBtn = document.getElementById('btn-apply-voucher');
    const removeBtn = document.getElementById('btn-remove-voucher');

    currentDiscount = 0;
    activeVoucherCode = '';

    voucherInput.value = '';
    voucherInput.disabled = false;
    applyBtn.classList.remove('hidden');
    removeBtn.classList.add('hidden');

    hideVoucherMsg();
    recalculateSummary();
  }

  function showVoucherMsg(msg, type) {
    const el = document.getElementById('voucher-message');
    el.innerText = msg;
    el.classList.remove('hidden', 'text-emerald-500', 'dark:text-emerald-400', 'text-rose-500');

    if (type === 'success') {
      el.classList.add('text-emerald-500', 'dark:text-emerald-400');
    } else {
      el.classList.add('text-rose-500');
    }
  }

  function hideVoucherMsg() {
    const el = document.getElementById('voucher-message');
    el.innerText = '';
    el.classList.add('hidden');
  }

  // 8. Kalkulasi Ulang Total Ringkasan
  function recalculateSummary() {
    let subtotalSum = 0;
    document.querySelectorAll('.item-subtotal').forEach(el => {
      subtotalSum += parseFloat(el.getAttribute('data-raw') || 0);
    });

    document.getElementById('summary-subtotal').innerText = formatRupiah(subtotalSum);
    document.getElementById('summary-discount').innerText = formatRupiah(currentDiscount);

    const effectiveShipping = currentFulfillment === 'pickup' ? 0 : currentShippingCost;
    const grandTotal = Math.max(0, subtotalSum + effectiveShipping - currentDiscount);

    document.getElementById('summary-grand-total').innerText = formatRupiah(grandTotal);
    document.getElementById('mobile-grand-total').innerText = formatRupiah(grandTotal);
  }

  // 9. Format Number ke Rupiah
  function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID').format(number);
  }

  // 10. Trigger Midtrans Snap
  function processCheckout() {
    const btn = document.getElementById('btn-checkout');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...';

    const payload = {
      fulfillment_type: currentFulfillment,
      shipping_cost: currentFulfillment === 'pickup' ? 0 : currentShippingCost,
      store_id: currentFulfillment === 'pickup' ? document.querySelector('input[name="pickup_store_id"]:checked')?.value : 1,
      user_address_id: currentFulfillment === 'delivery' ? document.querySelector('input[name="selected_address_id"]:checked')?.value : 1,
      courier_company: 'gojek',
      courier_type: 'instant',
      payment_method: 'midtrans',
      voucher_code: activeVoucherCode || null,
      notes: document.getElementById('delivery-note')?.value || ''
    };

    fetch("{{ route('orders.store') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = 'Bayar Sekarang <i class="fa-solid fa-arrow-right"></i>';

        if (data.snap_token) {
          window.snap.pay(data.snap_token, {
            onSuccess: function(result) {
              window.location.href = "/orders/" + data.order.id;
            },
            onPending: function(result) {
              window.location.href = "/orders";
            },
            onError: function(result) {
              alert("Pembayaran gagal, silakan coba lagi.");
            },
            onClose: function() {
              alert("Anda menutup halaman pembayaran sebelum menyelesaikan transaksi.");
            }
          });
        } else {
          alert(data.message || 'Gagal memproses pesanan.');
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerHTML = 'Bayar Sekarang <i class="fa-solid fa-arrow-right"></i>';
        console.error(err);
      });
  }
</script>
@endpush