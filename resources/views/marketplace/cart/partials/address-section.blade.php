<div id="section-address" class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm transition-all">
  <div class="flex items-center justify-between mb-3">
    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
      <i class="fa-solid fa-location-dot text-amber-500"></i> Alamat Pengiriman
    </h2>
    @if(!empty($addresses))
      <button type="button" onclick="openAddressModal()" class="text-xs text-amber-600 dark:text-amber-400 font-bold hover:underline">Ubah Alamat</button>
    @endif
  </div>
  <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-1">
    @if($primaryAddress)
      <div class="flex items-center gap-2 flex-wrap">
        <span id="display-address-name" class="text-xs font-bold text-slate-900 dark:text-white">{{ data_get($primaryAddress, 'recipient_name') }}</span>
        <span id="display-address-label" class="px-2 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 font-semibold text-[10px] rounded">{{ data_get($primaryAddress, 'label', 'Utama') }}</span>
        <span id="display-address-phone" class="text-xs text-slate-500 dark:text-slate-400">({{ data_get($primaryAddress, 'recipient_phone') }})</span>
      </div>
      <p id="display-address-detail" class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed pt-0.5">
        {{ data_get($primaryAddress, 'full_address') }}
      </p>
    @else
      <div class="text-xs text-rose-500 p-2">
        Belum ada alamat pengiriman tersimpan. Silakan tambahkan alamat terlebih dahulu.
      </div>
    @endif
    <input type="text" id="delivery-note" placeholder="Catatan lokasi/driver (cth: Titip di Satpam)" class="w-full mt-2.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-3 py-2 text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
  </div>
</div>