@php $keyword = trim($keyword ?? request()->get('keyword', '')); $products = $products ?? collect(); @endphp
@if(!empty($keyword))
    @if(count($products) > 0)
    <div class="search_product">
        <ul>
            @foreach($products as $value)
            <li>
                <a href="{{ route('product', $value->slug) }}">
                    <div class="search_img">
                        <img src="{{ asset($value->image ? $value->image->image : 'public/no-image.png') }}" alt="{{ $value->name }}" loading="lazy">
                    </div>
                    <div class="search_content">
                        <p class="name">{{ Str::limit($value->name, 50) }}</p>
                        <p class="price">৳{{ $value->new_price }} @if($value->old_price)<del>৳{{ $value->old_price }}</del>@endif</p>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
        <a href="{{ route('search') }}?keyword={{ urlencode($keyword) }}" class="search_view_all">সব রেজাল্ট দেখুন</a>
    </div>
    @else
    <div class="search_product search_no_result">
        <p class="mb-0">কোন প্রোডাক্ট পাওয়া যায়নি</p>
    </div>
    @endif
@endif