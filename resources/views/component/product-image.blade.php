@props(['path' => null, 'alt' => 'Product'])

@if($path)
  <img class="pimg" src="{{ asset('storage/'.$path) }}" alt="{{ $alt }}" loading="lazy" decoding="async">
@else
  <div class="pimg"></div>
@endif
<x-product-image :path="$p->main_image" :alt="$p->name" />
