<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice {{ $order->code }} — Tsania Craft</title>
  <style>
    body { font-family: Arial, sans-serif; background:#fff; color:#111; margin:0; }
    .wrap { max-width: 780px; margin: 24px auto; padding: 0 18px; }
    .card { border: 1px solid #ddd; border-radius: 10px; padding: 18px; }
    h1 { margin:0; font-size: 22px; }
    .muted { color:#555; font-size: 13px; }
    .divider { height:1px; background:#e5e5e5; margin: 14px 0; }
    table { width:100%; border-collapse: collapse; }
    th, td { padding: 10px 0; }
    thead tr { border-bottom: 1px solid #ddd; }
    tbody tr { border-bottom: 1px solid #f0f0f0; }
    .right { text-align:right; }
    .center { text-align:center; }
    .top { display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap; }
    .btn { display:none; }

    @media print {
      @page { margin: 12mm; }
      .wrap { margin:0; max-width: 100%; }
      .card { border: 1px solid #ddd; }
    }
  </style>
</head>
<body onload="window.print()">

  <div class="wrap">
    <div class="card">
      <div class="top">
        <div>
          <h1>Invoice</h1>
          <div class="muted">Kode: <b>{{ $order->code }}</b></div>
        </div>
        <div class="muted">
          Status: <b>{{ strtoupper($order->payment_status) }}</b>
        </div>
      </div>

      <div class="divider"></div>

      <div class="muted" style="line-height:1.7;">
        <div><b>Nama:</b> {{ $order->customer_name }}</div>
        <div><b>WhatsApp:</b> {{ $order->phone }}</div>
        <div><b>Alamat:</b> {{ $order->address }}</div>
        <div><b>Kurir:</b> {{ $order->courier ?? '-' }} {{ $order->service ?? '' }}</div>
      </div>

      <div class="divider"></div>

      <table>
        <thead>
          <tr>
            <th align="left">Produk</th>
            <th class="center">Qty</th>
            <th class="right">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $it)
          <tr>
            <td>
              <b>{{ $it->name_snapshot }}</b><br>
              <span class="muted">Rp{{ number_format($it->price_snapshot,0,',','.') }}</span>
            </td>
            <td class="center">{{ $it->qty }}</td>
            <td class="right">Rp{{ number_format($it->subtotal,0,',','.') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div class="divider"></div>

      <div style="display:flex; justify-content:space-between; margin-top:6px;">
        <span class="muted">Subtotal</span>
        <b>Rp{{ number_format($order->subtotal,0,',','.') }}</b>
      </div>
      <div style="display:flex; justify-content:space-between; margin-top:6px;">
        <span class="muted">Ongkir</span>
        <b>Rp{{ number_format($order->shipping_cost,0,',','.') }}</b>
      </div>
      <div style="display:flex; justify-content:space-between; margin-top:10px; font-weight:900;">
        <span>Total</span>
        <span>Rp{{ number_format($order->total,0,',','.') }}</span>
      </div>
    </div>
  </div>

</body>
</html>
