@extends('layouts.app')
@section('title','Checkout — Tsania Craft')

@section('content')

<section class="page-hero">
  <div style="text-align:center;">
    <h1 class="h1">Checkout</h1>
    <p class="p-muted" style="margin-top:8px;">
      Lengkapi pengiriman, lalu buat pesanan COD dan cetak struk.
    </p>
  </div>
</section>

@php $codWhitelistSafe = $codWhitelist ?? []; @endphp
<script>
  window.__CHECKOUT_CONFIG__ = {
    subtotal: {{ (int)($subtotal ?? 0) }},
    weight: {{ (int)($weight ?? 1000) }},
    codWhitelist: @json($codWhitelistSafe),
    shippingCheckUrl: @json(route('shipping.check')),
    csrf: @json(csrf_token()),
  };
</script>

<section class="section" x-data="checkoutCod()" x-init="init()">

@if(empty($items) || count($items) === 0)
  <div class="card" style="padding:20px; text-align:center;">
    <p class="p-muted">Keranjang masih kosong.</p>
    <a href="{{ route('products.index') }}" class="btn btn-dark" style="margin-top:12px;">Belanja Sekarang</a>
  </div>
@else

<div style="display:grid; grid-template-columns:1fr; gap:18px; align-items:start;">

  <form method="POST" action="{{ route('checkout.store') }}" class="card" style="padding:18px;">
    @csrf

    <div class="h2">Data Pembeli</div>
    <div class="divider" style="margin:12px 0;"></div>

    <div style="display:grid; gap:12px;">
      <div class="field-wrap">
        <label style="font-size:13px; color:var(--muted); font-weight:700;">Nama</label>
        <input name="customer_name" required class="field" value="{{ old('customer_name') }}">
      </div>

      <div class="field-wrap">
        <label style="font-size:13px; color:var(--muted); font-weight:700;">No. WhatsApp</label>
        <input name="phone" required class="field" value="{{ old('phone') }}">
      </div>

      <div class="field-wrap">
        <label style="font-size:13px; color:var(--muted); font-weight:700;">Alamat</label>
        <textarea name="address" required rows="3" class="field">{{ old('address') }}</textarea>
      </div>
    </div>

    <div class="divider" style="margin:14px 0;"></div>

    <div class="h2">Pengiriman</div>
    <div style="display:grid; gap:12px; margin-top:12px;">

      <div class="field-wrap">
        <label style="font-size:13px; color:var(--muted); font-weight:700;">City ID Tujuan</label>
        <input name="destination_city_id" class="field"
               x-model="destinationCityId"
               @input.debounce.600ms="checkOngkir()"
               placeholder="contoh: 23 (Bandung)" required>
      </div>

      <div class="field-wrap">
        <label style="font-size:13px; color:var(--muted); font-weight:700;">Kurir</label>
        <select name="courier" class="field" x-model="courier" @change="checkOngkir()">
          <option value="jne">JNE</option>
          <option value="pos">POS</option>
          <option value="tiki">TIKI</option>
        </select>
      </div>

      <div class="field-wrap">
        <label style="font-size:13px; color:var(--muted); font-weight:700;">Layanan</label>
        <select name="service" class="field" x-model="selectedService" @change="onServiceChange()" required>
          <option value="pickup">Ambil di Tempat (Ongkir Rp0)</option>
          <option value="" disabled>──── Layanan Kurir ────</option>
          <template x-for="s in services" :key="s.service">
            <option :value="s.service" x-text="`${s.service} • Rp${s.cost.toLocaleString('id-ID')} • ETD ${s.etd}`"></option>
          </template>
        </select>
      </div>

      <input type="hidden" name="shipping_cost" :value="shippingCost">

    </div>

    <div class="divider" style="margin:14px 0;"></div>

    <div class="h2">Pembayaran</div>
    <div class="card-soft" style="padding:14px; margin-top:10px;">
      <label style="display:flex; align-items:center; gap:10px;">
        <input type="radio" name="payment_method" value="cod" checked>
        <span><b>COD</b> (Bayar saat barang diterima)</span>
      </label>
      <div class="p-muted" style="margin-top:8px; font-size:13px;">
        COD hanya aktif untuk wilayah tertentu (sesuai whitelist).
      </div>
    </div>

    <button class="btn btn-dark" style="width:100%; margin-top:16px;" :disabled="!canSubmit"
            :style="!canSubmit ? 'opacity:.55; cursor:not-allowed;' : ''">
      Buat Pesanan COD
    </button>
  </form>

  <aside class="card" style="padding:18px;">
    <div class="h2">Ringkasan</div>
    <div class="divider" style="margin:12px 0;"></div>

    @foreach($items as $it)
      <div style="display:flex; justify-content:space-between; gap:12px;">
        <div>
          <div style="font-weight:700;">{{ $it['name'] }}</div>
          <div class="p-muted" style="font-size:13px;">Qty: {{ $it['qty'] }}</div>
        </div>
        <div>Rp{{ number_format($it['price']*$it['qty'],0,',','.') }}</div>
      </div>
      <div class="divider" style="margin:10px 0;"></div>
    @endforeach

    <div style="display:flex; justify-content:space-between;">
      <span class="p-muted">Subtotal</span>
      <span style="font-weight:900;">Rp{{ number_format($subtotal,0,',','.') }}</span>
    </div>
    <div style="display:flex; justify-content:space-between; margin-top:8px;">
      <span class="p-muted">Ongkir</span>
      <span style="font-weight:900;" x-text="`Rp${shippingCost.toLocaleString('id-ID')}`"></span>
    </div>
    <div style="display:flex; justify-content:space-between; margin-top:10px; font-weight:900;">
      <span>Total</span>
      <span x-text="`Rp${total.toLocaleString('id-ID')}`"></span>
    </div>
  </aside>

