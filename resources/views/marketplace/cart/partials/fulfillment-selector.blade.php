<div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-5">

  <div class="flex items-center justify-between">
    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
      <i class="fa-solid fa-hand-holding-box text-amber-500"></i> Pilih Cara Pengambilan & Store
    </h2>
  </div>

  <!-- BANNER PERINGATAN GLOBAL UTAMA (JIKA METODE PICKUP TIDAK MEMENUHI SYARAT) -->
  <div id="pickup-validation-alert" class="hidden p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs font-medium space-y-1">
    <p class="font-bold flex items-center gap-1.5">
      <i class="fa-solid fa-triangle-exclamation"></i>
      <span id="pickup-alert-title">Layanan Pick Up Tidak Tersedia</span>
    </p>
    <p id="pickup-alert-desc" class="text-[11px] opacity-90">
      Maaf, layanan pengambilan di toko saat ini sedang tidak aktif.
    </p>
  </div>

  <!-- RADIO OPTION FULFILLMENT TYPE -->
  <div class="grid grid-cols-2 gap-4">
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

  <!-- SELECTED STORE DISPLAY BAR -->
  <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80">
    <div class="flex items-center justify-between mb-2">
      <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
        <i class="fa-solid fa-shop text-amber-500"></i> Store Pengirim / Asal Stok:
      </span>
      <button type="button" onclick="openStoreModal()" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline flex items-center gap-1 cursor-pointer">
        Ganti Store <i class="fa-solid fa-chevron-right text-[10px]"></i>
      </button>
    </div>

    @php
    $primaryAddress = collect($addresses)->firstWhere('is_primary', true) ?? ($addresses[0] ?? null);
    $primaryLat = data_get($primaryAddress, 'latitude');
    $primaryLng = data_get($primaryAddress, 'longitude');

    $defaultStore = $stores[0] ?? null;
    $defaultStoreId = data_get($defaultStore, 'id');
    $defaultStoreName = data_get($defaultStore, 'name', 'Kawan Tuang Store');
    $defaultStoreAddress = data_get($defaultStore, 'full_address', data_get($defaultStore, 'address', '-'));

    $rawOpenTime = data_get($defaultStore, 'open_time') ? \Carbon\Carbon::parse(data_get($defaultStore, 'open_time'))->format('H:i') : '';
    $rawCloseTime = data_get($defaultStore, 'close_time') ? \Carbon\Carbon::parse(data_get($defaultStore, 'close_time'))->format('H:i') : '';
    $defaultStoreHours = ($rawOpenTime && $rawCloseTime) ? "{$rawOpenTime} - {$rawCloseTime}" : 'Jam operasional belum diatur';
    @endphp

    <input type="hidden" name="store_id" id="selected-store-id" value="{{ $defaultStoreId }}">
    <input type="hidden" id="selected-store-open-time" value="{{ $rawOpenTime }}">
    <input type="hidden" id="selected-store-close-time" value="{{ $rawCloseTime }}">

    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3.5 border border-slate-200 dark:border-slate-700/60 flex items-start justify-between gap-3">
      <div class="space-y-0.5 flex-1">
        <div class="flex items-center gap-2 flex-wrap">
          <span id="selected-store-name" class="text-xs font-bold text-slate-900 dark:text-white">{{ $defaultStoreName }}</span>
          <span id="store-auto-tag" class="px-2 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[9px] font-bold rounded">Terdekat dari Alamat</span>
          <!-- Status Badge Toko -->
          <span id="store-status-badge" class="px-2 py-0.5 text-[9px] font-bold rounded"></span>
        </div>
        <p id="selected-store-address" class="text-xs text-slate-500 dark:text-slate-400 leading-tight pt-0.5">{{ $defaultStoreAddress }}</p>
        <span id="selected-store-hours" class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold block pt-1">
          <i class="fa-regular fa-clock me-1"></i> Jam Operasional: {{ $defaultStoreHours }} WIB
        </span>
      </div>
    </div>
  </div>

</div>

