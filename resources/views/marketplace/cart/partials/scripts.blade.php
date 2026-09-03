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
            .then(res => res.json().then(data => ({
                status: res.status,
                ok: res.ok,
                body: data
            })))
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

        if (window.isManualStoreSelected) {
            if (currentFulfillment === 'delivery' && typeof fetchShippingRates === 'function') {
                fetchShippingRates();
            }
        } else if (lat && lng && typeof fetchNearestStore === 'function') {
            fetchNearestStore(lat, lng);
        } else if (currentFulfillment === 'delivery' && typeof fetchShippingRates === 'function') {
            fetchShippingRates();
        }
    }

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
                body: JSON.stringify({
                    code: code
                })
            })
            .then(res => res.json().then(data => ({
                status: res.status,
                body: data
            })))
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

        // --- PENYESUAIAN: LOGIKA HITUNG POTONGAN POIN TIPSY ---
        let effectivePointDiscount = 0;
        const usePointsCheckbox = document.getElementById('use-points-checkbox');
        const availablePointsInput = document.getElementById('available-user-points');
        const availablePoints = availablePointsInput ? parseInt(availablePointsInput.value || 0, 10) : 0;

        if (usePointsCheckbox && usePointsCheckbox.checked && hasSelectedItems) {
            // Hitung sisa tagihan sebelum dikurangi poin (Subtotal + Ongkir - Diskon Voucher)
            const billBeforePoints = Math.max(0, subtotalSum + effectiveShipping - effectiveDiscount);
            effectivePointDiscount = Math.min(availablePoints, billBeforePoints);
        }

        // Tampilkan / sembunyikan baris potongan poin di ringkasan
        const pointRow = document.getElementById('summary-point-row');
        const pointDiscountEl = document.getElementById('summary-point-discount');
        if (pointRow && pointDiscountEl) {
            if (effectivePointDiscount > 0) {
                pointRow.classList.remove('hidden');
                pointDiscountEl.innerText = formatRupiah(effectivePointDiscount);
            } else {
                pointRow.classList.add('hidden');
                pointDiscountEl.innerText = '0';
            }
        }

        // Grand Total = (Subtotal + Ongkir - Voucher - Diskon Poin)
        const grandTotal = Math.max(0, subtotalSum + effectiveShipping - effectiveDiscount - effectivePointDiscount);

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

        // --- PENYESUAIAN: BACA STATUS USE_POINTS ---
        const usePointsCheckbox = document.getElementById('use-points-checkbox');
        const isUsingPoints = usePointsCheckbox ? usePointsCheckbox.checked : false;

        const payload = {
            fulfillment_type: currentFulfillment,
            shipping_cost: currentFulfillment === 'pickup' ? 0 : currentShippingCost,
            store_id: finalStoreId,
            user_address_id: defaultAddressId,
            courier_company: selectedCourierCompany,
            courier_type: selectedCourierType,
            payment_method: 'midtrans',
            voucher_code: activeVoucherCode || null,
            use_points: isUsingPoints,
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