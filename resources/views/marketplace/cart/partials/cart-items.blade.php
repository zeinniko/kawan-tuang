@php
$items = data_get($cart, 'items', data_get($cart, 'data.items', []));
$allSelected = collect($items)->count() > 0 && collect($items)->every(fn($i) => (bool) data_get($i, 'is_selected', true));
@endphp

<div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
  <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
    <div class="flex items-center gap-3">
      <label class="flex items-center gap-2 cursor-pointer select-none">
        <input type="checkbox" id="select-all-items" {{ $allSelected ? 'checked' : '' }} onchange="toggleSelectAll(this)" class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500 cursor-pointer">
        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Pilih Semua</span>
      </label>
      <span class="text-xs text-slate-400">|</span>
      <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
        <i class="fa-solid fa-bag-shopping text-amber-500"></i> Item Pesanan
      </h2>
    </div>

    <button type="button" onclick="openClearCartModal()" class="text-xs text-rose-500 hover:text-rose-600 hover:underline font-bold transition-colors cursor-pointer">
      Hapus Semua
    </button>
  </div>

  <div class="divide-y divide-slate-100 dark:divide-slate-800/60" id="cart-items-container">
    @forelse($items as $item)
    @php
    $itemId = data_get($item, 'id');
    $product = data_get($item, 'product', data_get($item, 'product.data', []));
    $prodId = data_get($item, 'product_id', data_get($product, 'id'));
    $prodName = data_get($product, 'name', 'Produk');
    $prodImg = data_get($product, 'thumbnail_url',
    data_get($product, 'primary_image.image_url',
    'https://images.unsplash.com/photo-1614316650630-f2030d9980c6?auto=format&fit=crop&w=400&q=80'));
    $unitPrice = (float) data_get($item, 'unit_price', data_get($product, 'price', 0));
    $qty = (int) data_get($item, 'quantity', 1);
    $subtotal = (float) data_get($item, 'subtotal', $unitPrice * $qty);
    $isSelected = (bool) data_get($item, 'is_selected', true);

    // Ambil stok dari berbagai kemungkinan format atribut Eloquent
    $storeStocksRaw = data_get($product, 'store_stocks')
    ?? data_get($product, 'storeStocks')
    ?? data_get($product, 'stocks')
    ?? [];

    $storeStocksData = collect($storeStocksRaw)->pluck('stock', 'store_id')->toArray();

    // PERBAIKAN: Jika store_stocks tidak ada, gunakan stok asli produk (default 0, BUKAN 10)
    if (empty($storeStocksData)) {
    $defaultGeneralStock = (int) data_get($product, 'stock', 0);
    foreach($stores as $st) {
    $stId = data_get($st, 'id');
    $storeStocksData[$stId] = $defaultGeneralStock;
    }
    }
    @endphp

    <div class="py-4 flex gap-3 sm:gap-4 items-start sm:items-center cart-item-row transition-all duration-300"
      id="cart-item-{{ $itemId }}"
      data-item-id="{{ $itemId }}"
      data-product-id="{{ $prodId }}"
      data-store-stocks='@json($storeStocksData)'>

      <input type="checkbox" class="cart-item-checkbox w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500 cursor-pointer mt-2 sm:mt-0 shrink-0"
        value="{{ $itemId }}"
        {{ $isSelected ? 'checked' : '' }}
        onchange="onItemCheckboxChange('{{ $itemId }}', this)">

      <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center flex-shrink-0 overflow-hidden relative">
        <img src="{{ $prodImg }}" alt="{{ $prodName }}" class="w-full h-full object-cover">
      </div>

      <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between gap-2">
          <div class="min-w-0 flex-1 pr-2">
            <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white leading-snug break-words line-clamp-2">
              {{ $prodName }}
            </h3>

            <div class="flex items-center gap-2 mt-0.5">
              <p class="text-[11px] text-slate-500 dark:text-slate-400">
                Rp <span class="unit-price">{{ number_format($unitPrice, 0, ',', '.') }}</span> / botol
              </p>
              <span id="stock-warning-badge-{{ $itemId }}" class="hidden text-[10px] font-bold text-rose-500 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded-full">
                Stok Habis di Toko Ini
              </span>
            </div>
          </div>

          <button type="button" onclick="removeItem('{{ $itemId }}')" class="text-slate-400 hover:text-rose-500 text-xs p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors shrink-0 cursor-pointer" title="Hapus Item">
            <i class="fa-solid fa-trash-can"></i>
          </button>
        </div>

        <div class="flex items-center justify-between mt-3 pt-1">
          <span class="text-xs sm:text-sm font-extrabold text-amber-600 dark:text-amber-400">
            Rp <span class="item-subtotal" id="subtotal-{{ $itemId }}" data-raw="{{ $subtotal }}">{{ number_format($subtotal, 0, ',', '.') }}</span>
          </span>

          <div class="flex items-center bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shrink-0">
            <button type="button" id="btn-minus-{{ $itemId }}" onclick="updateQty('{{ $itemId }}', -1)" class="w-7 h-7 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-l-lg transition-colors cursor-pointer">-</button>

            <span class="w-8 text-center text-xs font-bold text-slate-900 dark:text-white" id="qty-{{ $itemId }}" data-unit-price="{{ $unitPrice }}" data-max-stock="99">{{ $qty }}</span>

            <button type="button" id="btn-plus-{{ $itemId }}" onclick="updateQty('{{ $itemId }}', 1)" class="w-7 h-7 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-r-lg transition-colors cursor-pointer">+</button>
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

