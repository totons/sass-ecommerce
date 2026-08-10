@extends('frontEnd.layouts.master') 

@section('title', $seo->meta_title ?? 'Home')

@push('seo')
 
<meta name="app-url" content="{{ url('/') }}" />
<meta name="robots" content="index, follow" />

<meta name="description" content="{{ $seo->meta_description ?? '' }}" />
<meta name="keywords" content="{{ $seo->meta_tags ?? '' }}" />

<!-- Open Graph data -->
<meta property="og:title" content="{{ $seo->meta_title ?? '' }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image" content="{{ asset($generalsetting->og_baner ?? 'public/logo.png') }}" />
<meta property="og:description" content="{{ $seo->meta_description ?? '' }}" />

@if(!empty($seo->search_console_verification))
<meta name="google-site-verification" content="{{ $seo->search_console_verification }}">
@endif
@endpush 

@push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" rel="stylesheet" />
<style>
/* ===== Featured vendor shops — রেফারেন্স ডিজাইন (কার্ড, ওভারল্যাপ লোগো, গোলাপি ব্যাজ, VISIT STORE) ===== */
.vendor-shops-section {
    background: #ffffff;
    padding-top: 8px;
    padding-bottom: 24px;
}
.vendor-shops-section .category-products-head {
    margin-bottom: 1.25rem !important;
}
.vendor-shops-section .category-products-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #111;
    letter-spacing: -0.02em;
    margin: 0;
    text-align: left;
}
.vendor-shop-grid {
    --vendor-shop-banner-h: 108px;
    --vendor-shop-logo: 84px;
}
.vendor-shop-item {
    display: flex;
    flex-direction: column;
    position: relative;
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    text-decoration: none;
    border: 1px solid #e8e8e8;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: box-shadow 0.25s ease, transform 0.25s ease, border-color 0.25s ease;
    height: 100%;
    color: inherit;
}
.vendor-shop-item:hover {
    box-shadow: 0 12px 28px rgba(0,0,0,0.1);
    transform: translateY(-3px);
    text-decoration: none;
    color: inherit;
    border-color: #ddd;
}
.shop-banner-bg {
    position: relative;
    width: 100%;
    height: var(--vendor-shop-banner-h);
    min-height: 88px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border-radius: 14px 14px 0 0;
    flex-shrink: 0;
}
.shop-content-wrapper {
    position: relative;
    display: flex;
    flex-direction: column;
    flex: 1 1 auto;
    padding: 0 14px 16px;
    text-align: center;
    /* অর্ধেক লোগো সাদা অংশে + ওভারল্যাপের জন্য নেগেটিভ মার্জিন মিল রাখুন */
    padding-top: calc(var(--vendor-shop-logo) / 2 + 12px);
}
.shop-logo-container {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-top: calc(-1 * (var(--vendor-shop-logo) / 2 + 12px));
    margin-bottom: 0;
}
/* লোগো + ব্যাজ একসাথে, ব্যাজ গোলের নিচের ডান কোণায় */
.shop-logo-wrap {
    position: relative;
    display: inline-block;
    line-height: 0;
}
.shop-logo-circle {
    width: var(--vendor-shop-logo);
    height: var(--vendor-shop-logo);
    border-radius: 50%;
    background: #ffffff;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}
.shop-logo-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.shop-logo-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(145deg, #7c6cf0 0%, #a855c4 100%);
}
/* রেফারেন্স: গোলাপি ভেরিফাই ব্যাজ (লোগোর ডান-নিচে) */
.shop-verified-badge {
    position: absolute;
    right: 2px;
    bottom: 2px;
    width: 26px;
    height: 26px;
    background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #ffffff;
    box-shadow: 0 2px 8px rgba(219, 39, 119, 0.35);
    z-index: 2;
}
.shop-verified-badge i { color: #ffffff; font-size: 10px; line-height: 1; }
.shop-details {
    padding-top: 14px;
    margin: 0 0 14px 0;
    width: 100%;
    box-sizing: border-box;
}
.shop-title {
    font-size: 15px;
    font-weight: 700;
    color: #111;
    margin: 0 0 8px 0;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.shop-rating-stars {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    flex-wrap: wrap;
    margin-bottom: 0;
}
.shop-rating-stars i {
    font-size: 13px;
    color: #f5b301;
}
.shop-rating-stars .far.fa-star {
    color: #ddd;
}
.shop-rating-stars .fas.fa-star,
.shop-rating-stars .fas.fa-star-half-alt {
    color: #f5b301;
}
.shop-review-text {
    font-size: 11px;
    color: #888;
    margin-left: 6px;
    font-weight: 500;
}
.shop-visit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 11px 16px;
    background: #ececec;
    border-radius: 999px;
    color: #333;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    transition: background 0.2s ease, color 0.2s ease;
    margin-top: auto;
    border: none;
}
.vendor-shop-item:hover .shop-visit-btn {
    background: #2563eb;
    color: #ffffff;
}
.visit-btn-icon {
    width: 28px;
    height: 28px;
    background: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.visit-btn-icon i { font-size: 11px; color: #555; transition: color 0.2s ease; }
.vendor-shop-item:hover .visit-btn-icon {
    background: rgba(255,255,255,0.25);
    box-shadow: none;
}
.vendor-shop-item:hover .visit-btn-icon i { color: #ffffff; }
.vendor-shop-grid > [class*="col-"] {
    display: flex;
}
.vendor-shop-grid > [class*="col-"] > .vendor-shop-item {
    width: 100%;
}
@media (max-width: 768px) {
    .vendor-shop-grid {
        --vendor-shop-banner-h: 96px;
        --vendor-shop-logo: 76px;
    }
    .shop-title { font-size: 14px; }
}
@media (max-width: 576px) {
    .vendor-shop-grid {
        --vendor-shop-banner-h: 86px;
        --vendor-shop-logo: 68px;
    }
    .shop-content-wrapper { padding: 0 10px 14px; padding-top: calc(var(--vendor-shop-logo) / 2 + 10px); }
    .shop-details { padding-top: 10px; margin-bottom: 12px; }
    .shop-logo-initial { font-size: 28px; }
    .shop-visit-btn { padding: 9px 12px; font-size: 10px; }
}

/* ===== Home — Latest Blogs ===== */
.home-blog-section {
    background: #fff;
    padding-top: 8px;
    padding-bottom: 28px;
}
.home-blog-section .category-products-head {
    margin-bottom: 1rem !important;
}
.home-blog-section .category-products-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #111;
    text-align: left;
    margin: 0;
}
.home-blog-view-all {
    font-size: 0.95rem;
    font-weight: 600;
    color: #2563eb;
    text-decoration: none;
}
.home-blog-view-all:hover {
    text-decoration: underline;
    color: #1d4ed8;
}
.home-blog-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e8e8e8;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.25s ease, transform 0.25s ease;
}
.home-blog-card:hover {
    box-shadow: 0 12px 28px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}
.home-blog-card__img {
    display: block;
    overflow: hidden;
    aspect-ratio: 16 / 10;
    background: #f1f5f9;
}
.home-blog-card__img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}
.home-blog-card:hover .home-blog-card__img img {
    transform: scale(1.04);
}
.home-blog-card__body {
    padding: 1.1rem 1.15rem 1.25rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.home-blog-card__meta {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 0.5rem;
}
.home-blog-card__title {
    font-size: 1.05rem;
    font-weight: 700;
    line-height: 1.35;
    margin: 0 0 0.5rem 0;
}
.home-blog-card__title a {
    color: #111;
    text-decoration: none;
}
.home-blog-card__title a:hover {
    color: #2563eb;
}
.home-blog-card__excerpt {
    font-size: 0.9rem;
    color: #475569;
    line-height: 1.5;
    margin: 0;
    flex: 1;
}
.home-blog-card__more {
    margin-top: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #2563eb;
    text-decoration: none;
    align-self: flex-start;
}
.home-blog-card__more:hover {
    text-decoration: underline;
}
</style>
@endpush 

@section('content')


 <!-- Mobile Hero - BEAUTY SOLUTION (mobile only) -->
    <section class="mobile-hero">
        <div class="mobile-hero-content">
            <div class="mobile-hero-left">
                <h2 class="mobile-hero-title">BEAUTY SOLUTION ONE CLICK AWAY</h2>
                <a href="https://shop.shajgoj.com/shop?category=acne-treatment" class="mobile-hero-btn">SHOP BY CONCERN →</a>
            </div>
            <div class="mobile-hero-right">
                <div class="mobile-hero-grid">
                    <div class="mobile-hero-frame"><img src="https://bk.shajgoj.com/storage/2024/10/shop-by-concern-web-updated.png" alt=""></div>
                    <div class="mobile-hero-frame"><img src="https://bk.shajgoj.com/storage/2024/10/shop-by-concern-web-updated.png" alt=""></div>
                    <div class="mobile-hero-frame"><img src="https://bk.shajgoj.com/storage/2024/10/shop-by-concern-web-updated.png" alt=""></div>
                    <div class="mobile-hero-frame"><img src="https://bk.shajgoj.com/storage/2024/10/shop-by-concern-web-updated.png" alt=""></div>
                </div>
            </div>
        </div>
        <div class="carousel-dots mobile-hero-dots">
            <span></span><span></span><span></span><span></span><span></span><span class="active"></span><span></span>
        </div>
    </section>

    <!-- Hero Carousel Section (desktop) - Dynamic from Banner (category_id: 1) -->
    @php
        $slideCount = (isset($sliders) && $sliders->count() > 0) ? $sliders->count() : 1;
    @endphp
    <section class="hero-section" style="--slide-count: {{ $slideCount }};">
        <div class="hero-slider">
            <div class="slider-track">
                @if(isset($sliders) && $sliders->count() > 0)
                    @foreach($sliders as $key => $slide)
                        <div class="slide {{ $key === 0 ? 'active' : '' }}">
                            @if(!empty($slide->link))
                                <a href="{{ $slide->link }}">
                                    <img src="{{ asset($slide->image) }}" alt="">
                                </a>
                            @else
                                <img src="{{ asset($slide->image) }}" alt="">
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="slide active">
                        <img src="{{ asset(optional($generalsetting ?? null)->logo ?? 'public/logo.png') }}" alt="{{ optional($generalsetting ?? null)->name ?? 'Home' }}">
                    </div>
                @endif
            </div>
        </div>
        <div class="carousel-dots">
            @if(isset($sliders) && $sliders->count() > 0)
                @foreach($sliders as $key => $slide)
                    <span class="{{ $key === 0 ? 'active' : '' }}" data-slide="{{ $key }}"></span>
                @endforeach
            @else
                <span class="active" data-slide="0"></span>
            @endif
        </div>
    </section>

    {{-- Floating Cart (overlaps hero on right) is removed to avoid duplicate cart widgets. --}}

    <!-- Secondary Banner - Dynamic from Home Ads (category_id: 10) -->
    @if(isset($homepageads) && $homepageads->count() > 0)
        @php $homeAd = $homepageads->first(); @endphp
        <section class="secondary-banner">
            @if(!empty($homeAd->link))
                <a href="{{ $homeAd->link }}" class="eid-banner-link">
                    <img src="{{ asset($homeAd->image) }}" alt="">
                </a>
            @else
                <div class="eid-banner-link" style="pointer-events:none;">
                    <img src="{{ asset($homeAd->image) }}" alt="">
                </div>
            @endif
        </section>
    @endif

    <!-- DEALS YOU CANNOT MISS - Dynamic from Hot Deals Banner (category_id: 9) -->
    @if(isset($hotDealsBanners) && $hotDealsBanners->count() > 0)
        <section class="flash-sale deals-section">
            <h2 class="section-title section-deals-title">DEALS YOU CANNOT MISS</h2>
            <div class="flash-grid">
                @foreach($hotDealsBanners as $index => $deal)
                    @php
                        $cardClass = 'flash-card';
                        if ($index === 0) $cardClass .= ' deal-card-green';
                        elseif ($index === 1) $cardClass .= ' deal-card-gray';
                    @endphp
                    <a href="{{ $deal->link ?? '#' }}" class="{{ $cardClass }}">
                        @if(!empty($deal->title))
                            <span class="deal-badge{{ $index === 1 ? ' deal-badge-pink' : '' }}">{{ $deal->title }}</span>
                        @endif
                        <img src="{{ asset($deal->image) }}" alt="">
                    </a>
                @endforeach
            </div>
        </section>
    @endif



    <!-- HOT DEAL products (index-style logic, professional slider) -->
    @if(isset($hotdeal_top) && $hotdeal_top->count() > 0)
        @php
            $hotDealEndDate = !empty($generalsetting->hot_deal_end_date) ? $generalsetting->hot_deal_end_date.'T23:59:59' : null;
            $isHotDealActive = $hotDealEndDate ? \Carbon\Carbon::parse($hotDealEndDate)->isFuture() : false;
        @endphp
        <section class="category-products-section hot-deal-products-section" id="hot-deal-section" @if($isHotDealActive) data-hot-end="{{ $hotDealEndDate }}" @endif>
            <div class="category-products-head">
                <h2 class="category-products-title">Hot Deals</h2>
                @if($isHotDealActive)
                    <div class="flash-countdown" id="hotDealCountdownMaster" aria-label="Hot deal ends in">
                        <div class="flash-count-cell"><span class="flash-count-val" id="hdH">00</span></div>
                        <div class="flash-count-cell"><span class="flash-count-val" id="hdM">00</span></div>
                        <div class="flash-count-cell"><span class="flash-count-val" id="hdS">00</span></div>
                    </div>
                @else
                    <span class="category-products-pill">Top Sale</span>
                @endif
            </div>
            <div class="category-products-slider owl-carousel" data-category-slider>
                @foreach($hotdeal_top as $value)
                    <div class="item">
                        @include('frontEnd.layouts.partials.product_card_compact', ['value' => $value])
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- EXTRA DISCOUNT - Today's Deal (Dynamic from Home, category_id: 12) -->
    @if(isset($homeBanner) && $homeBanner->count() > 0)
        @php $todayDeal = $homeBanner->first(); @endphp
        <section class="todays-deal">
            @if(!empty($todayDeal->link))
                <a href="{{ $todayDeal->link }}" class="deal-content">
                    @php
                        $todayImg = !empty($todayDeal->image) && Str::startsWith($todayDeal->image, ['http://', 'https://'])
                            ? $todayDeal->image
                            : asset($todayDeal->image);
                    @endphp
                    <img src="{{ $todayImg }}" alt="Extra Discount">
                </a>
            @else
                <div class="deal-content" style="pointer-events:none;">
                    @php
                        $todayImg = !empty($todayDeal->image) && Str::startsWith($todayDeal->image, ['http://', 'https://'])
                            ? $todayDeal->image
                            : asset($todayDeal->image);
                    @endphp
                    <img src="{{ $todayImg }}" alt="Extra Discount">
                </div>
            @endif
        </section>
    @endif

    <!-- LIMITED TIME OFFERS (Dynamic from Offers banner, category_id: 13) -->
    @if(isset($offersBanners) && $offersBanners->count() > 0)
        <section class="major-promo">
            <h2 class="section-title">LIMITED TIME OFFERS</h2>
            <div class="major-grid">
                @foreach($offersBanners as $offer)
                    @if(!empty($offer->image))
                    <a href="{{ $offer->link ?? '#' }}" class="major-card">
                        @php
                            $offerImg = Str::startsWith($offer->image, ['http://', 'https://'])
                                ? $offer->image
                                : asset($offer->image);
                        @endphp
                        <img src="{{ $offerImg }}" alt="{{ $offer->title ?? 'Offer' }}" loading="lazy">
                    </a>
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    

    <!-- FLASH SALE (professional layout, index-style product card behavior) -->
    @if(isset($flas_sales) && $flas_sales->count() > 0 && isset($generalsetting) && !empty($generalsetting->flash_sale_end_date))
        @php
            $flashSaleEndDate = $generalsetting->flash_sale_end_date.'T23:59:59';
            $isFlashSaleActive = \Carbon\Carbon::parse($flashSaleEndDate)->isFuture();
        @endphp
        @if($isFlashSaleActive)
            <section class="flash-products-section flash-products-section--premium" id="flash-sale-section" data-flash-end="{{ $generalsetting->flash_sale_end_date }}T23:59:59">
                <div class="flash-sales-head">
                    <div class="flash-title-wrap">
                        <h2 class="flash-sales-title">Flash Sales</h2>
                        <p class="flash-sales-subtitle">Limited-time offers on trending beauty picks</p>
                    </div>
                    <div class="flash-countdown" id="flashCountdownMaster" aria-label="Sale ends in">
                        <div class="flash-count-cell"><span class="flash-count-val" id="fcH">00</span></div>
                        <div class="flash-count-cell"><span class="flash-count-val" id="fcM">00</span></div>
                        <div class="flash-count-cell"><span class="flash-count-val" id="fcS">00</span></div>
                    </div>
                </div>
                <div class="flash-sale-master owl-carousel">
                    @foreach($flas_sales as $value)
                        <div class="item">
                            @include('frontEnd.layouts.partials.product_card_compact', ['value' => $value])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @endif
<!-- SHOP BEAUTY PRODUCTS BY CATEGORY (dynamic: parent categories, max 8 — same as index menucategories) -->
    @php
        $shopByCategory = isset($menucategories) ? $menucategories->take(8) : collect();
    @endphp
    @if($shopByCategory->count() > 0)
        <section class="category-section" id="category-section">
            <h2 class="section-title">SHOP BEAUTY PRODUCTS BY CATEGORY</h2>
            <div class="category-grid">
                @foreach($shopByCategory as $cat)
                    <a href="{{ route('category', $cat->slug) }}" class="category-card">
                        @if(!empty($cat->image))
                            @php
                                $catImg = Str::startsWith($cat->image, ['http://', 'https://'])
                                    ? $cat->image
                                    : asset($cat->image);
                            @endphp
                            <img src="{{ $catImg }}" alt="{{ $cat->name }}" loading="lazy">
                        @endif
                        <span>{{ Str::upper($cat->name) }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
    <!-- Category-wise products (integrated from index logic) -->
    @if(isset($homeproducts) && $homeproducts && $homeproducts->count() > 0)
        @foreach($homeproducts as $homecat)
            @if(isset($homecat->products) && $homecat->products->count() > 0)
                <section class="category-products-section">
                    <div class="category-products-head">
                        <h2 class="category-products-title">{{ $homecat->name }}</h2>
                        <a href="{{ route('category', $homecat->slug) }}" class="category-products-more">View More</a>
                    </div>
                    <div class="category-products-slider owl-carousel" data-category-slider>
                        @foreach($homecat->products as $value)
                            <div class="item">
                                @include('frontEnd.layouts.partials.product_card_compact', ['value' => $value])
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach
    @endif

    <!-- BRANDS (separate section like index) -->
    @if(isset($brands) && $brands->count() > 0)
        <section class="homeproduct brand-section master-brands-section">
            <div class="container-fluid master-brands-inner">
                <div class="brand-master-grid">
                    @foreach($brands as $brand)
                        @php
                            $brandImg = !empty($brand->image) && Str::startsWith($brand->image, ['http://', 'https://'])
                                ? $brand->image
                                : asset($brand->image);
                        @endphp
                        <a href="{{ route('brand.products', $brand->slug) }}" class="brand-master-item">
                            <img
                                src="{{ !empty($brand->image) ? $brandImg : '' }}"
                                alt="{{ $brand->name }}"
                                loading="lazy"
                                @if(empty($brand->image)) style="display:none;" @endif
                            />
                            @if(empty($brand->image))
                                <div class="brand-master-fallback">{{ Str::upper(Str::limit($brand->name, 1)) }}</div>
                            @endif
                            <div class="brand-master-name">{{ $brand->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured vendor shops (same idea as reference index) --}}
    @if(isset($vendors) && $vendors->count() > 0)
        <section class="homeproduct vendor-shops-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="category-products-head mb-3">
                            <h2 class="category-products-title">Our Featured Shops</h2>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row vendor-shop-grid g-3">
                            @foreach($vendors as $vendor)
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                    <a href="{{ route('vendor.shop', $vendor->slug) }}" class="vendor-shop-item">
                                        <div class="shop-banner-bg" @if($vendor->banner) style="background-image: url('{{ asset($vendor->banner) }}');" @else style="background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);" @endif></div>
                                        <div class="shop-content-wrapper">
                                            <div class="shop-logo-container">
                                                <div class="shop-logo-wrap">
                                                    <div class="shop-logo-circle">
                                                        @if($vendor->logo)
                                                            <img src="{{ asset($vendor->logo) }}" alt="{{ $vendor->shop_name }}" loading="lazy" />
                                                        @else
                                                            <div class="shop-logo-initial">
                                                                {{ strtoupper(Str::substr($vendor->shop_name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if(isset($vendor->verification_status) && $vendor->verification_status === 'approved')
                                                        <div class="shop-verified-badge" title="Verified shop">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="shop-details">
                                                <h3 class="shop-title">{{ $vendor->shop_name }}</h3>
                                                <div class="shop-rating-stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($vendor->average_rating))
                                                            <i class="fas fa-star"></i>
                                                        @elseif($i - 0.5 <= $vendor->average_rating)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    @php $tr = (int) ($vendor->total_reviews ?? 0); @endphp
                                                    <span class="shop-review-text">({{ $tr }} {{ $tr === 1 ? 'review' : 'reviews' }})</span>
                                                </div>
                                            </div>
                                            <div class="shop-visit-btn">
                                                <span class="visit-btn-icon"><i class="fas fa-arrow-right"></i></span>
                                                <span class="visit-btn-text">VISIT STORE</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Latest Blogs (ডাটা: FrontendController → $blogs, limit 3) --}}
    @if(isset($blogs) && $blogs->count() > 0)
        <section class="homeproduct home-blog-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="category-products-head mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h2 class="category-products-title mb-0">Latest Blogs</h2>
                            <a href="{{ route('blogs') }}" class="home-blog-view-all">View All →</a>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach($blogs as $blog)
                        <div class="col-lg-4 col-md-6">
                            <article class="home-blog-card">
                                <a href="{{ route('blog.details', $blog->slug) }}" class="home-blog-card__img">
                                    @if(!empty($blog->image))
                                        @php
                                            $blogImg = Str::startsWith($blog->image, ['http://', 'https://'])
                                                ? $blog->image
                                                : url('public/'.$blog->image);
                                        @endphp
                                        <img src="{{ $blogImg }}" alt="{{ $blog->title }}" loading="lazy" width="400" height="250">
                                    @else
                                        <img src="{{ url('public/no-image.png') }}" alt="{{ $blog->title }}" loading="lazy" width="400" height="250">
                                    @endif
                                </a>
                                <div class="home-blog-card__body">
                                    <div class="home-blog-card__meta">
                                        {{ $blog->created_at->format('d M Y') }}
                                        @if(isset($blog->views))
                                            <span aria-hidden="true"> · </span>{{ $blog->views }} views
                                        @endif
                                    </div>
                                    <h3 class="home-blog-card__title">
                                        <a href="{{ route('blog.details', $blog->slug) }}">{{ Str::limit($blog->title, 58) }}</a>
                                    </h3>
                                    <p class="home-blog-card__excerpt">{{ Str::limit(strip_tags($blog->short_description ?? ''), 110) }}</p>
                                    <a href="{{ route('blog.details', $blog->slug) }}" class="home-blog-card__more">Read more →</a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Promotional Grid - Top Brands (Dynamic from Home Ads 2, category_id: 11) -->
    @if(isset($homepageads2) && $homepageads2->count() > 0)
        <section class="promo-grid">
            @foreach($homepageads2 as $ad)
                @if(!empty($ad->image))
                <a href="{{ $ad->link ?? '#' }}" class="promo-card">
                    @php
                        $imgSrc = Str::startsWith($ad->image, ['http://', 'https://'])
                            ? $ad->image
                            : asset($ad->image);
                    @endphp
                    <img src="{{ $imgSrc }}" alt="{{ $ad->title ?? 'Promo' }}" loading="lazy">
                </a>
                @endif
            @endforeach
        </section>
    @endif

@endsection 

