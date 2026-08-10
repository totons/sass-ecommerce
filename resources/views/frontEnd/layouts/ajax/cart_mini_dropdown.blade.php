@php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
@endphp
@if(Cart::instance('shopping')->count() > 0)
<ul>
    @foreach(Cart::instance('shopping')->content() as $value)
    <li><a href="{{ route('product', $value->options->slug ?? '#') }}"><img src="{{ asset($value->options->image ?? 'public/no-image.png') }}" alt=""></a></li>
    <li><a href="{{ route('product', $value->options->slug ?? '#') }}">{{ Str::limit($value->name, 25) }}</a></li>
    <li>Qty: {{ $value->qty }}</li>
    <li><p>৳{{ $value->price }}</p><button type="button" class="remove-cart cart_remove" data-id="{{ $value->rowId }}">×</button></li>
    @endforeach
</ul>
<p><strong>সর্বমোট : ৳{{ $subtotal }}</strong></p>
<a href="{{ route('customer.checkout') }}" class="go_cart">অর্ডার করুন</a>
@else
<p class="cart-empty-msg">কার্ট খালি</p>
<a href="{{ route('home') }}" class="go_cart">শপ এ যান</a>
@endif
