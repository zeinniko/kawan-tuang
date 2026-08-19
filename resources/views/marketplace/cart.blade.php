@extends('welcome')

@section('title', 'Keranjang & Checkout - Kawan Tuang')

@push('styles')
  <!-- Midtrans Snap JS (Sandbox Mode) -->
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endpush

@section('content')
  <!-- MAIN CONTAINER -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
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
                <i class="fa-solid fa-truck-ramp-box text-amber-500 text-xl"></i>
                <div>
                  <span class="text-sm font-bold block text-slate-900 dark:text-white">Delivery</span>
                  <span class="text-[11px] text-slate-500">Dikirim ke Alamat Anda</span>
                </div>
              </div>
              <i class="fa-solid fa-circle-check text-amber-500 check-icon"></i>
            </label>

            <!-- Option Pick Up -->
            <label id="label-fulfillment-pickup" class="relative flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer transition-all">
              <input type="radio" name="fulfillment_type" value="pickup" class="hidden" onchange="switchFulfillment('pickup')">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-store text-slate-400 text-xl"></i>
                <div>
                  <span class="text-sm font-bold block text-slate-900 dark:text-white">Pick Up</span>
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
            <button onclick="clearAllCart()" class="text-xs text-rose-500 hover:underline font-medium">Hapus Semua</button>
          </div>

          <div class="divide-y divide-slate-100 dark:divide-slate-800/60" id="cart-items-container">
            @php
              // Handle unwrap data dari API Resource maupun array biasa
              $items = data_get($cart, 'items', data_get($cart, 'data.items', []));
            @endphp

            @forelse($items as $item)
              @php
                $itemId = data_get($item, 'id');
                
                // Ambil Nama Produk
                $prodName = data_get($item, 'product.name', data_get($item, 'product.data.name', 'Produk'));
                
                // Ambil Gambar Produk
                $prodImg = data_get($item, 'product.thumbnail_url', 
                           data_get($item, 'product.data.thumbnail_url', 
                           data_get($item, 'product.primary_image.image_url', 
                           'https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80')));
                
                // Ambil Harga Unit
                $unitPrice = (float) data_get($item, 'unit_price', 
                             data_get($item, 'product.price', 
                             data_get($item, 'product.data.price', 0)));
                
                // Ambil Kuantitas
                $qty = (int) data_get($item, 'quantity', 1);
                
                // Hitung Subtotal
                $subtotal = (float) data_get($item, 'subtotal', $unitPrice * $qty);
              @endphp

              <div class="py-4 flex gap-3 sm:gap-4 items-center cart-item-row" id="cart-item-{{ $itemId }}">
                
                <!-- Gambar Produk -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center flex-shrink-0 overflow-hidden">
                  <img src="{{ $prodImg }}" alt="{{ $prodName }}" class="w-full h-full object-cover">
                </div>

                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-2">
                    <div>
                      <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $prodName }}</h3>
                      <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        Rp <span class="unit-price">{{ number_format($unitPrice, 0, ',', '.') }}</span> / botol
                      </p>
                    </div>

                    <!-- Tombol Hapus -->
                    <button onclick="removeItem('{{ $itemId }}')" class="text-slate-400 hover:text-rose-500 text-xs p-1">
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                  </div>

                  <div class="flex items-center justify-between mt-3">
                    <span class="text-sm font-extrabold text-amber-600 dark:text-amber-400">
                      Rp <span class="item-subtotal" id="subtotal-{{ $itemId }}" data-raw="{{ $subtotal }}">{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </span>

                    <!-- Increment / Decrement Realtime tanpa refresh -->
                    <div class="flex items-center bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                      <button type="button" onclick="updateQty('{{ $itemId }}', -1)" class="w-7 h-7 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-l-lg">-</button>
                      
                      <span class="w-8 text-center text-xs font-bold text-slate-900 dark:text-white" id="qty-{{ $itemId }}" data-unit-price="{{ $unitPrice }}">{{ $qty }}</span>
                      
                      <button type="button" onclick="updateQty('{{ $itemId }}', 1)" class="w-7 h-7 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-r-lg">+</button>
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
            <button class="text-xs text-amber-600 dark:text-amber-400 font-bold hover:underline">Ubah Alamat</button>
          </div>
          <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-1">
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold text-slate-900 dark:text-white">Alex Wijaya</span>
              <span class="px-2 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 font-semibold text-[10px] rounded">Utama</span>
              <span class="text-xs text-slate-500 dark:text-slate-400">(+62 812-3456-7890)</span>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
              Jl. Senopati No. 45, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12190
            </p>
            <input type="text" id="delivery-note" placeholder="Catatan lokasi/driver (cth: Titip di Satpam)" class="w-full mt-2 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-3 py-2 text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
          </div>
        </div>

        <!-- SECTION 4: PILIHAN CABANG (PICKUP MODE) -->
        <div id="section-store-pickup" class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm hidden transition-all">
          <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-3">
            <i class="fa-solid fa-store text-amber-500"></i> Pilih Outlet Pengambilan
          </h2>
          <div class="space-y-3">
            <label class="flex items-start gap-3 p-3 rounded-xl border-2 border-amber-500 bg-amber-500/5 cursor-pointer">
              <input type="radio" name="pickup_store_id" value="store-senopati" checked class="mt-1 text-amber-500 focus:ring-amber-500">
              <div>
                <span class="text-xs font-bold text-slate-900 dark:text-white block">Kawan Tuang - Senopati (Pusat)</span>
                <span class="text-[11px] text-slate-500 dark:text-slate-400 block">Jl. Senopati No.45, Kebayoran Baru, Jakarta Selatan</span>
                <span class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold mt-1 block"><i class="fa-regular fa-clock me-1"></i> Buka Hari Ini: 10:00 - 24:00</span>
              </div>
            </label>
            <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer">
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
            
            <label class="relative flex flex-col justify-between p-3 rounded-xl border-2 border-amber-500 bg-amber-500/5 cursor-pointer courier-option" onclick="selectCourier(25000, 'Gojek Instant', event)">
              <input type="radio" name="shipping" value="25000" checked class="hidden">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-900 dark:text-white">Gojek Instant</span>
                <i class="fa-solid fa-circle-check text-amber-500 text-sm check-icon"></i>
              </div>
              <div class="mt-2">
                <span class="text-xs font-extrabold text-amber-600 dark:text-amber-400 block">Rp 25.000</span>
                <span class="text-[10px] text-slate-500">Estimasi 1 Jam Sampai</span>
              </div>
            </label>

            <label class="relative flex flex-col justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer courier-option" onclick="selectCourier(27000, 'GrabExpress', event)">
              <input type="radio" name="shipping" value="27000" class="hidden">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-900 dark:text-white">GrabExpress</span>
                <i class="fa-solid fa-circle-check text-amber-500 text-sm check-icon hidden"></i>
              </div>
              <div class="mt-2">
                <span class="text-xs font-extrabold text-slate-900 dark:text-white block">Rp 27.000</span>
                <span class="text-[10px] text-slate-500">Estimasi 1-2 Jam Sampai</span>
              </div>
            </label>

            <label class="relative flex flex-col justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-amber-400 cursor-pointer courier-option" onclick="selectCourier(35000, 'Paxel Cold Chain', event)">
              <input type="radio" name="shipping" value="35000" class="hidden">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-900 dark:text-white">Paxel Cold</span>
                <i class="fa-solid fa-circle-check text-amber-500 text-sm check-icon hidden"></i>
              </div>
              <div class="mt-2">
                <span class="text-xs font-extrabold text-slate-900 dark:text-white block">Rp 35.000</span>
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
              <button type="button" onclick="applyVoucher()" class="bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 text-amber-400 font-bold px-3 py-2 rounded-xl text-xs transition-colors">Pakai</button>
            </div>
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
            <span>Pembayaran aman diproses secara terenkripsi oleh Midtrans.</span>
          </div>

          <!-- BUTTON TRIGGER BAYAR SEKARANG -->
          <button type="button" id="btn-checkout" onclick="processCheckout()" class="w-full bg-amber-500 hover:bg-amber-600 dark:bg-amber-400 dark:hover:bg-amber-500 text-slate-950 font-extrabold py-3.5 rounded-xl text-center text-sm transition-all shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
            <span>Bayar Sekarang</span> <i class="fa-solid fa-arrow-right"></i>
          </button>

        </div>
      </div>

    </div>
  </div>

  <!-- MOBILE STICKY CHECKOUT BAR -->
  <div class="fixed bottom-14 sm:bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 lg:hidden p-4">
    <div class="flex items-center justify-between gap-3 max-w-md mx-auto">
      <div>
        <span class="text-[10px] text-slate-400 block">Total Pembayaran</span>
        <span class="text-lg font-black text-amber-600 dark:text-amber-400">
          Rp <span id="mobile-grand-total">0</span>
        </span>
      </div>
      <button type="button" onclick="processCheckout()" class="bg-amber-500 dark:bg-amber-400 text-slate-950 font-extrabold px-6 py-3 rounded-xl text-xs shadow-md">
        Bayar Sekarang <i class="fa-solid fa-arrow-right ms-1"></i>
      </button>
    </div>
  </div>
@endsection

@push('scripts')
  <!-- JAVASCRIPT LOGIC REAL-TIME CHECKOUT -->
  <script>
    let currentFulfillment = 'delivery';
    let currentShippingCost = 25000;
    let currentDiscount = 0;

    // Retrieve CSRF from the layout's meta tag
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

      if (type === 'pickup') {
        secAddress.classList.add('hidden');
        secCourier.classList.add('hidden');
        secPickup.classList.remove('hidden');

        lblPickup.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
        lblPickup.classList.remove('border-slate-200', 'dark:border-slate-800');
        lblPickup.querySelector('.check-icon').classList.remove('hidden');

        lblDelivery.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
        lblDelivery.classList.add('border-slate-200', 'dark:border-slate-800');
        lblDelivery.querySelector('.check-icon').classList.add('hidden');

        document.getElementById('summary-shipping-label').innerText = 'Pengambilan Toko (Self Pickup)';
        document.getElementById('summary-shipping-cost').innerText = '0';
      } else {
        secAddress.classList.remove('hidden');
        secCourier.classList.remove('hidden');
        secPickup.classList.add('hidden');

        lblDelivery.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
        lblDelivery.classList.remove('border-slate-200', 'dark:border-slate-800');
        lblDelivery.querySelector('.check-icon').classList.remove('hidden');

        lblPickup.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
        lblPickup.classList.add('border-slate-200', 'dark:border-slate-800');
        lblPickup.querySelector('.check-icon').classList.add('hidden');

        document.getElementById('summary-shipping-label').innerText = 'Ongkos Kirim';
        document.getElementById('summary-shipping-cost').innerText = formatRupiah(currentShippingCost);
      }
      recalculateSummary();
    }

    // 2. Select Courier Option
    function selectCourier(cost, name, evt) {
      currentShippingCost = cost;
      document.getElementById('summary-shipping-label').innerText = `Ongkos Kirim (${name})`;
      document.getElementById('summary-shipping-cost').innerText = formatRupiah(cost);

      document.querySelectorAll('.courier-option').forEach(el => {
        el.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
        el.classList.add('border', 'border-slate-200', 'dark:border-slate-800');
        el.querySelector('.check-icon').classList.add('hidden');
      });

      const activeEl = evt.currentTarget;
      activeEl.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
      activeEl.querySelector('.check-icon').classList.remove('hidden');

      recalculateSummary();
    }

    // 3. Increment / Decrement Quantity Via AJAX
    function updateQty(itemId, delta) {
      const qtyElement = document.getElementById(`qty-${itemId}`);
      let currentQty = parseInt(qtyElement.innerText);
      let newQty = currentQty + delta;

      if (newQty < 1) return;

      const unitPrice = parseFloat(qtyElement.getAttribute('data-unit-price'));

      // Immediate UI Update
      qtyElement.innerText = newQty;
      const newSubtotal = unitPrice * newQty;
      const subtotalEl = document.getElementById(`subtotal-${itemId}`);
      subtotalEl.innerText = formatRupiah(newSubtotal);
      subtotalEl.setAttribute('data-raw', newSubtotal);

      recalculateSummary();

      // Async API Request
      fetch(`/cart/items/${itemId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: newQty })
      }).catch(err => console.error('Error updating cart:', err));
    }

    // 4. Hapus Item Realtime
    function removeItem(itemId) {
      const itemRow = document.getElementById(`cart-item-${itemId}`);
      if (itemRow) itemRow.remove();

      recalculateSummary();

      fetch(`/cart/items/${itemId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        }
      }).catch(err => console.error('Error deleting item:', err));
    }

    // 5. Kalkulasi Ulang Total Ringkasan
    function recalculateSummary() {
      let subtotalSum = 0;
      document.querySelectorAll('.item-subtotal').forEach(el => {
        subtotalSum += parseFloat(el.getAttribute('data-raw') || 0);
      });

      document.getElementById('summary-subtotal').innerText = formatRupiah(subtotalSum);

      const effectiveShipping = currentFulfillment === 'pickup' ? 0 : currentShippingCost;
      const grandTotal = Math.max(0, subtotalSum + effectiveShipping - currentDiscount);

      document.getElementById('summary-grand-total').innerText = formatRupiah(grandTotal);
      document.getElementById('mobile-grand-total').innerText = formatRupiah(grandTotal);
    }

    // 6. Format Number ke Rupiah
    function formatRupiah(number) {
      return new Intl.NumberFormat('id-ID').format(number);
    }

    // 7. Trigger Midtrans Snap
    function processCheckout() {
      const btn = document.getElementById('btn-checkout');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...';

      const payload = {
        fulfillment_type: currentFulfillment,
        shipping_cost: currentFulfillment === 'pickup' ? 0 : currentShippingCost,
        store_id: currentFulfillment === 'pickup' ? document.querySelector('input[name="pickup_store_id"]:checked')?.value : null,
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
              window.location.href = "/orders/" + result.order_id;
            },
            onPending: function(result) {
              window.location.href = "/orders";
            },
            onError: function(result) {
              alert("Pembayaran gagal, silakan coba lagi.");
            }
          });
        } else if (data.redirect_url) {
          window.location.href = data.redirect_url;
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