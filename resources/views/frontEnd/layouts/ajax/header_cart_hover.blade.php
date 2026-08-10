@php
    $cartContent = $cartContent ?? collect();
    $subtotal = isset($subtotal) ? (float) $subtotal : (float) str_replace(',', '', Cart::instance('shopping')->subtotal());
@endphp
@if($cartContent->isEmpty())
    <div class="header-cart-hover-empty">
        <p class="mb-0">কার্ট খালি</p>
        <a href="{{ route('shop') }}" class="header-cart-hover-checkout">শপিং করুন</a>
    </div>
@else
    <div class="header-cart-hover-items">
        @foreach($cartContent as $item)
            <div class="header-cart-hover-row">
                <a href="{{ route('product', $item->options->slug ?? '#') }}" class="header-cart-hover-thumb">
                    <img src="{{ asset($item->options->image ?? 'public/uploads/default.webp') }}" alt="">
                </a>
                <div class="header-cart-hover-info">
                    <a href="{{ route('product', $item->options->slug ?? '#') }}" class="header-cart-hover-name">{{ Str::limit($item->name, 42) }}</a>
                    <div class="header-cart-hover-qty">পরিমাণ: {{ $item->qty }}</div>
                    <div class="header-cart-hover-price-row">
                        <span class="header-cart-hover-price">৳ {{ number_format((float) $item->price, 0) }}</span>
                        <button type="button" class="header-cart-hover-remove cart_remove" data-id="{{ $item->rowId }}" title="সরান">×</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="header-cart-hover-footer">
        <p class="header-cart-hover-total-line">সর্বমোট: <strong>৳ {{ number_format($subtotal, 0) }}</strong></p>
        <a href="{{ route('customer.checkout') }}" class="header-cart-hover-checkout">অর্ডার করুন</a>
    </div>
@endif
