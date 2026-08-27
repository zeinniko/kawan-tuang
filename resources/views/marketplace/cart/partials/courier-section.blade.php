<div id="section-courier" class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm transition-all">
  <div class="flex items-center justify-between mb-3">
    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
      <i class="fa-solid fa-truck-fast text-amber-500"></i> Opsi Pengiriman
    </h2>
    <button type="button" onclick="fetchShippingRates()" class="text-xs text-amber-600 dark:text-amber-400 font-semibold hover:underline flex items-center gap-1">
      <i class="fa-solid fa-rotate-right text-[10px]"></i> Hitung Ulang
    </button>
  </div>

  <div id="courier-options-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
    <div class="col-span-full py-8 text-center text-xs text-slate-400 flex items-center justify-center gap-2">
      <i class="fa-solid fa-circle-notch fa-spin text-amber-500 text-sm"></i>
      <span>Menghitung tarif ongkos kirim real-time...</span>
    </div>
  </div>
</div>