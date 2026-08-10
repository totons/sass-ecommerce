<div class="flash-product-card">
    <div class="flash-product-inner">
        <a href="{{ route('product', $value->slug) }}" class="flash-product-img-wrap">
            @if($value->old_price)
                @php
                    $discount = ((($value->old_price - $value->new_price) * 100) / $value->old_price);
                @endphp
                <span class="flash-sale-badge-circle" aria-hidden="true">
                    <span class="flash-sale-pct">{{ number_format($discount, 0) }}%</span>
                    <span class="flash-sale-txt">ছাড়</span>
                </span>
            @endif
            <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{ $value->name }}" loading="lazy">
            @if(!is_null($value->stock) && $value->stock < 1)
                <span class="flash-stock-out">STOCK OUT</span>
            @endif
        </a>
        <div class="flash-product-body">
            <a href="{{ route('product', $value->slug) }}" class="flash-product-name">{{ Str::limit($value->name, 70) }}</a>
            <span class="flash-sold">Sold {{ $value->sold ?? 0 }}</span>
            <div class="flash-product-price">
                @if($value->old_price)
                    <del class="flash-price-old">৳ {{ $value->old_price }}</del>
                @endif
                <strong class="flash-price-new">৳ {{ $value->new_price }}</strong>
            </div>
        </div>
        @if(!$value->prosizes->isEmpty() || !$value->procolors->isEmpty() || (!is_null($value->stock) && $value->stock < 1))
            <div class="flash-product-actions">
                <a href="{{ route('product', $value->slug) }}" class="flash-btn-order">অর্ডার করুন</a>
                <a href="{{ route('product', $value->slug) }}" class="flash-btn-cart" title="কার্টে যোগ করুন" aria-label="কার্ট">
                    <svg class="flash-cart-ico" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="#ffffff" d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                </a>
            </div>
        @else
            <div class="flash-product-actions">
                <form action="{{ route('cart.store') }}" method="POST" class="flash-form-order">
                    @csrf
                    <input type="hidden" name="id" value="{{ $value->id }}">
                    <input type="hidden" name="qty" value="1">
                    <input type="hidden" name="order_now" value="1">
                    <button type="submit" class="flash-btn-order">অর্ডার করুন</button>
                </form>
                <form action="{{ route('cart.store') }}" method="POST" class="flash-form-cart">
                    @csrf
                    <input type="hidden" name="id" value="{{ $value->id }}">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="flash-btn-cart" title="কার্টে যোগ করুন" aria-label="কার্ট">
                        <svg class="flash-cart-ico" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="#ffffff" d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