<script>
  window.isManualStoreSelected = false;

  document.addEventListener('DOMContentLoaded', () => {
    const pLat = "{{ $primaryLat ?? '' }}";
    const pLng = "{{ $primaryLng ?? '' }}";
    const initialStoreId = document.getElementById('selected-store-id')?.value;

    if (initialStoreId) {
      syncItemsStockWithSelectedStore(initialStoreId);
    }

    if (pLat && pLng) {
      fetchNearestStore(pLat, pLng);
    } else {
      validatePickupConditions();
    }
  });

  function checkStoreOperatingStatus(openTimeStr, closeTimeStr) {
    if (!openTimeStr || !closeTimeStr) {
      return {
        status: 'CLOSED',
        message: 'Jam operasional tidak tersedia'
      };
    }

    const now = new Date();
    const currentMinutes = now.getHours() * 60 + now.getMinutes();

    const [oH, oM] = openTimeStr.split(':').map(Number);
    const [cH, cM] = closeTimeStr.split(':').map(Number);

    if (isNaN(oH) || isNaN(oM) || isNaN(cH) || isNaN(cM)) {
      return {
        status: 'CLOSED',
        message: 'Jam operasional tidak valid'
      };
    }

    let openMinutes = oH * 60 + oM;
    let closeMinutes = cH * 60 + cM;
    const pickupCutoffMinutes = closeMinutes - 60;

    if (closeMinutes < openMinutes) {
      closeMinutes += 24 * 60;
      if (currentMinutes < openMinutes) {
        if ((currentMinutes + 24 * 60) >= closeMinutes) {
          return {
            status: 'CLOSED',
            message: 'Toko Sedang Tutup'
          };
        }
      }
    }

    if (currentMinutes < openMinutes || currentMinutes >= closeMinutes) {
      return {
        status: 'CLOSED',
        message: 'Toko Sedang Tutup'
      };
    }

    if (currentMinutes >= pickupCutoffMinutes) {
      return {
        status: 'CLOSING_SOON',
        message: `Pick Up Ditutup (H-1 Jam Sebelum Tutup ${closeTimeStr} WIB)`
      };
    }

    return {
      status: 'OPEN',
      message: 'Toko Buka'
    };
  }

  function refreshStoreModalStates() {
    const storeOptions = document.querySelectorAll('.store-option');
    const isPickup = (typeof currentFulfillment !== 'undefined' && currentFulfillment === 'pickup');

    storeOptions.forEach(option => {
      const openTime = option.getAttribute('data-open-time');
      const closeTime = option.getAttribute('data-close-time');
      const radio = option.querySelector('.store-radio-input');
      const badge = option.querySelector('.modal-store-status-badge');

      const storeStatus = checkStoreOperatingStatus(openTime, closeTime);

      if (badge) {
        badge.classList.remove('hidden', 'bg-emerald-500/10', 'text-emerald-500', 'bg-amber-500/10', 'text-amber-500', 'bg-rose-500/10', 'text-rose-500');
        if (storeStatus.status === 'OPEN') {
          badge.classList.add('bg-emerald-500/10', 'text-emerald-500');
          badge.textContent = 'Buka';
        } else if (storeStatus.status === 'CLOSING_SOON') {
          badge.classList.add('bg-amber-500/10', 'text-amber-500');
          badge.textContent = 'Mendekati Tutup';
        } else {
          badge.classList.add('bg-rose-500/10', 'text-rose-500');
          badge.textContent = 'Tutup';
        }
      }

      if (isPickup && (storeStatus.status === 'CLOSED' || storeStatus.status === 'CLOSING_SOON')) {
        if (radio) radio.disabled = true;
        option.classList.add('opacity-50', 'cursor-not-allowed', 'bg-slate-100', 'dark:bg-slate-800/40');
        option.classList.remove('hover:border-amber-400', 'cursor-pointer');
      } else {
        if (radio) radio.disabled = false;
        option.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-slate-100', 'dark:bg-slate-800/40');
        option.classList.add('cursor-pointer');
      }
    });
  }

  function openStoreModal() {
    refreshStoreModalStates();
    const modal = document.getElementById('store-modal');
    const modalContent = document.getElementById('store-modal-content');
    if (modal && modalContent) {
      modal.classList.remove('opacity-0', 'pointer-events-none');
      modalContent.classList.remove('translate-y-full');
    }
  }

  function closeStoreModal() {
    const modal = document.getElementById('store-modal');
    const modalContent = document.getElementById('store-modal-content');
    if (modal && modalContent) {
      modal.classList.add('opacity-0', 'pointer-events-none');
      modalContent.classList.add('translate-y-full');
    }
  }

  function handleStoreOptionClick(labelEl, id, name, address, hours, openTime, closeTime) {
    const radio = labelEl.querySelector('.store-radio-input');
    if (radio && radio.disabled) return;

    if (typeof highlightStoreOption === 'function') highlightStoreOption(labelEl);
    if (radio) radio.checked = true;
    selectStore(id, name, address, hours, openTime, closeTime);
  }

  function validatePickupConditions() {
    const alertBox = document.getElementById('pickup-validation-alert');
    const alertTitle = document.getElementById('pickup-alert-title');
    const alertDesc = document.getElementById('pickup-alert-desc');

    const openTime = document.getElementById('selected-store-open-time')?.value || '';
    const closeTime = document.getElementById('selected-store-close-time')?.value || '';

    const storeCheck = checkStoreOperatingStatus(openTime, closeTime);
    const badge = document.getElementById('store-status-badge');

    if (badge) {
      badge.className = 'px-2 py-0.5 text-[9px] font-bold rounded';
      if (storeCheck.status === 'OPEN') {
        badge.classList.add('bg-emerald-500/10', 'text-emerald-500');
        badge.textContent = 'Toko Buka';
      } else if (storeCheck.status === 'CLOSING_SOON') {
        badge.classList.add('bg-amber-500/10', 'text-amber-500');
        badge.textContent = 'Mendekati Tutup';
      } else {
        badge.classList.add('bg-rose-500/10', 'text-rose-500');
        badge.textContent = 'Toko Tutup';
      }
    }

    if (typeof currentFulfillment !== 'undefined' && currentFulfillment === 'pickup') {
      if (storeCheck.status === 'CLOSED') {
        if (alertBox) alertBox.classList.remove('hidden');
        if (alertTitle) alertTitle.textContent = 'Toko Sedang Tutup';
        if (alertDesc) alertDesc.textContent = `Toko ini sedang tidak beroperasi${openTime && closeTime ? ` (${openTime} - ${closeTime} WIB)` : ''}. Silakan pilih toko lain atau gunakan metode Delivery.`;

        disableCheckoutButtons();
        return false;
      }

      if (storeCheck.status === 'CLOSING_SOON') {
        if (alertBox) alertBox.classList.remove('hidden');
        if (alertTitle) alertTitle.textContent = 'Batas Waktu Pick Up Berakhir';
        if (alertDesc) alertDesc.textContent = `Pemesanan Pick Up otomatis ditutup 1 jam sebelum toko tutup${closeTime ? ` (${closeTime} WIB)` : ''} untuk persiapan operasional outlet.`;

        disableCheckoutButtons();
        return false;
      }
    }

    if (alertBox) alertBox.classList.add('hidden');
    if (typeof recalculateSummary === 'function') recalculateSummary();
    return true;
  }

  function disableCheckoutButtons() {
    const btnCheckout = document.getElementById('btn-checkout');
    const mobileBtnCheckout = document.querySelector('div.fixed button[onclick="processCheckout()"]');

    if (typeof disableCheckoutBtn === 'function') {
      disableCheckoutBtn(btnCheckout);
      disableCheckoutBtn(mobileBtnCheckout);
    }
  }

  function switchFulfillment(type) {
    currentFulfillment = type;

    const secAddress = document.getElementById('section-address');
    const secCourier = document.getElementById('section-courier');
    const secPickup = document.getElementById('section-store-pickup');

    const lblDelivery = document.getElementById('label-fulfillment-delivery');
    const lblPickup = document.getElementById('label-fulfillment-pickup');

    const iconDelivery = document.getElementById('icon-fulfillment-delivery');
    const titleDelivery = document.getElementById('title-fulfillment-delivery');

    const iconPickup = document.getElementById('icon-fulfillment-pickup');
    const titlePickup = document.getElementById('title-fulfillment-pickup');

    const shippingLabel = document.getElementById('summary-shipping-label');
    const shippingCost = document.getElementById('summary-shipping-cost');

    if (type === 'pickup') {
      if (secAddress) secAddress.classList.add('hidden');
      if (secCourier) secCourier.classList.add('hidden');
      if (secPickup) secPickup.classList.remove('hidden');

      if (lblPickup) {
        lblPickup.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
        lblPickup.classList.remove('border-slate-200', 'dark:border-slate-800');
        const check = lblPickup.querySelector('.check-icon');
        if (check) check.classList.remove('hidden');
      }
      if (iconPickup) iconPickup.className = 'fa-solid fa-store text-amber-500 text-xl transition-colors';
      if (titlePickup) titlePickup.className = 'text-sm font-bold block text-amber-600 dark:text-amber-400 transition-colors';

      if (lblDelivery) {
        lblDelivery.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
        lblDelivery.classList.add('border-slate-200', 'dark:border-slate-800');
        const check = lblDelivery.querySelector('.check-icon');
        if (check) check.classList.add('hidden');
      }
      if (iconDelivery) iconDelivery.className = 'fa-solid fa-truck-ramp-box text-slate-400 text-xl transition-colors';
      if (titleDelivery) titleDelivery.className = 'text-sm font-bold block text-slate-900 dark:text-white transition-colors';

      if (shippingLabel) shippingLabel.innerText = 'Pengambilan Toko (Self Pickup)';
      if (shippingCost) shippingCost.innerText = '0';
    } else {
      if (secAddress) secAddress.classList.remove('hidden');
      if (secCourier) secCourier.classList.remove('hidden');
      if (secPickup) secPickup.classList.add('hidden');

      if (lblDelivery) {
        lblDelivery.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
        lblDelivery.classList.remove('border-slate-200', 'dark:border-slate-800');
        const check = lblDelivery.querySelector('.check-icon');
        if (check) check.classList.remove('hidden');
      }
      if (iconDelivery) iconDelivery.className = 'fa-solid fa-truck-ramp-box text-amber-500 text-xl transition-colors';
      if (titleDelivery) titleDelivery.className = 'text-sm font-bold block text-amber-600 dark:text-amber-400 transition-colors';

      if (lblPickup) {
        lblPickup.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
        lblPickup.classList.add('border-slate-200', 'dark:border-slate-800');
        const check = lblPickup.querySelector('.check-icon');
        if (check) check.classList.add('hidden');
      }
      if (iconPickup) iconPickup.className = 'fa-solid fa-store text-slate-400 text-xl transition-colors';
      if (titlePickup) titlePickup.className = 'text-sm font-bold block text-slate-900 dark:text-white transition-colors';

      if (shippingLabel) shippingLabel.innerText = 'Ongkos Kirim';
      if (shippingCost) shippingCost.innerText = typeof formatRupiah === 'function' ? formatRupiah(currentShippingCost || 0) : (currentShippingCost || 0);
    }

    refreshStoreModalStates();
    validatePickupConditions();
  }

  function fetchNearestStore(lat, lng) {
    if (!lat || !lng) return;

    if (window.isManualStoreSelected) {
      if (typeof currentFulfillment !== 'undefined' && currentFulfillment === 'delivery' && typeof fetchShippingRates === 'function') {
        fetchShippingRates();
      }
      return;
    }

    const autoTag = document.getElementById('store-auto-tag');
    if (autoTag) autoTag.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Mencari toko terdekat...';

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const url = `{{ route('cart.nearest-store') }}?latitude=${lat}&longitude=${lng}&limit=5`;

    fetch(url, {
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': token
        }
      })
      .then(res => res.json())
      .then(res => {
        const stores = res.data || [];
        if (stores.length > 0) {
          const nearest = stores[0];
          const openTime = nearest.open_time ? nearest.open_time.substring(0, 5) : '';
          const closeTime = nearest.close_time ? nearest.close_time.substring(0, 5) : '';
          const hoursText = (openTime && closeTime) ? `${openTime} - ${closeTime}` : 'Jam operasional belum diatur';

          updateStoreUI(nearest.id, nearest.name, nearest.address || nearest.full_address || '-', hoursText, openTime, closeTime);

          if (autoTag) {
            const rawDist = parseFloat(nearest.distance);
            const formattedDist = !isNaN(rawDist) ?
              (rawDist < 1 ? `${Math.round(rawDist * 1000)} m` : `${rawDist.toFixed(1)} km`) :
              '';

            const distText = nearest.distance_text || formattedDist;
            autoTag.textContent = distText ? `Terdekat (${distText})` : 'Terdekat dari Alamat';
          }

          syncItemsStockWithSelectedStore(nearest.id);

          if (typeof currentFulfillment !== 'undefined' && currentFulfillment === 'delivery' && typeof fetchShippingRates === 'function') {
            fetchShippingRates();
          }
        }
      })
      .catch(err => console.error('Error fetching nearest store:', err));
  }

  function selectStore(id, name, address, hours, openTime = '', closeTime = '') {
    window.isManualStoreSelected = true;

    updateStoreUI(id, name, address, hours, openTime, closeTime);

    const autoTag = document.getElementById('store-auto-tag');
    if (autoTag) autoTag.textContent = 'Pilihan Manual';

    syncItemsStockWithSelectedStore(id);

    if (typeof currentFulfillment !== 'undefined' && currentFulfillment === 'delivery' && typeof fetchShippingRates === 'function') {
      fetchShippingRates();
    }

    closeStoreModal();
  }

  function updateStoreUI(id, name, address, hours, openTime = '', closeTime = '') {
    const inputId = document.getElementById('selected-store-id');
    const inputOpen = document.getElementById('selected-store-open-time');
    const inputClose = document.getElementById('selected-store-close-time');

    const nameEl = document.getElementById('selected-store-name');
    const addressEl = document.getElementById('selected-store-address');
    const hoursEl = document.getElementById('selected-store-hours');

    if (inputId) inputId.value = id;
    if (inputOpen) inputOpen.value = openTime;
    if (inputClose) inputClose.value = closeTime;

    if (nameEl) nameEl.textContent = name;
    if (addressEl) addressEl.textContent = address;
    if (hoursEl) hoursEl.innerHTML = '<i class="fa-regular fa-clock me-1"></i> Jam Operasional: ' + hours + ' WIB';

    validatePickupConditions();
  }

  function syncItemsStockWithSelectedStore(storeId) {
    if (!storeId) return;
    const itemRows = document.querySelectorAll('.cart-item-row');
    if (!itemRows.length) return;

    itemRows.forEach(row => {
      const itemId = row.getAttribute('data-item-id');
      const checkbox = row.querySelector('.cart-item-checkbox');
      const warningBadge = document.getElementById(`stock-warning-badge-${itemId}`);
      const qtyEl = document.getElementById(`qty-${itemId}`);
      const qty = parseInt(qtyEl?.innerText || 1);

      let storeStocks = {};
      try {
        const rawData = row.getAttribute('data-store-stocks');
        storeStocks = rawData ? JSON.parse(rawData) : {};
      } catch (e) {
        console.error('Gagal parse store stocks:', e);
      }

      let stockInStore = 0;
      if (Object.prototype.hasOwnProperty.call(storeStocks, storeId)) {
        stockInStore = parseInt(storeStocks[storeId]);
      } else if (Object.prototype.hasOwnProperty.call(storeStocks, String(storeId))) {
        stockInStore = parseInt(storeStocks[String(storeId)]);
      } else if (Object.prototype.hasOwnProperty.call(storeStocks, Number(storeId))) {
        stockInStore = parseInt(storeStocks[Number(storeId)]);
      }

      if (stockInStore <= 0 || stockInStore < qty) {
        if (checkbox) {
          checkbox.checked = false;
          checkbox.disabled = true;
        }
        row.classList.add('opacity-50');
        if (warningBadge) {
          warningBadge.classList.remove('hidden');
          warningBadge.innerText = stockInStore <= 0 ? 'Stok Habis di Toko Ini' : `Stok Kurang (Sisa: ${stockInStore})`;
        }
      } else {
        if (checkbox) {
          checkbox.disabled = false;
          checkbox.checked = true;
        }
        row.classList.remove('opacity-50');
        if (warningBadge) {
          warningBadge.classList.add('hidden');
        }
      }
    });

    validatePickupConditions();

    const masterCheckbox = document.getElementById('select-all-items');
    if (masterCheckbox) {
      const activeCbs = document.querySelectorAll('.cart-item-checkbox:not(:disabled)');
      const checkedCbs = document.querySelectorAll('.cart-item-checkbox:checked:not(:disabled)');
      masterCheckbox.checked = activeCbs.length > 0 && activeCbs.length === checkedCbs.length;
      masterCheckbox.disabled = activeCbs.length === 0;
    }
  }
</script>