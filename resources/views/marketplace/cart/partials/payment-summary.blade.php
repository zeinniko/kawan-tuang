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
      <p id="voucher-message" class="text-[11px] mt-1.5 hidden font-medium"></p>
    </div>

    <!-- COST BREAKDOWN -->
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

    <div class="p-3 bg-amber-500/10 rounded-xl border border-amber-500/20 text-[11px] text-amber-700 dark:text-amber-300 flex items-center gap-2">
      <i class="fa-solid fa-shield-halved text-sm flex-shrink-0"></i>
      <span>Pembayaran aman diproses secara terenkripsi oleh Payment Gateway.</span>
    </div>

    <button type="button" id="btn-checkout" onclick="processCheckout()" class="w-full bg-amber-500 hover:bg-amber-600 dark:bg-amber-400 dark:hover:bg-amber-500 text-slate-950 font-extrabold py-3.5 rounded-xl text-center text-sm transition-all shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
      <span>Bayar Sekarang</span> <i class="fa-solid fa-arrow-right"></i>
    </button>

  </div>
</div>