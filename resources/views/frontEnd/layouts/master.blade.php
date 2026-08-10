<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="{{ $generalsetting->primary_color ?? '#3c7d17' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="mobile-web-app-capable" content="yes">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	 <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}" alt="{{$generalsetting->name}} Favicon" />
      
    @if(!empty(optional($generalsetting)->facebook_verification))
    <meta name="facebook-domain-verification" content="{{ $generalsetting->facebook_verification }}" />
    @endif
    @if(!empty(optional($generalsetting)->google_verification))
    <meta name="google-site-verification" content="{{ $generalsetting->google_verification }}" />
    @endif
    <title>@yield('title') - {{$generalsetting->name}}</title>

    @php
        $pixels = $pixels ?? collect();
        $gtm_code = $gtm_code ?? collect();
        $tiktok_pixels = $tiktok_pixels ?? collect();
    @endphp

    {{-- dataLayer অ্যারে GTM-এর আগে থাকতে হবে (খালি/init) --}}
    <script>window.dataLayer = window.dataLayer || [];</script>

    @foreach($pixels as $pixel)
    @continue(empty($pixel->code))
    <!-- Facebook Pixel {{ $loop->iteration }} -->
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', @json($pixel->code));
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" alt=""
        src="https://www.facebook.com/tr?id={{ urlencode($pixel->code) }}&ev=PageView&noscript=1" /></noscript>
    @endforeach

    @foreach($gtm_code as $gtm)
    @continue(empty($gtm->code))
    @php $gtmId = str_starts_with((string)$gtm->code, 'GTM-') ? $gtm->code : 'GTM-' . $gtm->code; @endphp
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer',@json($gtmId));</script>
    <!-- End Google Tag Manager -->
    @endforeach

    @foreach($tiktok_pixels as $tp)
    @continue(empty($tp->code))
    <!-- TikTok Pixel {{ $loop->iteration }} -->
    <script>
        !function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=d.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
        ttq.load(@json($tp->code));
        ttq.page();
    }(window, document, 'ttq');
    </script>
    @endforeach

    {{-- পেজ-স্পেসিফিক ইভেন্ট: FB/TikTok স্টাব লোড হওয়ার পরে (ttq.track কিউ হয়ে যাবে) --}}
    <script>
        window.dataLayer.push({
            event: 'page_context',
            pageType: @json($pageType ?? \Illuminate\Support\Facades\Route::currentRouteName() ?? 'general'),
            pagePath: @json(parse_url(url()->current(), PHP_URL_PATH) ?: '/'),
            pageTitle: @json(strip_tags($__env->yieldContent('title') ?: '') . ' - ' . ($generalsetting->name ?? '')),
            siteName: @json($generalsetting->name ?? ''),
            currency: 'BDT',
            language: 'bn'
        });
        @stack('dataLayer')
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&family=Dancing+Script:wght@600&family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontEnd/styles.css') }}?v={{ @filemtime(public_path('frontEnd/styles.css')) }}">
    <link rel="stylesheet" href="{{asset('public/frontEnd/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
    <style>
        :root {
            --brand-primary: {{ $generalsetting->primary_color ?? '#3c7d17' }};
            --brand-secondary: {{ $generalsetting->secodery_color ?? '#326814' }};
            --footer-bg: {{ $generalsetting->footer_color ?? '#1a1a1a' }};
            --footer-copyright-text: {{ $generalsetting->copyright_color ?? 'rgba(255,255,255,0.88)' }};
        }
    </style>
    @stack('seo')
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/toastr.min.css') }}" />
    @stack('css')
</head>
<body>
    @foreach($gtm_code as $gtm)
    @continue(empty($gtm->code))
    @php $gtmId = str_starts_with((string)$gtm->code, 'GTM-') ? $gtm->code : 'GTM-' . $gtm->code; @endphp
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ urlencode($gtmId) }}"
        height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>
    @endforeach

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    <nav class="mobile-nav-drawer" id="mobileNavDrawer">
        <div class="mobile-nav-header">
            <span class="mobile-nav-close" id="mobileNavClose">✕</span>
        </div>
        <div class="mobile-nav-links">
            <h4 class="mobile-nav-title">CATEGORIES</h4>
            @if(isset($menucategories) && $menucategories->count() > 0)
                <div class="mobile-nav-category-list">
                    @foreach($menucategories as $scategory)
                        @php
                            $mainSubsId = 'mn-' . $scategory->slug . '-subs';
                        @endphp

                        <div class="mobile-nav-row">
                            <a href="{{ route('category', $scategory->slug) }}" class="mobile-nav-main-link">
                                {{ $scategory->name ?? ucwords(str_replace('-', ' ', $scategory->slug)) }}
                            </a>

                            @if(!empty($scategory->subcategories) && $scategory->subcategories->count() > 0)
                                <button type="button" class="mobile-nav-toggle" data-target="#{{ $mainSubsId }}" aria-label="Toggle subcategories">+</button>
                            @else
                                <span class="mobile-nav-toggle-placeholder" aria-hidden="true"> </span>
                            @endif
                        </div>

                        @if(!empty($scategory->subcategories) && $scategory->subcategories->count() > 0)
                            <ul class="mobile-nav-subcat-list" id="{{ $mainSubsId }}">
                                @foreach($scategory->subcategories as $subcategory)
                                    @php
                                        $subChildId = 'mn-' . $scategory->slug . '-' . $subcategory->slug . '-child';
                                    @endphp
                                    <li>
                                        <div class="mobile-nav-row mobile-nav-subcat-row">
                                            <a href="{{ route('subcategory', $subcategory->slug) }}" class="mobile-nav-subcat-link">
                                                {{ $subcategory->subcategoryName ?? $subcategory->name }}
                                            </a>

                                            @if(!empty($subcategory->childcategories) && $subcategory->childcategories->count() > 0)
                                                <button type="button" class="mobile-nav-toggle" data-target="#{{ $subChildId }}" aria-label="Toggle child categories">+</button>
                                            @else
                                                <span class="mobile-nav-toggle-placeholder" aria-hidden="true"> </span>
                                            @endif
                                        </div>

                                        @if(!empty($subcategory->childcategories) && $subcategory->childcategories->count() > 0)
                                            <ul class="mobile-nav-childcat-list" id="{{ $subChildId }}">
                                                @foreach($subcategory->childcategories as $childcat)
                                                    <li>
                                                        <a href="{{ route('products', $childcat->slug) }}" class="mobile-nav-childcat-link">
                                                            {{ $childcat->childcategoryName ?? $childcat->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Brands (drawer) intentionally removed. --}}
        </div>
    </nav>

    <!-- Top Header + Nav (wrapper for mega menu positioning) -->
    <div id="headerNavWrap">
    <header class="top-header">
        <div class="header-container">
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
            <div class="logo-brand-group">
