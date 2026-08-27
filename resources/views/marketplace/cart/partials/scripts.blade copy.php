<script>
    let selectedCourierCompany = '';
    let selectedCourierType = '';
    let currentFulfillment = 'delivery';
    let currentShippingCost = 25000;
    let currentDiscount = 0;
    let activeVoucherCode = '';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.addEventListener('DOMContentLoaded', () => {
        recalculateSummary();
    });

    // ==========================================
    // 0. CHECKBOX ITEM & PERSISTENSI IS_SELECTED
    // ==========================================
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
                body: JSON.stringify({ is_selected: isSelected })
            });

            if (currentFulfillment === 'delivery') {
                fetchShippingRates();
            }
        } catch (err) {
            console.error('Error toggling select all:', err);
        }
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
                body: JSON.stringify({ is_selected: isSelected })
            });

            if (currentFulfillment === 'delivery') {
                fetchShippingRates();
            }
        } catch (err) {
            console.error('Error toggling item select:', err);
        }
    }

    function disableCheckoutBtn(btn) {
        if (!btn) return;
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
    }

    function enableCheckoutBtn(btn) {
        if (!btn) return;
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
    }

    // ==========================================
    // 1. FETCH RATES ONGKIR (Biteship API)
    // ==========================================
    function fetchShippingRates() {
        const container = document.getElementById('courier-options-container');
        if (!container) return;

        const selectedAddressRadio = document.querySelector('input[name="selected_address_id"]:checked');
        const addressId = selectedAddressRadio ? selectedAddressRadio.value : "{{ $primaryAddress['id'] ?? '' }}";
        const storeId = document.getElementById('selected-store-id')?.value || "{{ $stores[0]['id'] ?? '' }}";

        if (!addressId || !storeId) {
            container.innerHTML = `
            <div class="col-span-full p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs text-center font-medium">
                Pilih alamat pengiriman dan outlet toko terlebih dahulu untuk menghitung ongkir.
            </div>`;
            return;
        }

        const items = [];
        document.querySelectorAll('.cart-item-checkbox:checked:not(:disabled)').forEach(cb => {
            const itemId = cb.value;
            const row = document.getElementById(`cart-item-${itemId}`);
            if (row) {
                const productId = row.getAttribute('data-product-id');
                const qty = parseInt(document.getElementById(`qty-${itemId}`)?.innerText || 0);
                if (productId && qty > 0) {
                    items.push({
                        product_id: productId,
                        quantity: qty
                    });
                }
            }
        });

        if (items.length === 0) {
            container.innerHTML = `
            <div class="col-span-full p-4 text-center text-xs text-slate-400">
                Pilih minimal satu item produk yang tersedia untuk melihat opsi pengiriman.
            </div>`;
            currentShippingCost = 0;
            recalculateSummary();
            return;
        }

        container.innerHTML = `
        <div class="col-span-full py-8 text-center text-xs text-slate-400 flex items-center justify-center gap-2">
            <i class="fa-solid fa-circle-notch fa-spin text-amber-500 text-sm"></i>
            <span>Menghitung tarif ongkos kirim real-time...</span>
        </div>`;

        fetch("{{ route('cart.shipping-rates') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                store_id: storeId,
                user_address_id: addressId,
                items: items
            })
        })
        .then(res => res.json().then(data => ({ status: res.status, ok: res.ok, body: data })))
        .then(res => {
            if (!res.ok || res.body.error || (res.body.message && !res.body.data)) {
                const errorDetail = res.body.error || res.body.message || 'Gagal menghitung tarif pengiriman.';
                container.innerHTML = `
                <div class="col-span-full p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs text-center font-medium space-y-1">
                    <p class="font-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> ${errorDetail}</p>
                    <p class="text-[11px] opacity-80">Silakan hilangkan centang produk ini atau ganti outlet store pengirim.</p>
                </div>`;
                currentShippingCost = 0;
                recalculateSummary();
                return;
            }

            let rates = res.body.data || [];
            if (rates.length === 0) {
                container.innerHTML = `
                <div class="col-span-full p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs text-center font-medium">
                    Layanan pengiriman tidak tersedia untuk jarak lokasi ini.
                </div>`;
                currentShippingCost = 0;
                recalculateSummary();
                return;
            }

            rates.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));

            let html = '';
            rates.forEach((rate, index) => {
                const isFirst = index === 0;
                if (isFirst) {
                    currentShippingCost = parseFloat(rate.price);
                    selectedCourierCompany = rate.courier_code;
                    selectedCourierType = rate.courier_service_code;
                }

                const durationText = rate.estimated_days ? `Estimasi ${rate.estimated_days} ${rate.shipment_duration_unit}` : 'Pengiriman Instan/Reguler';

                html += `
                <label class="relative flex flex-col justify-between p-3.5 rounded-xl border ${isFirst ? 'border-2 border-amber-500 bg-amber-500/5' : 'border-slate-200 dark:border-slate-800 hover:border-amber-400'} cursor-pointer courier-option transition-all" 
                       onclick="selectCourier(${rate.price}, '${rate.courier_code}', '${rate.courier_service_code}', '${rate.courier_name} ${rate.service_name}', event)">
                  <input type="radio" name="shipping" value="${rate.price}" ${isFirst ? 'checked' : ''} class="hidden">
                  <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-900 dark:text-white">${rate.courier_name} (${rate.service_name})</span>
                    <i class="fa-solid fa-circle-check text-amber-500 text-sm check-icon ${isFirst ? '' : 'hidden'}"></i>
                  </div>
                  <div class="mt-2">
                    <span class="text-xs font-extrabold ${isFirst ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white'} block courier-price">Rp ${formatRupiah(rate.price)}</span>
                    <span class="text-[10px] text-slate-500">${durationText}</span>
                  </div>
                </label>`;
            });

            container.innerHTML = html;

            if (rates[0]) {
                document.getElementById('summary-shipping-label').innerText = `Ongkos Kirim (${rates[0].courier_name} ${rates[0].service_name})`;
                document.getElementById('summary-shipping-cost').innerText = formatRupiah(rates[0].price);
            }
            recalculateSummary();
        })
        .catch(err => {
            console.error('Error fetching shipping rates:', err);
            container.innerHTML = `
            <div class="col-span-full p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs text-center font-medium">
                Gagal mengambil tarif pengiriman. Silakan coba klik 'Hitung Ulang'.
            </div>`;
        });
    }

    // ==========================================
    // 2. SWITCH FULFILLMENT (Delivery / Pickup)
    // ==========================================
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

        recalculateSummary();
    }

    function openStoreModal() {
        const modal = document.getElementById('store-modal');
        const content = document.getElementById('store-modal-content');
        modal.classList.remove('pointer-events-none');
        modal.classList.add('opacity-100');
        content.classList.remove('translate-y-full');
    }

    function closeStoreModal() {
        const modal = document.getElementById('store-modal');
        const content = document.getElementById('store-modal-content');
        content.classList.add('translate-y-full');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('pointer-events-none');
        }, 300);
    }

    function highlightStoreOption(element) {
        document.querySelectorAll('.store-option').forEach(opt => {
            opt.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
            opt.classList.add('border-slate-200', 'dark:border-slate-800');
        });
        element.classList.remove('border-slate-200', 'dark:border-slate-800');
        element.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
    }

    // ==========================================
    // 3. MODALS MANAGEMENT (Address)
    // ==========================================
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

        if (currentFulfillment === 'delivery') {
            fetchShippingRates();
        }
    }

    function highlightAddressOption(selectedEl) {
        document.querySelectorAll('.address-option').forEach(el => {
            el.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
            el.classList.add('border', 'border-slate-200', 'dark:border-slate-800');
        });
        selectedEl.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
        selectedEl.classList.remove('border-slate-200', 'dark:border-slate-800');
    }

    function selectAddress(name, phone, detail, label, lat = null, lng = null) {
        document.getElementById('display-address-name').innerText = name;
        document.getElementById('display-address-phone').innerText = phone;
        document.getElementById('display-address-detail').innerText = detail;
        const labelEl = document.getElementById('display-address-label');
        if (labelEl) labelEl.innerText = label;

        if (lat && lng && typeof fetchNearestStore === 'function') {
            fetchNearestStore(lat, lng);
        } else if (currentFulfillment === 'delivery') {
            fetchShippingRates();
        }
    }

    // ==========================================
    // 4. SELECT COURIER
    // ==========================================
    function selectCourier(cost, courierCode, serviceCode, displayName, evt) {
        currentShippingCost = parseFloat(cost);
        selectedCourierCompany = courierCode;
        selectedCourierType = serviceCode;

        document.getElementById('summary-shipping-label').innerText = `Ongkos Kirim (${displayName})`;
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

    // ==========================================
    // 5. CLEAR CART ACTIONS
    // ==========================================
    function openClearCartModal() {
        const cartContainer = document.getElementById('cart-items-container');
        if (!cartContainer || cartContainer.querySelectorAll('.cart-item-row').length === 0) return;
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
        </div>`;
        removeVoucher();
        recalculateSummary();

        fetch("{{ route('cart.clear') }}", {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).catch(err => console.error('Error clearing cart:', err));
    }

    // ==========================================
    // 6. QTY & ITEM MANAGEMENT
    // ==========================================
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
                body: JSON.stringify({ quantity: newQty })
            });

            if (response.ok && activeVoucherCode) {
                applyVoucher(true);
            }
        } catch (err) {
            console.error('Error updating cart quantity on server:', err);
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

    function checkEmptyState() {
        const cartContainer = document.getElementById('cart-items-container');
        if (cartContainer && cartContainer.querySelectorAll('.cart-item-row').length === 0) {
            cartContainer.innerHTML = `
            <div class="py-12 text-center text-slate-400 text-xs" id="empty-cart-msg">
                Keranjang Anda masih kosong. <a href="{{ route('catalog.index') }}" class="text-amber-500 underline font-bold">Mulai belanja</a>
            </div>`;
            removeVoucher();
        }
    }

    // ==========================================
    // 7. VOUCHER ACTIONS
    // ==========================================
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

    // ==========================================
    // 8. CALCULATIONS & HELPERS (FILTER BY CHECKLIST)
    // ==========================================
    function recalculateSummary() {
        let subtotalSum = 0;
        const checkedCheckboxes = document.querySelectorAll('.cart-item-checkbox:checked:not(:disabled)');
        const hasSelectedItems = checkedCheckboxes.length > 0;

        checkedCheckboxes.forEach(cb => {
            const itemId = cb.value;
            const subtotalEl = document.getElementById(`subtotal-${itemId}`);
            if (subtotalEl) {
                subtotalSum += parseFloat(subtotalEl.getAttribute('data-raw') || 0);
            }
        });

        const effectiveShipping = (!hasSelectedItems || currentFulfillment === 'pickup') ? 0 : currentShippingCost;
        const effectiveDiscount = hasSelectedItems ? currentDiscount : 0;
        const grandTotal = Math.max(0, subtotalSum + effectiveShipping - effectiveDiscount);

        document.getElementById('summary-subtotal').innerText = formatRupiah(subtotalSum);
        document.getElementById('summary-discount').innerText = formatRupiah(effectiveDiscount);
        document.getElementById('summary-grand-total').innerText = formatRupiah(grandTotal);

        const mobileGrandTotalEl = document.getElementById('mobile-grand-total');
        if (mobileGrandTotalEl) {
            mobileGrandTotalEl.innerText = formatRupiah(grandTotal);
        }

        const btnCheckout = document.getElementById('btn-checkout');
        const mobileBtnCheckout = document.querySelector('div.fixed button[onclick="processCheckout()"]');

        if (!hasSelectedItems) {
            disableCheckoutBtn(btnCheckout);
            disableCheckoutBtn(mobileBtnCheckout);
        } else {
            enableCheckoutBtn(btnCheckout);
            enableCheckoutBtn(mobileBtnCheckout);
        }
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function selectStoreOption(selectedLabel) {
        document.querySelectorAll('.store-option-item').forEach(el => {
            el.classList.remove('border-2', 'border-amber-500', 'bg-amber-500/5');
            el.classList.add('border-slate-200', 'dark:border-slate-800', 'hover:border-amber-400');

            const radioInput = el.querySelector('input[type="radio"]');
            if (radioInput) radioInput.checked = false;
        });

        selectedLabel.classList.add('border-2', 'border-amber-500', 'bg-amber-500/5');
        selectedLabel.classList.remove('border-slate-200', 'dark:border-slate-800', 'hover:border-amber-400');

        const activeRadio = selectedLabel.querySelector('input[type="radio"]');
        if (activeRadio) {
            activeRadio.checked = true;
        }
    }

    // ==========================================
    // 9. CHECKOUT EXECUTION
    // ==========================================
    function processCheckout() {
        const selectedCheckboxes = document.querySelectorAll('.cart-item-checkbox:checked:not(:disabled)');
        if (selectedCheckboxes.length === 0) {
            alert("Silakan pilih minimal satu produk yang tersedia untuk diproses.");
            return;
        }

        const btn = document.getElementById('btn-checkout');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...';

        const finalStoreId = document.getElementById('selected-store-id')?.value;
        const selectedAddressRadio = document.querySelector('input[name="selected_address_id"]:checked');
        const defaultAddressId = selectedAddressRadio ? selectedAddressRadio.value : "{{ $primaryAddress['id'] ?? '' }}";

        if (currentFulfillment === 'delivery' && !defaultAddressId) {
            alert("Harap pilih atau tambahkan alamat pengiriman terlebih dahulu.");
            btn.disabled = false;
            btn.innerHTML = 'Bayar Sekarang <i class="fa-solid fa-arrow-right"></i>';
            return;
        }

        const payload = {
            fulfillment_type: currentFulfillment,
            shipping_cost: currentFulfillment === 'pickup' ? 0 : currentShippingCost,
            store_id: finalStoreId,
            user_address_id: defaultAddressId,
            courier_company: selectedCourierCompany || 'gojek',
            courier_type: selectedCourierType || 'instant',
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
            const snapToken = data.snap_token || data.data?.snap_token;

            if (snapToken) {
                if (typeof window.snap === 'undefined') {
                    alert("SDK Midtrans Snap belum dimuat dengan sempurna. Harap refresh halaman.");
                    return;
                }
                window.snap.pay(snapToken, {
                    onSuccess: function(result) {
                        window.location.href = "/orders/" + (data.order_id || data.order?.id);
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
            console.error('Checkout error:', err);
            alert("Terjadi kesalahan koneksi saat memproses pesanan.");
        });
    }
</script>