<script>
  async function toggleSelectAll(masterCheckbox) {
    const isSelected = masterCheckbox.checked;

    document.querySelectorAll('.cart-item-checkbox:not(:disabled)').forEach(cb => {
      cb.checked = isSelected;
    });

    recalculateSummary();

    try {
      await fetch("{{ route('cart.toggle-select-all') }}", {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          is_selected: isSelected
        })
      });

      if (currentFulfillment === 'delivery') {
        fetchShippingRates();
      }
    } catch (err) {
      console.error('Error toggling select all:', err);
    }
  }

  function openClearCartModal() {
    const cartContainer = document.getElementById('cart-items-container');
    if (!cartContainer || cartContainer.querySelectorAll('.cart-item-row').length === 0) return;
    const modal = document.getElementById('clear-cart-modal');
    const content = document.getElementById('clear-cart-modal-content');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    content.classList.remove('scale-95');
    content.classList.add('scale-100');
  }
  async function onItemCheckboxChange(itemId, checkboxEl) {
    const isSelected = checkboxEl.checked;
    const allCheckboxes = document.querySelectorAll('.cart-item-checkbox:not(:disabled)');
    const checkedCheckboxes = document.querySelectorAll('.cart-item-checkbox:checked:not(:disabled)');
    const masterCheckbox = document.getElementById('select-all-items');

    if (masterCheckbox) {
      masterCheckbox.checked = allCheckboxes.length > 0 && allCheckboxes.length === checkedCheckboxes.length;
    }

    recalculateSummary();

    const toggleUrl = "{{ route('cart.toggle-select', ':id') }}".replace(':id', itemId);
    try {
      await fetch(toggleUrl, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          is_selected: isSelected
        })
      });

      if (currentFulfillment === 'delivery') {
        fetchShippingRates();
      }
    } catch (err) {
      console.error('Error toggling item select:', err);
    }
  }

  function removeItem(itemId) {
    const itemRow = document.getElementById(`cart-item-${itemId}`);
    if (itemRow) itemRow.remove();

    checkEmptyState();
    recalculateSummary();
    if (activeVoucherCode) applyVoucher(true);

    const deleteUrl = "{{ route('cart.destroy', ':id') }}".replace(':id', itemId);
    fetch(deleteUrl, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    }).catch(err => console.error('Error deleting item:', err));
  }
  async function updateQty(itemId, delta) {
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

    // Re-check stok item terhadap toko yang aktif setelah QTY berubah
    const currentStoreId = document.getElementById('selected-store-id')?.value;
    if (currentStoreId && typeof syncItemsStockWithSelectedStore === 'function') {
      syncItemsStockWithSelectedStore(currentStoreId);
    } else {
      recalculateSummary();
    }

    const updateUrl = "{{ route('cart.update', ':id') }}".replace(':id', itemId);
    try {
      const response = await fetch(updateUrl, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          quantity: newQty
        })
      });

      if (response.ok && activeVoucherCode) {
        applyVoucher(true);
      }
    } catch (err) {
      console.error('Error updating cart quantity on server:', err);
    }
  }
</script>