<a href="{{route('home')}}" class="logo"><img src="{{asset($generalsetting->dark_logo)}}" alt=""></a>
                <a href="{{ route('brand') }}" class="brands-link">BRANDS</a>
            </div>
            <div class="search-section">
                <form action="{{ route('search') }}" method="GET" class="search-form">
                    <div class="search-bar">
                        <input type="text" placeholder="{{ $generalsetting->search_placeholder ?? 'Grab Himalaya upto 50%' }}" name="keyword" class="search_keyword search_click" autocomplete="off" />
                        <button type="submit" class="search-icon-btn" title="Search">
                            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </button>
                    </div>
                </form>
                <div class="search_result"></div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('customer.order_track') }}" class="btn-wishlist" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">TRACK ORDER</a>
                @if(Auth::guard('customer')->check())
                <a href="{{ route('customer.account') }}" class="btn-login" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">{{ Str::limit(Auth::guard('customer')->user()->name, 12) }}</a>
                @else
                <a href="{{ route('customer.login') }}" class="btn-login" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">LOGIN</a>
                @endif
                @php
                    $headerCartContent = Cart::instance('shopping')->content();
                    $headerCartSubtotal = (float) str_replace(',', '', Cart::instance('shopping')->subtotal());
                @endphp
                <div class="header-cart-hover-wrap">
                    <a href="javascript:void(0)" class="btn-bag cart-open-btn" onclick="openSidebarCart()" style="text-decoration:none;display:inline-flex;align-items:center;gap:10px;color:#fff;">
                        <svg class="bag-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        <span class="bag-text" style="color:#fff;">BAG</span>
                        <span class="bag-count mobilecart-qty">{{ Cart::instance('shopping')->count() }}</span>
                    </a>
                    <div class="header-cart-hover-dropdown" role="region" aria-label="কার্ট সারাংশ">
                        <div id="headerCartHoverContent">
                            @include('frontEnd.layouts.ajax.header_cart_hover', ['cartContent' => $headerCartContent, 'subtotal' => $headerCartSubtotal, 'generalsetting' => $generalsetting])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Bar - Mega menu: subcategories + child categories -->
    <nav class="main-nav">
        <div class="nav-container">
            @php
                $menuCats = isset($menucategories) ? $menucategories : collect();
                $pillColors = ['pill-blue', 'pill-pink', 'pill-purple', 'pill-teal', 'pill-green'];
            @endphp
            @if($menuCats->count() > 0)
                <div class="nav-links">
                    @foreach($menuCats->take(6) as $cat)
                        <div class="nav-item-dropdown" tabindex="0">
                            <a href="{{ route('category', $cat->slug) }}" class="nav-link-main">{{ $cat->name }}</a>
                            @if($cat->subcategories && $cat->subcategories->count() > 0)
                                @if($cat->subcategories->count() > 1)
                                    {{-- একাধিক সাবক্যাটাগরি: গ্রিড মেগা মেনু --}}
                                    <div class="mega-menu-panel" role="navigation" aria-label="{{ $cat->name }}">
                                        <div class="mega-menu-inner" style="--subcount: {{ $cat->subcategories->count() }};">
                                            <div class="mega-menu-grid">
                                                @foreach($cat->subcategories as $sub)
                                                    <div class="mega-menu-col">
                                                        <a href="{{ route('subcategory', $sub->slug) }}" class="mega-menu-heading">{{ strtoupper($sub->subcategoryName) }}</a>
                                                        @if($sub->childcategories && $sub->childcategories->count() > 0)
                                                            <ul class="mega-menu-list">
                                                                @foreach($sub->childcategories as $child)
                                                                    <li><a href="{{ route('products', $child->slug) }}">{{ $child->childcategoryName }}</a></li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- একটা সাবক্যাটাগরি: সিঙ্গেল কলাম (ছবির মত) --}}
                                    @php $singleSub = $cat->subcategories->first(); @endphp
                                    <div class="mega-menu-panel mega-menu-panel--single" role="navigation" aria-label="{{ $cat->name }}">
                                        <div class="mega-menu-single-inner">
                                            @if($singleSub->childcategories && $singleSub->childcategories->count() > 0)
                                                <ul class="mega-menu-single-list">
                                                    @foreach($singleSub->childcategories as $child)
                                                        <li><a href="{{ route('products', $child->slug) }}">{{ $child->childcategoryName }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <a href="{{ route('subcategory', $singleSub->slug) }}" class="mega-menu-single-link">{{ $singleSub->subcategoryName }}</a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
                @if($menuCats->count() > 6)
                    <div class="nav-pills">
                        @foreach($menuCats->skip(6) as $idx => $cat)
                            <div class="nav-item-dropdown nav-pill-dropdown" tabindex="0">
                                <a href="{{ route('category', $cat->slug) }}" class="pill {{ $pillColors[$idx % count($pillColors)] }}">{{ strtoupper($cat->name) }}</a>
                                @if($cat->subcategories && $cat->subcategories->count() > 0)
                                    @if($cat->subcategories->count() > 1)
                                        <div class="mega-menu-panel mega-menu-panel--pill" role="navigation" aria-label="{{ $cat->name }}">
                                            <div class="mega-menu-inner" style="--subcount: {{ $cat->subcategories->count() }};">
                                                <div class="mega-menu-grid">
                                                    @foreach($cat->subcategories as $sub)
                                                        <div class="mega-menu-col">
                                                            <a href="{{ route('subcategory', $sub->slug) }}" class="mega-menu-heading">{{ strtoupper($sub->subcategoryName) }}</a>
                                                            @if($sub->childcategories && $sub->childcategories->count() > 0)
                                                                <ul class="mega-menu-list">
                                                                    @foreach($sub->childcategories as $child)
                                                                        <li><a href="{{ route('products', $child->slug) }}">{{ $child->childcategoryName }}</a></li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @php $singleSub = $cat->subcategories->first(); @endphp
                                        <div class="mega-menu-panel mega-menu-panel--single mega-menu-panel--pill" role="navigation" aria-label="{{ $cat->name }}">
                                            <div class="mega-menu-single-inner">
                                                @if($singleSub->childcategories && $singleSub->childcategories->count() > 0)
                                                    <ul class="mega-menu-single-list">
                                                        @foreach($singleSub->childcategories as $child)
                                                            <li><a href="{{ route('products', $child->slug) }}">{{ $child->childcategoryName }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <a href="{{ route('subcategory', $singleSub->slug) }}" class="mega-menu-single-link">{{ $singleSub->subcategoryName }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="nav-links">
                    <a href="{{ route('shop') }}" class="nav-link-main">Shop</a>
                </div>
            @endif
        </div>
    </nav>
    </div>

   

 @yield('content')






    @php
        $pages = $pages ?? collect();
        $pagesright = $pagesright ?? collect();
        $socialicons = $socialicons ?? collect();
        $contact = $contact ?? (object) ['hotline' => ''];
    @endphp

    <!-- New professional footer (unique nf-* classes) -->
    <footer class="nf-footer">
        <div class="nf-container">
            <div class="nf-grid">
                <div class="nf-col nf-about">
                    <a href="{{ route('home') }}" class="nf-logo">
                        <img src="{{ asset($generalsetting->white_logo) }}" alt="{{ $generalsetting->name }} logo">
                    </a>
                    <p class="nf-about-text">
                        {{ optional($generalsetting)->footer_about_text ?? 'আপনার ব্যবসার ডিজিটাল পার্টনার। আমরা বিশ্বাস করি গুণগত মান এবং গ্রাহক সন্তুষ্টিতে। প্রযুক্তির সাথে এগিয়ে চলুন আমাদের সাথে।' }}
                    </p>
                    @if(!empty($contact->hotline))
                        <a href="tel:{{ $contact->hotline }}" class="nf-hotline">{{ $contact->hotline }}</a>
                    @endif

                    <div class="nf-app-badges">
                        @if(!empty(optional($generalsetting)->google_play_link))
                            <a href="{{ optional($generalsetting)->google_play_link }}" class="nf-badge" target="_blank" rel="noopener">
                                <img src="/public/uploads/play.svg" alt="Get it on Google Play">
                            </a>
                        @endif
                        @if(!empty(optional($generalsetting)->app_store_link))
                            <a href="{{ optional($generalsetting)->app_store_link }}" class="nf-badge" target="_blank" rel="noopener">
                                <img src="/public/uploads/app.png" alt="Download on the App Store">
                            </a>
                        @endif
                    </div>
                </div>

                <div class="nf-col">
                    <h4 class="nf-title">Useful Link</h4>
                    <ul class="nf-list">
                        <li><a href="{{ route('sellers') }}">All Sellers</a></li>
                        <li><a href="{{ route('complaint') }}">Complaints</a></li>
                        @foreach($pages as $page)
                            <li><a href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="nf-col">
                    <h4 class="nf-title">Link</h4>
                    <ul class="nf-list">
                        <li><a href="{{ route('shop') }}">All Products</a></li>
                        @foreach($pagesright as $value)
                            <li><a href="{{ route('page', ['slug' => $value->slug]) }}">{{ $value->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="nf-col">
                    <h4 class="nf-title">Newsletter Subscribe</h4>
                    <form action="{{ route('frontend.newsletter.subscribe') }}" method="POST" class="nf-newsletter-form">
                        @csrf
                        <input type="email" name="email" class="nf-newsletter-input" placeholder="Enter your email..." required>
                        <button type="submit" class="nf-newsletter-btn" aria-label="Subscribe">
                            <i class="fas fa-paper-plane" aria-hidden="true"></i>
                        </button>
                    </form>

                    @if($socialicons->count() > 0)
                        <ul class="nf-social">
                            @foreach($socialicons as $value)
                                <li>
                                    <a href="{{ $value->link }}" class="nf-social-link" target="_blank" rel="noopener" aria-label="social">
                                        <i class="{{ $value->icon }}" aria-hidden="true"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="nf-bottom">
                <div class="nf-bottom-inner">
                    <span>Copyright © {{ date('Y') }} {{ $generalsetting->name }}. All rights reserved</span>
                    <span class="nf-divider">|</span>
                    <span>Website Designed by:</span>
                    <a href="https://www.creativedesign.com.bd" target="_blank" rel="noopener">
                        <img src="{{ asset('public/uploads/creativedesign.png') }}" alt="Creative Design">
                        <strong>Creative Design</strong>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Floating Cart - Sidebar cart open/close --}}
    <a href="javascript:void(0)" class="floating-cart-widget" id="floatingCartBtn" title="কার্ট দেখুন" aria-label="Open cart">
        <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
        <span class="floating-cart-badge mobilecart-qty">{{ Cart::instance('shopping')->count() }}</span>
    </a>

    <div id="sidebarCartOverlay" class="sidebar-cart-overlay" onclick="closeSidebarCart()"></div>
    <div id="sidebarCartDrawer" class="sidebar-cart-drawer">
        <div id="sidebarCartContent"></div>
    </div>

    <style>
        /* Floating cart widget */
        .floating-cart-widget {
            position: fixed;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            width: 52px;
            height: 70px;
            background: {{ $generalsetting->primary_color ?? '#007bff' }};
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px 0 0 12px;
            box-shadow: -3px 0 15px {{ ($generalsetting->primary_color ?? '#007bff') }}66;
            z-index: 9998;
            text-decoration: none;
            transition: all 0.3s ease;
            overflow: visible;
        }
        .floating-cart-widget:hover {
            color: #fff;
            width: 56px;
            box-shadow: -4px 0 20px {{ ($generalsetting->primary_color ?? '#007bff') }}80;
        }
        .floating-cart-widget i {
            font-size: 24px;
        }
        .floating-cart-badge {
            position: absolute;
            top: -6px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 22px;
            height: 22px;
            background: #fff;
            color: {{ $generalsetting->primary_color ?? '#007bff' }};
            font-size: 11px;
            font-weight: bold;
            border-radius: 50%;
            border: 2px solid {{ $generalsetting->primary_color ?? '#007bff' }};
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }
        @media (max-width: 768px) {
            .floating-cart-widget {
                top: 35%;
                width: 48px;
                height: 60px;
                z-index: 9999;
            }
            .floating-cart-widget i { font-size: 20px; }
            .floating-cart-badge { min-width: 20px; height: 20px; font-size: 10px; }
        }

        /* Sidebar cart */
        .sidebar-cart-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 10010;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }
        .sidebar-cart-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .sidebar-cart-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: 380px;
            max-width: 95vw;
            height: 100dvh;
            background: #fff;
            z-index: 10011;
            transform: translateX(100%);
            transition: transform 0.35s ease;
            box-shadow: -5px 0 25px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .sidebar-cart-drawer.active {
            transform: translateX(0);
        }
        #sidebarCartContent {
            height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar-cart-header {
            color: #fff;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .sidebar-cart-close {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 22px;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
        }
        .sidebar-cart-close:hover { opacity: 0.9; }
        .sidebar-cart-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            flex: 1;
        }
        .sidebar-cart-body {
            flex: 0 0 auto;
            padding: 16px;
            background: #f8f9fa;
        }
        .sidebar-cart-item {
            display: flex;
            gap: 12px;
            padding: 12px;
            background: #fff;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .sidebar-cart-item-img {
            width: 70px;
            height: 85px;
            flex-shrink: 0;
            border-radius: 6px;
            overflow: hidden;
        }
        .sidebar-cart-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .sidebar-cart-item-details {
            flex: 1;
            min-width: 0;
            position: relative;
        }
        .sidebar-cart-item-title {
            font-weight: 600;
            color: #222;
            text-decoration: none;
            display: block;
            margin-bottom: 4px;
            font-size: 14px;
            line-height: 1.3;
        }
        .sidebar-cart-item-price {
            font-size: 13px;
            color: #444;
            margin: 0 0 4px 0;
        }
        .sidebar-cart-item-savings {
            font-size: 12px;
            color: #28a745;
            font-weight: 500;
            margin: 0 0 8px 0;
        }
        .sidebar-cart-item-remove {
            position: absolute;
            bottom: 0;
            right: 0;
            background: none;
            border: none;
            color: {{ $generalsetting->primary_color ?? '#007bff' }};
            cursor: pointer;
            padding: 4px;
            font-size: 14px;
        }
        .sidebar-cart-item-remove:hover { opacity: 0.85; }
        .sidebar-cart-qty {
            display: flex;
            align-items: center;
            width: fit-content;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            margin: 8px 0 6px 0;
        }
        .sidebar-qty-btn {
            width: 28px;
            height: 28px;
            border: none;
            background: #f0f0f0;
            color: #333;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .sidebar-qty-btn:hover {
            background: {{ $generalsetting->primary_color ?? '#007bff' }};
            color: #fff;
        }
        .sidebar-qty-num {
            min-width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            background: #fff;
        }
        .sidebar-cart-empty {
            text-align: center;
            padding: 40px 20px;
            color: #888;
        }
        .sidebar-cart-empty i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }
        .sidebar-cart-footer {
            padding: 16px 20px;
            border-top: 1px solid #eee;
            background: #fff;
        }
        .sidebar-cart-total {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 12px;
        }
        .sidebar-cart-total-label {
            font-size: 14px;
            color: #666;
        }
        .sidebar-cart-total-amount {
            font-size: 20px;
            font-weight: 700;
            color: #222;
        }
        .sidebar-cart-checkout-btn {
            display: block;
            width: 100%;
            padding: 14px 24px;
            color: #fff !important;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
            border-radius: 6px;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .sidebar-cart-checkout-btn:hover { opacity: 0.9; color: #fff !important; }

        /* Fly to cart animation */
        .fly-to-cart-img {
            position: fixed;
            z-index: 99999;
            pointer-events: none;
            border-radius: 8px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.35);
            object-fit: cover;
            border: 2px solid #fff;
        }
        @keyframes cartBump {
            0% { transform: scale(1); }
            40% { transform: scale(1.25); }
            70% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }
        .cart-bump-animate {
            animation: cartBump 0.45s ease-out;
        }

        /* হেডার BAG — হোভারে মিনি কার্ট */
        .header-cart-hover-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        .header-cart-hover-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 280px;
            max-width: 360px;
            max-height: 420px;
            overflow-y: auto;
            padding: 14px 14px 14px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.18);
            border: 1px solid #e2e8f0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: opacity 0.2s ease, visibility 0.2s ease, transform 0.2s ease;
            z-index: 10040;
            pointer-events: none;
        }
        .header-cart-hover-dropdown::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            height: 14px;
        }
        .header-cart-hover-wrap:hover .header-cart-hover-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }
        /* প্রোডাক্ট রো — ছবির মতো লেআউট */
        .header-cart-hover-empty {
            text-align: center;
            padding: 16px 0;
            font-size: 14px;
            color: #64748b;
        }
        .header-cart-hover-empty .header-cart-hover-checkout {
            margin-top: 10px;
        }
        .header-cart-hover-items {
            margin-bottom: 12px;
        }
        .header-cart-hover-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .header-cart-hover-row:last-child {
            border-bottom: none;
        }
        .header-cart-hover-thumb {
            flex-shrink: 0;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header-cart-hover-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .header-cart-hover-info {
            flex: 1;
            min-width: 0;
        }
        .header-cart-hover-name {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #0f172a !important;
            text-decoration: none !important;
            line-height: 1.35;
            margin-bottom: 2px;
        }
        .header-cart-hover-name:hover {
            text-decoration: underline !important;
        }
        .header-cart-hover-qty {
            font-size: 12px;
            color: #475569;
        }
        .header-cart-hover-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 4px;
        }
        .header-cart-hover-price {
            font-size: 14px;
            color: #0f172a;
        }
        .header-cart-hover-remove {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #ef4444;
            color: #fff !important;
            border: none;
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .header-cart-hover-remove:hover {
            background: #dc2626;
        }
        .header-cart-hover-footer {
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }
        .header-cart-hover-total-line {
            margin: 0 0 12px;
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }
        .header-cart-hover-total-line strong {
            font-weight: 800;
        }
        .header-cart-hover-checkout {
            display: block;
            width: 100%;
            padding: 12px 16px;
            text-align: center;
            font-weight: 700;
            font-size: 15px;
            color: #fff !important;
            background: var(--brand-primary, #7c3aed);
            border-radius: 8px;
            text-decoration: none !important;
            transition: filter 0.2s ease, transform 0.15s ease;
        }
        .header-cart-hover-checkout:hover {
            filter: brightness(1.08);
            color: #fff !important;
        }
        @media (max-width: 991.98px) {
            .header-cart-hover-dropdown {
                display: none !important;
            }
        }
    </style>

    @php
        $fcContact = $contact ?? null;
        $fcHasAny = $fcContact && (
            trim((string) ($fcContact->whatsapp ?? '')) !== ''
            || trim((string) ($fcContact->phone ?? '')) !== ''
            || trim((string) ($fcContact->hotline ?? '')) !== ''
        );
        if (!$fcHasAny) {
            $fcContact = \App\Models\Contact::where('status', 1)->first() ?? $fcContact;
        }
        $fcWaDigits = preg_replace('/\D+/', '', (string) (optional($fcContact)->whatsapp ?? optional($fcContact)->phone ?? optional($fcContact)->hotline ?? ''));
        $fcTel = trim((string) (optional($fcContact)->hotline ?? optional($fcContact)->phone ?? ''));
        $fcMessengerUser = trim((string) (optional($generalsetting)->facebook_page_username ?? ''));
        $fcMessengerUser = ltrim($fcMessengerUser, '@');
        $fcShowWidget = ($fcWaDigits !== '' || $fcTel !== '' || $fcMessengerUser !== '');
    @endphp
    @if($fcShowWidget)
    <!-- Floating WhatsApp / Messenger / Phone -->
    <div class="floating-contact-widget" id="floatingContactWidget">
        <div class="floating-contact-options" id="floatingContactOptions" aria-hidden="true">
            @if($fcMessengerUser !== '')
                <a href="https://m.me/{{ rawurlencode($fcMessengerUser) }}" target="_blank" rel="noopener" class="floating-contact-btn floating-contact-messenger" title="Messenger">
                    <i class="fab fa-facebook-messenger" aria-hidden="true"></i>
                </a>
            @endif
            @if($fcWaDigits !== '')
                <a href="https://wa.me/{{ $fcWaDigits }}" target="_blank" rel="noopener" class="floating-contact-btn floating-contact-whatsapp" title="WhatsApp">
                    <i class="fab fa-whatsapp" aria-hidden="true"></i>
                </a>
            @endif
            @if($fcTel !== '')
                <a href="tel:{{ preg_replace('/\s+/', '', $fcTel) }}" class="floating-contact-btn floating-contact-phone" title="কল করুন">
                    <i class="fas fa-phone" aria-hidden="true"></i>
                </a>
            @endif
        </div>
        <button type="button" class="floating-contact-toggle" id="floatingContactToggle" aria-expanded="false" aria-controls="floatingContactOptions" title="যোগাযোগ">
            <i class="fas fa-comment-dots" aria-hidden="true"></i>
        </button>
    </div>
    <style>
        .floating-contact-widget {
            position: fixed;
            bottom: calc(20px + env(safe-area-inset-bottom, 0px));
            right: calc(16px + env(safe-area-inset-right, 0px));
            z-index: 10020;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }
        .floating-contact-toggle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #25D366, #128C7E);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.22);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .floating-contact-toggle:hover {
            transform: scale(1.06);
            color: #fff;
        }
        .floating-contact-options {
            display: none;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .floating-contact-options.is-open {
            display: flex;
            animation: floatingContactIn 0.28s ease;
        }
        @keyframes floatingContactIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .floating-contact-btn {
            position: relative;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            color: #fff !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            text-decoration: none !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .floating-contact-btn:hover {
            transform: translateY(-3px);
            color: #fff !important;
        }
        .floating-contact-whatsapp { background: #25D366; }
        .floating-contact-messenger { background: #0084FF; }
        .floating-contact-phone { background: #FF3B30; }
        @media (max-width: 768px) {
            .floating-contact-widget {
                bottom: calc(88px + env(safe-area-inset-bottom, 0px));
            }
        }
    </style>
    <script>
        (function () {
            var t = document.getElementById('floatingContactToggle');
            var o = document.getElementById('floatingContactOptions');
            if (!t || !o) return;
            t.addEventListener('click', function (e) {
                e.stopPropagation();
                var open = o.classList.toggle('is-open');
                t.setAttribute('aria-expanded', open ? 'true' : 'false');
                o.setAttribute('aria-hidden', open ? 'false' : 'true');
            });
            document.addEventListener('click', function () {
                o.classList.remove('is-open');
                t.setAttribute('aria-expanded', 'false');
                o.setAttribute('aria-hidden', 'true');
            });
            document.getElementById('floatingContactWidget').addEventListener('click', function (e) {
                e.stopPropagation();
            });
        })();
    </script>
    @endif

    <!-- Mobile Bottom Navigation (hidden - using floating elements per design) -->
    <nav class="mobile-bottom-nav">
        <a href="{{ route('home') }}" class="bottom-nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            <span>Home</span>
        </a>
        <a href="#" class="bottom-nav-item bottom-nav-categories">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            <span>Categories</span>
        </a>
        <a href="#" class="bottom-nav-item cart-nav">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <span class="cart-badge">0</span>
            <span>Cart</span>
        </a>
        @if(Auth::guard('customer')->check())
            <a href="{{ route('customer.account') }}" class="bottom-nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>{{ Str::limit(Auth::guard('customer')->user()->name, 12) }}</span>
            </a>
        @else
            <a href="{{ route('customer.login') }}" class="bottom-nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>Login</span>
            </a>
        @endif
    </nav>

    <script src="{{ asset('public/frontEnd/js/bootstrap.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('public/backEnd/assets/js/toastr.min.js') }}"></script>
    <script>
        (function () {
            if (typeof toastr !== 'undefined') {
                var _to = @json(config('toastr.options', []));
                if (_to && typeof _to === 'object' && Object.keys(_to).length) {
                    toastr.options = _to;
                }
            }
        })();
    </script>
    {!! \Brian2694\Toastr\Facades\Toastr::message() !!}
    <script>
        jQuery(function ($) {
            @if (Session::has('success'))
                if (typeof toastr !== 'undefined') {
                    toastr.success(@json(Session::get('success')));
                }
            @endif
            @if (Session::has('error') && !Session::has('demo_mode_blocked'))
                if (typeof toastr !== 'undefined') {
                    toastr.error(@json(Session::get('error')));
                }
            @endif
            @if (Session::has('info'))
                if (typeof toastr !== 'undefined') {
                    toastr.info(@json(Session::get('info')));
                }
            @endif
            @if (Session::has('warning'))
                if (typeof toastr !== 'undefined') {
                    toastr.warning(@json(Session::get('warning')));
                }
            @endif
        });
    </script>
    <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
    <script>
        // Mega menu: position below header+nav
        (function() {
            function setMegaMenuTop() {
                var wrap = document.getElementById('headerNavWrap');
                if (wrap) {
                    document.documentElement.style.setProperty('--mega-menu-top', wrap.offsetHeight + 'px');
                }
            }
            setMegaMenuTop();
            window.addEventListener('resize', setMegaMenuTop);
        })();

        // Live Search
        (function() {
            var searchTimeout;
            $(document).on("keyup input", ".search_click", function () {
                var $input = $(this);
                var $resultBox = $input.closest(".search-section").find(".search_result");
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    var keyword = $input.val().trim();
                    if (!keyword) { $resultBox.empty(); return; }
                    $.ajax({
                        type: "GET",
                        data: { keyword: keyword },
                        url: "{{ route('livesearch') }}",
                        success: function (html) { $resultBox.html(html || ''); },
                        error: function() { $resultBox.empty(); }
                    });
                }, 200);
            });
            $(document).on("click", function(e) {
                if (!$(e.target).closest(".search-section").length) $(".search_result").empty();
            });
        })();

        // Sidebar cart (open/close/refresh) + fly-to-cart animation
        (function() {
            function syncCartBadges() {
                $.get("{{ route('mobile.cart.count') }}", function(c) {
                    var count = c || "0";
                    $(".mobilecart-qty").text(count);
                    $(".cart-badge").text(count);
                });
            }

            window.headerCartHoverRefresh = function() {
                var $cont = $('#headerCartHoverContent');
                if (!$cont.length) return;
                $.get("{{ route('cart.header-hover') }}", function(html) {
                    $cont.html(html || '');
                });
            };

            window.sidebarCartRefresh = function() {
                $.get("{{ route('cart.sidebar') }}", function(html) {
                    $("#sidebarCartContent").html(html || '');
                    if (typeof feather !== "undefined" && window.feather && typeof window.feather.replace === "function") {
                        window.feather.replace();
                    }
                    if (typeof window.headerCartHoverRefresh === "function") {
                        window.headerCartHoverRefresh();
                    }
                });
            };

            window.openSidebarCart = function() {
                var overlay = document.getElementById('sidebarCartOverlay');
                var drawer = document.getElementById('sidebarCartDrawer');
                if (overlay) overlay.classList.add('active');
                if (drawer) drawer.classList.add('active');
                document.body.style.overflow = 'hidden';
                if (typeof window.sidebarCartRefresh === 'function') window.sidebarCartRefresh();
            };

            window.closeSidebarCart = function() {
                var overlay = document.getElementById('sidebarCartOverlay');
                var drawer = document.getElementById('sidebarCartDrawer');
                if (overlay) overlay.classList.remove('active');
                if (drawer) drawer.classList.remove('active');
                document.body.style.overflow = '';
            };

            // Fly to cart animation helper
            window.runFlyToCart = function($sourceEl, onComplete) {
                var $flyImg = null;
                if ($sourceEl != null && typeof $sourceEl.jquery === 'undefined') {
                    $sourceEl = $($sourceEl);
                }

                if ($sourceEl && $sourceEl.length && $sourceEl.closest) {
                    // Try common selectors first (existing layouts)
                    $flyImg = $sourceEl.closest('.variant-modal-content').find('.variant-modal-img img').first();
                    if (!$flyImg || !$flyImg.length) {
                        // IMPORTANT: do NOT include `form` in `closest()` selector.
                        // `$sourceEl` is usually the cart form itself, and including `form`
                        // would stop at the form (no image inside), breaking fly-to-cart.
                        $flyImg = $sourceEl
                            .closest('#productDetailsTop, .product_item, .wist_item, .search-item, .quick-product, .product-section, .main-details-page, .details-action-box, .block__single')
                            .find('.pro_img img, .quick-product-img img, .details_slider img, .block__pic img, .dimage_item img')
                            .first();

                        // Fallback: take any image inside the same card container.
                        if (!$flyImg || !$flyImg.length) {
                            $flyImg = $sourceEl
                                .closest('#productDetailsTop, .product_item, .wist_item, .search-item, .quick-product, .product-section, .main-details-page, .details-action-box, .block__single')
                                .find('img')
                                .first();
                        }

                    // Final fallback: any image within parent divs near the submit button/form.
                    if (!$flyImg || !$flyImg.length) {
                        $flyImg = $sourceEl.parents('div').find('img').first();
                    }
                    }
                    // Try this project's flash/compact card selectors
                    if (!$flyImg || !$flyImg.length) {
                        $flyImg = $sourceEl
                            .closest('.flash-product-card, .flash-product-inner, .flash-product-actions, .flash-products-section')
                            .find('.flash-product-img-wrap img, img')
                            .first();
                    }
                    // Product details: Owl carousel — prefer visible/active slide (cloned/hidden slides break fly)
                    if (!$flyImg || !$flyImg.length) {
                        var $pdRoot = $sourceEl.closest('#productDetailsTop, .pd-modern.main-details-page');
                        if ($pdRoot.length) {
                            $flyImg = $pdRoot.find('.details_slider .owl-item.active img.block__pic, .details_slider .owl-item.center img.block__pic').first();
                        }
                        if ((!$flyImg || !$flyImg.length) && $pdRoot.length) {
                            $flyImg = $pdRoot.find('.details_slider .owl-item:not(.cloned) img.block__pic').first();
                        }
                        if ((!$flyImg || !$flyImg.length) && $pdRoot.length) {
                            $flyImg = $pdRoot.find('.pd-gallery-card img.block__pic').first();
                        }
                    }
                }

                if (!$flyImg || !$flyImg.length) {
                    if (typeof onComplete === 'function') onComplete();
                    return;
                }

                var rect = $flyImg[0].getBoundingClientRect();
                if (rect.width < 4 || rect.height < 4) {
                    var $pd2 = $sourceEl.closest('#productDetailsTop');
                    if ($pd2.length) {
                        var $alt = $pd2.find('.pd-gallery-card img.block__pic').filter(function() {
                            var r = this.getBoundingClientRect();
                            return r.width >= 4 && r.height >= 4;
                        }).first();
                        if ($alt.length) {
                            $flyImg = $alt;
                            rect = $flyImg[0].getBoundingClientRect();
                        }
                    }
                }
                var $clone = $flyImg.clone().addClass('fly-to-cart-img').css({
                    position: 'fixed',
                    width: 90,
                    height: 110,
                    left: rect.left,
                    top: rect.top,
                    margin: 0,
                    padding: 0,
                    zIndex: 99999
                }).appendTo('body');

                // Fly to sidebar cart widget (more robust: don't rely too much on :visible)
                var $target = $('#floatingCartBtn, .floating-cart-widget').first();
                if (!$target.length) {
                    $target = $('.mobile-bottom-nav .cart-nav').first();
                }
                if (!$target.length) {
                    $target = $('.cart-open-btn').first();
                }

                var destRect;
                if ($target && $target.length) {
                    destRect = $target[0].getBoundingClientRect();
                } else {
                    destRect = { left: $(window).width() - 60, top: $(window).height() / 2 - 40, width: 0, height: 0 };
                }

                var endW = 36, endH = 44;
                var endLeft = destRect.left + (destRect.width ? destRect.width / 2 - endW / 2 : 0);
                var endTop = destRect.top + (destRect.height ? destRect.height / 2 - endH / 2 : 0);

                // Direct animation (simpler path = always visible)
                $clone.animate(
                    { left: endLeft, top: endTop, width: endW, height: endH, opacity: 0.6 },
                    520,
                    'swing',
                    function() {
                        $clone.remove();
                        if ($target && $target.length) {
                            $target.addClass('cart-bump-animate');
                            setTimeout(function() { $target.removeClass('cart-bump-animate'); }, 450);
                        }
                        if (typeof onComplete === 'function') onComplete();
                    }
                );
            };

            // Open cart on floating widget / mobile cart button
            $(document).on('click', '#floatingCartBtn', function(e) {
                e.preventDefault();
                window.openSidebarCart();
            });
            $(document).on('click', '.cart-nav', function(e) {
                e.preventDefault();
                window.openSidebarCart();
            });

            // Cart actions inside sidebar
            $(document).on('click', '.cart_remove', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                if (!id) return;
                $.ajax({
                    type: 'GET',
                    data: { id: id },
                    url: "{{ route('cart.remove') }}",
                    success: function() {
                        syncCartBadges();
                        if (typeof window.sidebarCartRefresh === 'function') window.sidebarCartRefresh();
                    }
                });
            });
            $(document).on('click', '.cart_increment', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                if (!id) return;
                $.ajax({
                    type: 'GET',
                    data: { id: id },
                    url: "{{ route('cart.increment') }}",
                    success: function() {
                        syncCartBadges();
                        if (typeof window.sidebarCartRefresh === 'function') window.sidebarCartRefresh();
                    }
                });
            });
            $(document).on('click', '.cart_decrement', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                if (!id) return;
                $.ajax({
                    type: 'GET',
                    data: { id: id },
                    url: "{{ route('cart.decrement') }}",
                    success: function() {
                        syncCartBadges();
                        if (typeof window.sidebarCartRefresh === 'function') window.sidebarCartRefresh();
                    }
                });
            });

            // Intercept cart/store forms (add to cart + fly-to-cart)
            $(document).on('submit', "form[action*='cart/store']", function(e) {
                var $form = $(this);
                if ($form.hasClass('cart-ajax-submit')) return;
                e.preventDefault();

                var id = $form.find("input[name=id]").val();
                var qty = $form.find("input[name=qty]").val() || 1;
                var $submitter = (e.originalEvent && e.originalEvent.submitter)
                    ? $(e.originalEvent.submitter)
                    : $form;

                var hasOrderNow = false;
                if (e.originalEvent && e.originalEvent.submitter && e.originalEvent.submitter.name === 'order_now') {
                    hasOrderNow = true;
                } else {
                    hasOrderNow = !!$form.find("input[name=order_now]").val();
                }

                $form.addClass('cart-ajax-submit');
                $.ajax({
                    type: 'POST',
                    data: $form.serialize(),
                    url: $form.attr('action'),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(data) {
                        if (data && data.reload) {
                            window.location.reload();
                            return;
                        }
                        if (data && data.success) {
                            if (data.tracking) {
                                var t = data.tracking;
                                window.dataLayer = window.dataLayer || [];
                                window.dataLayer.push({ ecommerce: null });
                                window.dataLayer.push({
                                    event: 'add_to_cart',
                                    ecommerce: {
                                        currency: t.currency || 'BDT',
                                        value: t.value,
                                        items: [{
                                            item_id: String(t.id),
                                            item_name: t.item_name,
                                            price: t.price,
                                            quantity: t.quantity
                                        }]
                                    }
                                });
                                if (typeof fbq === 'function') {
                                    fbq('track', 'AddToCart', {
                                        content_ids: [String(t.id)],
                                        content_type: 'product',
                                        value: t.value,
                                        currency: t.currency || 'BDT',
                                        num_items: t.quantity
                                    }, { eventID: t.event_id });
                                }
                                if (typeof ttq !== 'undefined') {
                                    ttq.track('AddToCart', {
                                        content_id: String(t.id),
                                        content_type: 'product',
                                        content_name: t.item_name,
                                        value: t.value,
                                        currency: t.currency || 'BDT',
                                        quantity: t.quantity
                                    });
                                }
                            }
                            syncCartBadges();
                            if (typeof window.sidebarCartRefresh === 'function') window.sidebarCartRefresh();

                            if (hasOrderNow) {
                                window.location.href = "{{ route('customer.checkout') }}";
                            } else {
                                window.runFlyToCart($submitter, function() {
                                    window.openSidebarCart();
                                });
                            }
                        } else {
                            var errTitle = (data && data.title) ? data.title : '';
                            var errMsg = (data && data.message) ? data.message : '';
                            if (errMsg && typeof toastr !== 'undefined') {
                                toastr.error(errMsg, errTitle || 'Error');
                            } else {
                                $form[0].submit();
                            }
                        }
                    },
                    error: function(xhr) {
                        var d = xhr.responseJSON;
                        if (d && d.reload) {
                            window.location.reload();
                            return;
                        }
                        if (d && d.message && typeof toastr !== 'undefined') {
                            toastr.error(d.message, d.title || 'Error');
                            return;
                        }
                        $form[0].submit();
                    },
                    complete: function() {
                        $form.removeClass('cart-ajax-submit');
                    }
                });
            });

            // Initial sync (avoid stale badge counts)
            syncCartBadges();
        })();
    </script>
    <script>
        // Mobile drawer accordion (Main cat -> subcat -> childcat)
        (function() {
            document.addEventListener('click', function(e) {
                var btn = e.target.closest('.mobile-nav-toggle');
                if (!btn) return;
                var targetSel = btn.getAttribute('data-target');
                if (!targetSel) return;
                var target = document.querySelector(targetSel);
                if (!target) return;
                var opened = target.classList.toggle('open');
                btn.textContent = opened ? '−' : '+';
            });
        })();
    </script>
    <script>
        // Slider
        const slides = document.querySelectorAll('.hero-section .slide');
        const dots = document.querySelectorAll('.hero-section .carousel-dots span');
        const track = document.querySelector('.hero-section .slider-track');
        let current = 0;

        function goToSlide(i) {
            current = i;
            const percent = slides.length ? (current * 100 / slides.length) : 0;
            if (track) track.style.transform = `translateX(-${percent}%)`;
            dots.forEach((d, j) => d.classList.toggle('active', j === current));
        }

        if (track && dots.length) {
            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => goToSlide(i));
            });
            setInterval(() => {
                current = (current + 1) % slides.length;
                goToSlide(current);
            }, 4000);
        }

        // Mobile menu
        const menuBtn = document.getElementById('mobileMenuBtn');
        const menuDrawer = document.getElementById('mobileNavDrawer');
        const menuOverlay = document.getElementById('mobileMenuOverlay');
        const menuClose = document.getElementById('mobileNavClose');

        function openMenu() {
            menuDrawer.classList.add('open');
            menuOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeMenu() {
            menuDrawer.classList.remove('open');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (menuBtn) menuBtn.addEventListener('click', openMenu);
        if (menuClose) menuClose.addEventListener('click', closeMenu);
        if (menuOverlay) menuOverlay.addEventListener('click', closeMenu);

        // Bottom nav "Categories" should open the mobile categories drawer
        var bottomCats = document.querySelector('.mobile-bottom-nav .bottom-nav-categories');
        if (bottomCats) {
            bottomCats.addEventListener('click', function(ev) {
                ev.preventDefault();
                openMenu();
            });
        }

        document.querySelectorAll('.mobile-nav-links a').forEach(link => {
            link.addEventListener('click', closeMenu);
        });

        // Countdown helper for Flash/Hot deal timers
        (function() {
            function mountCountdown(sectionId, dataAttr, ids) {
                var sec = document.getElementById(sectionId);
                if (!sec || !sec.getAttribute(dataAttr)) return;
                var end = new Date(sec.getAttribute(dataAttr)).getTime();
                if (isNaN(end)) return;
                function pad(n) { return n < 10 ? '0' + n : String(n); }
                function tick() {
                    var t = Math.max(0, end - Date.now());
                    var h = Math.floor(t / 3600000);
                    var m = Math.floor((t % 3600000) / 60000);
                    var s = Math.floor((t % 60000) / 1000);
                    var eh = document.getElementById(ids.h), em = document.getElementById(ids.m), es = document.getElementById(ids.s);
                    if (eh) eh.textContent = pad(h);
                    if (em) em.textContent = pad(m);
                    if (es) es.textContent = pad(s);
                }
                tick();
                setInterval(tick, 1000);
            }

            mountCountdown('flash-sale-section', 'data-flash-end', { h: 'fcH', m: 'fcM', s: 'fcS' });
            mountCountdown('hot-deal-section', 'data-hot-end', { h: 'hdH', m: 'hdM', s: 'hdS' });
        })();

        // Flash Sale — Owl Carousel (same idea as index flash_sale_slider; swipe + arrows)
        $(function() {
            var $el = $('.flash-sale-master');
            // Important: don't return early when flash-sale doesn't exist.
            // Category pages often only have `[data-category-slider]`, and Owl Carousel
            // would otherwise never initialize (leaving carousel hidden).
            if ($el.length && typeof $.fn.owlCarousel !== 'undefined') {
                $el.owlCarousel({
                    margin: 12,
                    loop: $el.find('.item').length > 6,
                    dots: false,
                    nav: true,
                    touchDrag: true,
                    mouseDrag: true,
                    pullDrag: true,
                    navText: ['<span class="flash-owl-nav-inner" aria-hidden="true">‹</span>', '<span class="flash-owl-nav-inner" aria-hidden="true">›</span>'],
                    autoplay: false,
                    responsive: {
                        0: { items: 2, nav: true },
                        480: { items: 3, nav: true },
                        768: { items: 4, nav: true },
                        992: { items: 5, nav: true },
                        1200: { items: 6, nav: true }
                    }
                });
            }

            if (typeof $.fn.owlCarousel === 'undefined') return;

            $('[data-category-slider]').each(function() {
                var $slider = $(this);
                $slider.owlCarousel({
                    margin: 12,
                    loop: $slider.find('.item').length > 6,
                    dots: false,
                    nav: true,
                    touchDrag: true,
                    mouseDrag: true,
                    pullDrag: true,
                    navText: ['<span class="flash-owl-nav-inner" aria-hidden="true">‹</span>', '<span class="flash-owl-nav-inner" aria-hidden="true">›</span>'],
                    autoplay: false,
                    responsive: {
                        0: { items: 2, nav: true },
                        480: { items: 3, nav: true },
                        768: { items: 4, nav: true },
                        992: { items: 5, nav: true },
                        1200: { items: 6, nav: true }
                    }
                });
            });
        });
    </script>
    {{-- Category / subcategory / child: mobile filter drawer --}}
    <script>
        $(function() {
            $(document).on('click', '.filter_btn', function() {
                $('.filter_sidebar').addClass('active');
            });
            $(document).on('click', '.filter_close', function() {
                $('.filter_sidebar').removeClass('active');
            });
        });
    </script>

    {{-- Duplicate order limit alert (session from cart/store) — SweetAlert2 --}}
    @if(session('show_order_limit_modal'))
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var whatsappNumber = "{{ preg_replace('/\D+/', '', (string)(optional($contact ?? null)->whatsapp ?? optional($contact ?? null)->hotline ?? optional($contact ?? null)->phone ?? '8801700000000')) }}";
            if (!whatsappNumber) whatsappNumber = "8801700000000";

            Swal.fire({
                title: '',
                html: `
                <div class="custom-modal-content">
                    <div class="modal-header-custom">
                        <div class="header-left">
                            <i class="fas fa-exclamation-triangle header-icon"></i>
                            <span>Duplicate Order Detective Alert</span>
                        </div>
                        <i class="fas fa-times close-icon" onclick="Swal.close()"></i>
                    </div>

                    <div class="modal-body-custom">
                        <p>
                            <img src="https://img.icons8.com/emoji/48/000000/warning-emoji.png" style="width: 20px; vertical-align: text-bottom;">
                            <b>সতর্কতা!</b> আপনি ইতিমধ্যে এই পণ্যটির জন্য অর্ডার দিয়েছেন। নির্দিষ্ট সময়ের মধ্যে একই পণ্যের পুনরায় অর্ডার দেওয়া অনুমোদিত নয়।
                            👉 আপনি যদি সত্যিই আবার অর্ডার করতে চান, তাহলে নিচে দেওয়া WhatsApp নম্বরে যোগাযোগ করুন:
                        </p>
                    </div>

                    <div class="modal-footer-custom">
                        <a href="https://wa.me/${whatsappNumber}?text=${encodeURIComponent('আমি একই পণ্য পুনরায় অর্ডার করতে চাই, অনুগ্রহ করে সাহায্য করুন।')}" target="_blank" rel="noopener" class="btn-whatsapp-custom">
                            <i class="fab fa-whatsapp"></i> CONTACT ON WHATSAPP
                        </a>
                        <button type="button" onclick="Swal.close()" class="btn-close-custom">Close</button>
                    </div>
                </div>
            `,
                showConfirmButton: false,
                background: 'transparent',
                customClass: {
                    popup: 'swal-no-padding'
                },
                allowOutsideClick: false
            });
        });
    </script>

    <style>
        .swal-no-padding {
            padding: 0 !important;
            background: none !important;
            box-shadow: none !important;
            overflow: visible !important;
        }
        .custom-modal-content {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            font-family: 'Arial', sans-serif;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            max-width: 500px;
            margin: 0 auto;
        }
        .modal-header-custom {
            background-color: #b91c1c;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-icon {
            color: #facc15;
            font-size: 20px;
        }
        .close-icon {
            cursor: pointer;
            opacity: 0.8;
            font-size: 20px;
            transition: 0.2s;
        }
        .close-icon:hover { opacity: 1; }
        .modal-body-custom {
            padding: 30px 25px;
            text-align: left;
            font-size: 15px;
            line-height: 1.6;
            color: #4b5563;
        }
        .modal-footer-custom {
            padding: 0 25px 30px 25px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .btn-whatsapp-custom {
            background-color: #10b981;
            color: white !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background 0.3s;
            border: none;
        }
        .btn-whatsapp-custom:hover { background-color: #059669; }
        .btn-close-custom {
            background-color: #dc2626;
            color: white;
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 14px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background 0.3s;
        }
        .btn-close-custom:hover { background-color: #b91c1c; }
        @media (max-width: 450px) {
            .modal-footer-custom { flex-direction: column; }
            .btn-whatsapp-custom, .btn-close-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    @endif

    @include('frontEnd.layouts.partials.marketing_popup')

    @stack('script')
</body>
</html>
