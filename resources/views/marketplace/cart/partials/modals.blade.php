<!-- CUSTOM MODAL KONFIRMASI HAPUS SEMUA (CLEAR CART) -->
<div id="clear-cart-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 p-4">
  <div id="clear-cart-modal-content" class="w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-2xl border border-slate-200 dark:border-slate-800 transform scale-95 transition-transform duration-300 text-center">
    <div class="w-12 h-12 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto mb-4 text-xl">
      <i class="fa-solid fa-trash-arrow-up"></i>
    </div>
    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">Kosongkan Keranjang?</h3>
    <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">
      Semua produk yang ada di dalam keranjang belanja Anda akan dihapus. Akses ini tidak dapat dibatalkan.
    </p>
    <div class="flex items-center gap-3">
      <button type="button" onclick="closeClearCartModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold py-2.5 rounded-xl text-xs transition-colors cursor-pointer">
        Batal
      </button>
      <button type="button" onclick="confirmClearCart()" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white font-bold py-2.5 rounded-xl text-xs transition-colors shadow-md shadow-rose-500/20 cursor-pointer">
        Ya, Kosongkan
      </button>
    </div>
  </div>
</div>

<!-- BOTTOM SHEET MODAL DAFTAR ALAMAT -->
<div id="address-modal" class="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
  <div id="address-modal-content" class="w-full max-w-xl bg-white dark:bg-slate-900 rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl border border-slate-200 dark:border-slate-800 transform translate-y-full transition-transform duration-300 max-h-[85vh] flex flex-col">
    <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
      <div>
        <h3 class="text-base font-bold text-slate-900 dark:text-white">Pilih Alamat Pengiriman</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400">Pilih salah satu alamat tersimpan untuk pengiriman pesanan kamu.</p>
      </div>
      <button type="button" onclick="closeAddressModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors cursor-pointer">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <div class="py-4 space-y-3 overflow-y-auto flex-1" id="address-modal-list">
      @forelse($addresses as $addr)
        @php
          $addrId = data_get($addr, 'id');
          $name = data_get($addr, 'recipient_name');
          $phone = data_get($addr, 'recipient_phone');
          $fullAddr = data_get($addr, 'full_address');
          $label = data_get($addr, 'label', 'Alamat');
          $isPrimary = data_get($addr, 'is_primary', false) || $loop->first;
          $lat = data_get($addr, 'latitude');
          $lng = data_get($addr, 'longitude');
        @endphp

        <label class="address-option flex items-start gap-3 p-3.5 rounded-xl border {{ $isPrimary ? 'border-2 border-amber-500 bg-amber-500/5' : 'border-slate-200 dark:border-slate-800 hover:border-amber-400' }} cursor-pointer transition-all" onclick="highlightAddressOption(this)">
          <input type="radio" name="selected_address_id" value="{{ $addrId }}" {{ $isPrimary ? 'checked' : '' }} onchange="selectAddress('{{ addslashes($name) }}', '({{ addslashes($phone) }})', '{{ addslashes($fullAddr) }}', '{{ addslashes($label) }}', '{{ $lat }}', '{{ $lng }}')" class="mt-1 text-amber-500 focus:ring-amber-500">
          <div class="flex-1">
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $name }}</span>
              <span class="px-2 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 font-semibold text-[10px] rounded">{{ $label }}</span>
              <span class="text-xs text-slate-500 dark:text-slate-400">({{ $phone }})</span>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
              {{ $fullAddr }}
            </p>
          </div>
        </label>
      @empty
        <div class="p-6 text-center text-xs text-slate-400">
          Belum ada alamat pengiriman tersimpan.
        </div>
      @endforelse
    </div>

    <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex gap-3">
      <button type="button" onclick="closeAddressModal()" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 rounded-xl text-xs transition-colors shadow-sm cursor-pointer">
        Gunakan Alamat Ini
      </button>
    </div>
  </div>
</div>

<!-- BOTTOM SHEET MODAL PILIH OUTLET STORE -->
<div id="store-modal" class="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
  <div id="store-modal-content" class="w-full max-w-xl bg-white dark:bg-slate-900 rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl border border-slate-200 dark:border-slate-800 transform translate-y-full transition-transform duration-300 max-h-[85vh] flex flex-col">
    <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
      <div>
        <h3 class="text-base font-bold text-slate-900 dark:text-white">Pilih Store Pengirim / Pick Up</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400">Pilih cabang toko tempat pemrosesan stok barang Anda.</p>
      </div>
      <button type="button" onclick="closeStoreModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors cursor-pointer">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <div class="py-4 space-y-3 overflow-y-auto flex-1" id="store-modal-list">
      @forelse($stores as $store)
        @php
          $sId = data_get($store, 'id');
          $sName = data_get($store, 'name');
          $sAddr = data_get($store, 'full_address') ?? data_get($store, 'address') ?? '-';
          $sLat = data_get($store, 'latitude');
          $sLng = data_get($store, 'longitude');
          $sOpen = data_get($store, 'open_time') ? \Carbon\Carbon::parse(data_get($store, 'open_time'))->format('H:i') : '';
          $sClose = data_get($store, 'close_time') ? \Carbon\Carbon::parse(data_get($store, 'close_time'))->format('H:i') : '';
          $sHours = ($sOpen && $sClose) ? "{$sOpen} - {$sClose}" : 'Jam operasional belum diatur';
          $isFirst = $loop->first;
        @endphp

        <label class="store-option flex items-start gap-3 p-3.5 rounded-xl border {{ $isFirst ? 'border-2 border-amber-500 bg-amber-500/5' : 'border-slate-200 dark:border-slate-800 hover:border-amber-400' }} transition-all"
               data-store-id="{{ $sId }}"
               data-open-time="{{ $sOpen }}"
               data-close-time="{{ $sClose }}"
               onclick="handleStoreOptionClick(this, '{{ $sId }}', '{{ addslashes($sName) }}', '{{ addslashes($sAddr) }}', '{{ $sHours }}', '{{ $sOpen }}', '{{ $sClose }}')">
          <input type="radio" name="modal_store_id" value="{{ $sId }}" data-lat="{{ $sLat }}" data-lng="{{ $sLng }}" {{ $isFirst ? 'checked' : '' }} class="mt-1 text-amber-500 focus:ring-amber-500 store-radio-input">
          <div class="flex-1">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="text-xs font-bold text-slate-900 dark:text-white block">{{ $sName }}</span>
              <span class="modal-store-status-badge px-2 py-0.5 text-[9px] font-bold rounded hidden"></span>
            </div>
            <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-0.5">{{ $sAddr }}</span>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold mt-1 block">
              <i class="fa-regular fa-clock me-1"></i> Jam Buka: {{ $sHours }} WIB
            </span>
          </div>
        </label>
      @empty
        <div class="p-6 text-center text-xs text-slate-400">
          Belum ada data toko cabang yang tersedia.
        </div>
      @endforelse
    </div>

    <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex gap-3">
      <button type="button" onclick="closeStoreModal()" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 rounded-xl text-xs transition-colors shadow-sm cursor-pointer">
        Pilih Store Ini
      </button>
    </div>
  </div>
</div>