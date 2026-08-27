@extends('welcome')

@section('title', 'Keranjang & Checkout - Kawan Tuang')

@push('styles')
<script
  src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
  data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
@endpush

@section('content')
@php
  $items = data_get($cart, 'items', data_get($cart, 'data.items', []));
  $primaryAddress = collect($addresses)->firstWhere('is_primary', true) ?? ($addresses[0] ?? null);
  $computedSubtotal = collect($items)->sum(function($itm) {
    $p = (float) data_get($itm, 'unit_price', data_get($itm, 'product.price', data_get($itm, 'product.data.price', 0)));
    $q = (int) data_get($itm, 'quantity', 1);
    return $p * $q;
  });
  $rawTotal = (float) data_get($cart, 'total_price', data_get($cart, 'data.total_price', $computedSubtotal));
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-28 lg:pb-12">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- LEFT COLUMN -->
    <div class="lg:col-span-8 space-y-6">
      @include('marketplace.cart.partials.fulfillment-selector')
      @include('marketplace.cart.partials.cart-items')
      @include('marketplace.cart.partials.address-section')
      @include('marketplace.cart.partials.courier-section')
    </div>

    <!-- RIGHT COLUMN -->
    @include('marketplace.cart.partials.payment-summary')

  </div>
</div>

<!-- MODALS -->
@include('marketplace.cart.partials.modals')

<!-- MOBILE STICKY CHECKOUT BAR -->
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
  @include('marketplace.cart.partials.scripts')
@endpush