</div>

<style>
@media(min-width:1024px){
  section.section > div[style*="grid-template-columns:1fr"]{
    grid-template-columns: 1.15fr .85fr !important;
  }
}
</style>

@endif
</section>

<script>
function checkoutCod() {
  const cfg = window.__CHECKOUT_CONFIG__ || {};
  return {
    subtotal: cfg.subtotal || 0,
    weight: cfg.weight || 1000,
    codWhitelist: cfg.codWhitelist || [],
    shippingCheckUrl: cfg.shippingCheckUrl || '',
    csrf: cfg.csrf || '',

    services: [],
    selectedService: 'pickup',
    shippingCost: 0,

    courier: 'jne',
    destinationCityId: '',

    init(){},

    onServiceChange(){
      if(this.selectedService === 'pickup'){
        this.shippingCost = 0;
        return;
      }
      const s = this.services.find(x => x.service === this.selectedService);
      this.shippingCost = s ? s.cost : 0;
    },

    async checkOngkir(){
      this.services = [];
      if (!this.destinationCityId || !this.shippingCheckUrl) return;

      try{
        const res = await fetch(this.shippingCheckUrl, {
          method:'POST',
          headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': this.csrf,
            'Accept':'application/json'
          },
          body: JSON.stringify({
            destination_city_id: Number(this.destinationCityId),
            courier: this.courier,
            weight: this.weight
          })
        });

        const data = await res.json();
        if(data.ok && data.services && data.services.length > 0){
          this.services = data.services;

          // auto select layanan pertama (opsional)
          if(this.selectedService !== 'pickup'){
            this.selectedService = data.services[0].service;
            this.shippingCost = data.services[0].cost;
          }
        }
      }catch(e){
        // fallback pickup
      }
    },

    get total(){
      return this.subtotal + this.shippingCost;
    },

    get canSubmit(){
      // pickup selalu bisa
      if(this.selectedService === 'pickup') return true;
      // jika bukan pickup, ongkir harus >0 dan service terpilih
      return this.shippingCost > 0 && !!this.selectedService;
    }
  }
}
</script>

@endsection
