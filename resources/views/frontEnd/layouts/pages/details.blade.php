@extends('frontEnd.layouts.master')
@php
    $pageType = 'product';
    // CAPI (FrontendController) এর মতোই মূল্য — ব্রাউজার ও সার্ভার মিল রাখতে
    $detailTrackPrice = (float) ($details->new_price ?? $details->old_price ?? 0);
    // SEO: প্রোডাক্টে সেট করা মেটা ফিল্ড (খালি হলে ফলব্যাক)
    $metaTitle = filled($details->meta_title) ? $details->meta_title : $details->name;
    $metaDescription = filled($details->meta_description)
        ? $details->meta_description
        : Str::limit(strip_tags($details->description ?? ''), 160);
    $metaKeywords = filled($details->meta_keywords) ? $details->meta_keywords : $details->name;
    $metaImage = $details->meta_image
        ? asset($details->meta_image)
        : asset(optional($details->image)->image);
@endphp
@section('title', $metaTitle)
@push('dataLayer')
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
    event: 'view_item',
    ecommerce: {
        currency: 'BDT',
        value: {{ $detailTrackPrice }},
        items: [{
            item_id: '{{ $details->id }}',
            item_name: '{{ addslashes($details->name) }}',
            price: {{ $detailTrackPrice }},
            item_category: '{{ addslashes(optional($details->category)->name ?? '') }}',
            quantity: 1
        }]
    }
});
if (typeof fbq === 'function') {
    fbq('track', 'ViewContent', {
        content_ids: ['{{ $details->id }}'],
        content_name: '{{ addslashes($details->name) }}',
        content_type: 'product',
        value: {{ $detailTrackPrice }},
        currency: 'BDT'
    }, { eventID: '{{ $vc_event_id }}' });
}
if (typeof ttq !== 'undefined') {
    ttq.track('ViewContent', {
        content_type: 'product',
        content_id: '{{ $details->id }}',
        content_name: '{{ addslashes($details->name) }}',
        value: {{ $detailTrackPrice }},
        currency: 'BDT'
    });
}
@endpush
@push('seo')
<link rel="canonical" href="{{ route('product', $details->slug) }}" />

<meta name="app-url" content="{{ route('product', $details->slug) }}" />
<meta name="robots" content="index, follow" />

{{-- মূল HTML টাইটেল = @section('title') + মাস্টারে সাইট নাম; এখানে মেটা ট্যাগ একই মান --}}
<meta name="title" content="{{ $metaTitle }}" />
<meta name="description" content="{{ $metaDescription }}" />
<meta name="keywords" content="{{ $metaKeywords }}" />

<!-- Twitter Card data -->
<meta name="twitter:card" content="summary_large_image" />
@if(filled(optional($generalsetting)->twitter))
<meta name="twitter:site" content="{{ Str::startsWith($generalsetting->twitter, '@') ? $generalsetting->twitter : '@' . ltrim($generalsetting->twitter, '@') }}" />
@endif
<meta name="twitter:title" content="{{ $metaTitle }}" />
<meta name="twitter:description" content="{{ $metaDescription }}" />
<meta name="twitter:image" content="{{ $metaImage }}" />

<!-- Open Graph data -->
<meta property="og:title" content="{{ $metaTitle }}" />
<meta property="og:type" content="product" />
<meta property="og:url" content="{{ route('product', $details->slug) }}" />
<meta property="og:image" content="{{ $metaImage }}" />
<meta property="og:description" content="{{ $metaDescription }}" />
<meta property="og:site_name" content="{{ $generalsetting->name ?? config('app.name', 'Shop') }}" />
@endpush


@push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/zoomsl.css') }}">
<style>
.pd-modern.main-details-page {
    --brand-primary: {{ optional($generalsetting)->primary_color ?? '#e11d74' }};
}
/* ✅ Scoped Review Section */
.gomobd-review-section {
    font-family: 'Poppins', sans-serif;
}

/* Title */
.gomobd-review-section .gomobd-review-title {
    font-size: 20px;
    color: #222;
}

/* Review Card */
.gomobd-review-section .gomobd-review-card {
    background: #fff;
    border: 1px solid #e6e6e6;
    border-radius: 10px;
    padding: 16px 20px;
    transition: all 0.3s ease-in-out;
}
.gomobd-review-section .gomobd-review-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Header */
.gomobd-review-section .gomobd-review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

/* Avatar */
.gomobd-review-section .gomobd-review-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #198754;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 600;
    margin-right: 12px;
}

/* Name + Date */
.gomobd-review-section .gomobd-review-meta {
    flex-grow: 1;
}
.gomobd-review-section .gomobd-review-name {
    font-size: 16px;
    margin: 0;
    color: #222;
}
.gomobd-review-section .gomobd-review-date {
    font-size: 13px;
    color: #888;
}

/* Stars */
.gomobd-review-section .gomobd-review-stars {
    color: #f8b400;
    font-size: 15px;
}

/* Review Text */
.gomobd-review-section .gomobd-review-body {
    margin-top: 10px;
    color: #555;
    font-size: 15px;
    line-height: 1.6;
}

/* Empty state */
.gomobd-review-section .gomobd-review-empty {
    background: #f9f9f9;
    border-radius: 10px;
    color: #777;
}

/* ✅ Simple Wholesale Pricing Styles */
.wholesale-tier-row:hover {
    background: #f0f8f0 !important;
}

.wholesale-tier-row.active-tier {
    background: #d4edda !important;
    border-left: 3px solid #28a745 !important;
}

/* Review Modal: z-index fix যাতে ব্যাকড্রপের পিছনে না পড়ে এবং ক্লিক/টাইপ কাজ করে */
#exampleModal {
    z-index: 10055 !important;
}
#exampleModal .modal-dialog {
    z-index: 10056 !important;
}

/* ========== Product details — modern layout (ক্যাটাগরি/হোম স্টাইলের সাথে মিল) ========== */
.pd-modern {
    padding: 28px 0 24px;
    background: #f1f5f9;
}
.pd-modern-inner { max-width: 1200px; }
.pd-breadcrumb-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px 8px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 16px;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
}
.pd-breadcrumb-bar a {
    color: #64748b;
    text-decoration: none;
}
.pd-breadcrumb-bar a:hover { color: var(--brand-primary, #e11d74); }
.pd-breadcrumb-bar .pd-bc-sep { color: #cbd5e1; margin: 0 2px; }
.pd-breadcrumb-bar .pd-bc-current { color: #0f172a; font-weight: 800; }

.pd-hero-row { align-items: flex-start; }
.pd-gallery-card,
.pd-info-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px;
    height: 100%;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
}
.pd-gallery-card { position: relative; }
.pd-gallery-card .product-details-discount-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 3;
}
.pd-gallery-card .sale-badge,
.pd-gallery-card .sale-badge-inner {
    width: auto;
    height: auto;
}
.pd-gallery-card .sale-badge-box {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--brand-primary, #e11d74);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
}
.pd-gallery-card .sale-badge-text {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    line-height: 1.15;
    text-align: center;
    margin: 0;
}
.pd-gallery-card .sale-badge-text p {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
}
.pd-gallery-card .details_slider .block__pic {
    border-radius: 12px;
    width: 100%;
    height: auto;
    max-height: 420px;
    object-fit: contain;
    background: #fafafa;
}
.pd-gallery-card .dimage_item {
    display: flex;
    align-items: center;
    justify-content: center;
}
.pd-gallery-card .indicator_thumb {
    margin-top: 14px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}
.pd-gallery-card .indicator-item {
    width: 64px;
    height: 64px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.pd-gallery-card .indicator-item:hover,
.pd-gallery-card .indicator-item.active {
    border-color: var(--brand-primary, #e11d74);
    box-shadow: 0 0 0 2px rgba(225, 29, 116, 0.15);
}
.pd-gallery-card .indicator-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pd-info-card .details_right { padding: 0; }
.pd-info-card .product { margin: 0; }
.pd-info-card .product-cart { padding: 0; border: none; }
.pd-info-card .name {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.35;
    margin-bottom: 10px;
}
.pd-info-card .details-price {
    margin-bottom: 12px;
    font-size: 1rem;
}
.pd-info-card .details-price del {
    color: #94a3b8;
    margin-right: 8px;
}
.pd-info-card .details-price #newPrice {
    font-size: 1.55rem;
    font-weight: 800;
    color: var(--brand-primary, #e11d74);
}
.pd-info-card .details-ratting-wrapper {
    margin-bottom: 12px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
}
.pd-info-card .details-ratting-wrapper .fa-star,
.pd-info-card .details-ratting-wrapper .fa-star-half-alt { color: #f59e0b; }
.pd-info-card .all-reviews-button {
    font-size: 13px;
    font-weight: 700;
    color: var(--brand-primary, #e11d74);
    margin-left: 4px;
}
.pd-info-card .product-code p,
.pd-info-card .pro_brand p {
    font-size: 14px;
    color: #475569;
    margin-bottom: 6px;
}
.pd-info-card .product-code span { font-weight: 700; color: #334155; }

.pd-qty-row { margin-top: 8px; margin-bottom: 12px; }
.pd-info-card .quantity {
    display: inline-flex;
    align-items: center;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    background: #f8fafc;
}
.pd-info-card .quantity .minus,
.pd-info-card .quantity .plus {
    width: 40px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: 700;
    font-size: 18px;
    color: var(--brand-primary, #e11d74);
    user-select: none;
    background: #fff;
}
.pd-info-card .quantity input[name="qty"] {
    width: 48px;
    border: none;
    text-align: center;
    font-weight: 700;
    background: transparent;
    font-size: 16px;
}

.pd-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 8px;
    margin-bottom: 12px;
}
.pd-info-card .add_cart_btn,
.pd-info-card .order_now_btn {
    flex: 1;
    min-width: 140px;
    border-radius: 10px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    padding: 12px 16px !important;
    border: none !important;
    cursor: pointer;
    transition: filter 0.2s;
}
.pd-info-card .order_now_btn {
    background: var(--brand-primary, #e11d74) !important;
    color: #fff !important;
}
.pd-info-card .add_cart_btn {
    background: #fff !important;
    color: var(--brand-primary, #e11d74) !important;
    border: 2px solid var(--brand-primary, #e11d74) !important;
}
.pd-info-card .add_cart_btn:hover,
.pd-info-card .order_now_btn:hover { filter: brightness(1.06); }

.pd-support-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 10px;
    margin-top: 8px;
}
@media (min-width: 576px) {
    .pd-support-grid { grid-template-columns: 1fr 1fr; }
}
.pd-support-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    border: none;
}
.pd-support-btn--phone {
    background: #0d9488;
    color: #fff !important;
}
.pd-support-btn--wa {
    background: #22c55e;
    color: #fff !important;
}
.pd-desc-nav-wrap {
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 50;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}
.pd-desc-nav-wrap .description-nav { padding: 12px 0; }
.pd-desc-nav-wrap .desc-nav-ul {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    list-style: none;
    margin: 0;
    padding: 0;
    justify-content: center;
}
.pd-desc-nav-wrap .desc-nav-ul a {
    display: inline-block;
    padding: 10px 18px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 14px;
    color: #64748b;
    background: #f1f5f9;
    text-decoration: none;
    border: 1px solid transparent;
}
.pd-desc-nav-wrap .desc-nav-ul a:hover {
    color: var(--brand-primary, #e11d74);
    background: #fff;
    border-color: #e2e8f0;
}

.pd-details-body {
    padding: 28px 0 40px;
    background: #f8fafc;
}
.pd-details-body .description.tab-content,
.pd-details-body .details-action-box {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 22px 24px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}
.pd-details-body .description h2,
.pd-details-body .pro_vide h2 {
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--brand-primary, #e11d74);
}
.pd-details-body .pro_vide {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    height: auto;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
    position: static;
    top: auto;
}
.pd-details-body .pd-video-embed {
    position: relative;
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    background: #0f172a;
    aspect-ratio: 16 / 9;
    min-height: 280px;
}
@media (min-width: 576px) {
    .pd-details-body .pd-video-embed { min-height: 360px; }
}
@media (min-width: 992px) {
    .pd-details-body .pd-video-embed { min-height: 520px; }
}
.pd-details-body .pd-video-embed iframe,
.pd-details-body .pd-video-embed video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: 0;
    border-radius: 12px;
    object-fit: contain;
}

.pd-related-wrap {
    padding: 8px 0 40px;
    background: #f1f5f9;
}
.pd-related-wrap .category-products-section {
    margin-bottom: 0;
}

.pd-desc-nav-wrap > .container,
.pd-details-body > .container,
.pd-related-wrap > .container {
    max-width: 1200px;
}

/* ========== Variants: Color & Size (modern / professional) ========== */
.pd-variant-block {
    margin-bottom: 18px;
    padding: 18px 18px 20px;
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}
.pd-variant-head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px 16px;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e2e8f0;
}
.pd-variant-title-row {
    display: flex;
    align-items: center;
    gap: 10px;
}
.pd-variant-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}
.pd-variant-block--color .pd-variant-icon {
    background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
    color: #be185d;
}
.pd-variant-block--size .pd-variant-icon {
    background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    color: #4338ca;
}
.pd-variant-title {
    margin: 0;
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    line-height: 1.25;
}
.pd-variant-sub {
    margin: 2px 0 0;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
}
.pd-variant-pick {
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    text-align: right;
    max-width: 100%;
}
.pd-variant-pick strong {
    color: #0f172a;
    font-weight: 800;
}
.pd-swatch-input {
    position: absolute;
    opacity: 0;
    width: 1px;
    height: 1px;
    margin: 0;
    clip: rect(0, 0, 0, 0);
}
.pd-color-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 14px 12px;
    align-items: flex-start;
}
.pd-swatch-item {
    position: relative;
    max-width: 92px;
}
.pd-swatch-row {
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 4px 2px 2px;
    border-radius: 12px;
    transition: background 0.2s ease;
}
.pd-swatch-row:hover {
    background: rgba(15, 23, 42, 0.03);
}
.pd-swatch-visual {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 2px solid rgba(15, 23, 42, 0.14);
    background: var(--pd-swatch, #e2e8f0);
    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.35),
        0 2px 8px rgba(15, 23, 42, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.pd-swatch-row:hover .pd-swatch-visual {
    transform: scale(1.06);
    border-color: rgba(15, 23, 42, 0.22);
    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.4),
        0 6px 16px rgba(15, 23, 42, 0.12);
}
.pd-swatch-check {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 14px;
    opacity: 0;
    transform: scale(0.5);
    transition: opacity 0.2s ease, transform 0.2s ease;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.45);
    pointer-events: none;
}
.pd-swatch-input:checked + .pd-swatch-row .pd-swatch-check {
    opacity: 1;
    transform: scale(1);
}
.pd-swatch-input:focus-visible + .pd-swatch-row .pd-swatch-visual {
    outline: 2px solid var(--brand-primary, #e11d74);
    outline-offset: 3px;
}
.pd-swatch-input:checked + .pd-swatch-row .pd-swatch-visual {
    border-color: var(--brand-primary, #e11d74);
    box-shadow:
        0 0 0 3px rgba(225, 29, 116, 0.22),
        0 4px 14px rgba(15, 23, 42, 0.12);
    transform: scale(1.05);
}
.pd-swatch-input:checked + .pd-swatch-row .pd-swatch-name {
    color: var(--brand-primary, #e11d74);
}
.pd-swatch-name {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    text-align: center;
    line-height: 1.25;
    max-width: 92px;
    word-break: break-word;
    transition: color 0.2s ease;
}
.pd-size-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.pd-size-grid > div {
    position: relative;
    display: inline-flex;
}
.pd-size-input {
    position: absolute;
    opacity: 0;
    width: 1px;
    height: 1px;
    margin: 0;
    clip: rect(0, 0, 0, 0);
}
.pd-size-label {
    cursor: pointer;
    min-height: 46px;
    min-width: 48px;
    padding: 10px 18px;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    background: #fff;
    color: #334155;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.02em;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: border-color 0.2s ease, background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}
.pd-size-label:hover {
    border-color: var(--brand-primary, #e11d74);
    color: var(--brand-primary, #e11d74);
    box-shadow: 0 4px 14px rgba(225, 29, 116, 0.12);
    transform: translateY(-1px);
}
.pd-size-input:focus-visible + .pd-size-label {
    outline: 2px solid var(--brand-primary, #e11d74);
    outline-offset: 2px;
}
.pd-size-input:checked + .pd-size-label {
    background: linear-gradient(135deg, var(--brand-primary, #e11d74) 0%, #c2185b 100%);
    border-color: transparent;
    color: #fff !important;
    box-shadow: 0 6px 18px rgba(225, 29, 116, 0.35);
    transform: translateY(-1px);
}

@media (max-width: 991.98px) {
    .pd-modern { padding-top: 18px; }
    .pd-info-card { margin-top: 4px; }
}
</style>
@endpush

@section('content')
<section class="pd-modern main-details-page" id="productDetailsTop">
    <div class="container pd-modern-inner">
        <nav class="pd-breadcrumb-bar" aria-label="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            @if(optional($details->category)->slug)
                <span class="pd-bc-sep">/</span>
                <a href="{{ route('category', $details->category->slug) }}">{{ $details->category->name }}</a>
            @endif
            @if($details->subcategory && $details->subcategory->slug)
                <span class="pd-bc-sep">/</span>
                <a href="{{ route('subcategory', $details->subcategory->slug) }}">{{ $details->subcategory->subcategoryName }}</a>
            @endif
            @if($details->childcategory && $details->childcategory->slug)
                <span class="pd-bc-sep">/</span>
                <a href="{{ route('products', $details->childcategory->slug) }}">{{ $details->childcategory->childcategoryName }}</a>
            @endif
            <span class="pd-bc-sep">/</span>
            <span class="pd-bc-current">{{ Str::limit($details->name, 48) }}</span>
        </nav>

        <div class="row g-3 g-lg-4 pd-hero-row">
            <div class="col-lg-6 position-relative">
                <div class="pd-gallery-card">
                                @if($details->old_price)
                                <div class="product-details-discount-badge">
                                    <div class="sale-badge">
                                        <div class="sale-badge-inner">
                                            <div class="sale-badge-box">
                                                <span class="sale-badge-text">
                                                    <p> @php $discount=(((($details->old_price)-($details->new_price))*100) / ($details->old_price)) @endphp {{ number_format($discount, 0) }}%</p>
                                                    ছাড়
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="details_slider owl-carousel" id="details_slider_main">
                                    @foreach ($details->images as $value)
                                        <div class="dimage_item" data-color-id="{{ $value->color_id ?? '' }}">
                                            <img src="{{ asset($value->image) }}" class="block__pic" />
                                        </div>
                                    @endforeach
                                </div>
                                <div
                                    class="indicator_thumb @if ($details->images->count() > 4) thumb_slider owl-carousel @endif" id="indicator_thumb_wrapper">
                                    @foreach ($details->images as $key => $image)
                                        <div class="indicator-item" data-id="{{ $key }}" data-color-id="{{ $image->color_id ?? '' }}">
                                            <img src="{{ asset($image->image) }}" />
                                        </div>
                                    @endforeach
                                </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="pd-info-card">
                                <div class="details_right">

                                    <div class="product">
                                        <div class="product-cart">
                                            <p class="name">{{ $details->name }}</p>
                                            <p class="details-price">
                                                @if ($details->old_price)
                                                    <del>৳{{ $details->old_price }}</del>
                                                @endif <span id="newPrice">৳{{ $details->new_price }}</span>

                                            </p>
                                            <div class="details-ratting-wrapper">
                                            @php
                                                $averageRating = $reviews->avg('ratting');
                                                $filledStars = floor($averageRating);
                                                $emptyStars = 5 - $filledStars;
                                            @endphp
                                            
                                            @if ($averageRating >= 0 && $averageRating <= 5)
                                                @for ($i = 1; $i <= $filledStars; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                            
                                                @if ($averageRating == $filledStars)
                                                    {{-- If averageRating is an integer, don't display half star --}}
                                                @else
                                                    <i class="far fa-star-half-alt"></i>
                                                @endif
                                            
                                                @for ($i = 1; $i <= $emptyStars; $i++)
                                                    <i class="far fa-star"></i>
                                                @endfor
                                            
                                                <span>{{ number_format($averageRating, 2) }}/5</span>
                                            @else
                                                <span>Invalid rating range</span>
                                            @endif
                                            <a class="all-reviews-button" href="#writeReview">See Reviews</a>
                                            </div>
                                            <div class="product-code">
                                                <p><span>প্রোডাক্ট কোড : </span>{{ $details->product_code }}</p>
                                            </div>

                                            {{-- ⭐⭐ এখানে Product Type দেখানো হচ্ছে ⭐⭐ --}}
                                            @php
                                                $productTypeText = $details->is_digital
                                                    ? 'Digital'
                                                    : 'Physical';
                                            @endphp
                                            <div class="pro_brand">
                                                <p>
                                                  Product Type: {{ $productTypeText }}
                                                </p>
                                            </div>
                                            {{-- ⭐⭐ Product Type End ⭐⭐ --}}

                                            {{-- ⭐⭐ Wholesale Pricing Tiers - Simple Clean Design ⭐⭐ --}}
                                            @if($details->is_wholesale && $details->wholesalePrices && $details->wholesalePrices->count() > 0)
                                            <div class="wholesale-pricing-section" style="margin: 20px 0;">
                                                <h5 style="margin-bottom: 15px; font-size: 16px; font-weight: 600; color: #333;">
                                                    <i class="fa fa-tag me-2"></i> Wholesale Pricing
                                                </h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover mb-0" style="background: #fff;">
                                                        <thead style="background: #f8f9fa;">
                                                            <tr>
                                                                <th style="padding: 12px; font-size: 14px; font-weight: 600;">Quantity</th>
                                                                <th style="padding: 12px; font-size: 14px; font-weight: 600;">Price</th>
                                                                <th style="padding: 12px; font-size: 14px; font-weight: 600;">Stock</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($details->wholesalePrices->sortBy('min_quantity') as $tier)
                                                            <tr class="wholesale-tier-row" 
                                                                data-min-qty="{{ $tier->min_quantity }}" 
                                                                data-max-qty="{{ $tier->max_quantity ?? 999999 }}" 
                                                                data-price="{{ $tier->wholesale_price }}"
                                                                style="cursor: pointer; transition: background 0.2s;">
                                                                <td style="padding: 12px; font-size: 14px;">
                                                                    {{ $tier->min_quantity }}{{ $tier->max_quantity ? ' - ' . $tier->max_quantity : '+' }} pcs
                                                                </td>
                                                                <td style="padding: 12px; font-size: 14px; font-weight: 600; color: #28a745;">
                                                                    ৳{{ number_format($tier->wholesale_price, 2) }}
                                                                </td>
                                                                <td style="padding: 12px; font-size: 14px; color: {{ ($tier->stock ?? 0) > 0 ? '#28a745' : '#dc3545' }};">
                                                                    {{ $tier->stock ?? 0 }} pcs
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <p class="text-muted mt-2 mb-0" style="font-size: 12px;">
                                                    <i class="fa fa-info-circle me-1"></i> Quantity select করলে wholesale price automatically apply হবে
                                                </p>
                                            </div>
                                            @endif
                                            {{-- ⭐⭐ Wholesale Pricing End ⭐⭐ --}}

                                            <form action="{{ route('cart.store') }}" method="POST" name="formName" class="ajax-cart-form" id="productDetailsCartForm" onsubmit="return handleDetailsCartSubmit(event)">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $details->id }}" />


{{-- ✅ Variant-based Color & Size (with your old design style) --}}
@if ($details->variantPrices->count() > 0)
    @php
        $productcolors = $details->variantPrices->pluck('color')->unique('id')->filter();
        $productsizes = $details->variantPrices->pluck('size')->unique('id')->filter();
    @endphp

    {{-- 🎨 Color — modern swatches --}}
    @if ($productcolors->count() > 0)
        <div class="pd-variant-block pd-variant-block--color" id="pdVariantColor">
            <div class="pd-variant-head">
                <div>
                    <div class="pd-variant-title-row">
                        <span class="pd-variant-icon" aria-hidden="true"><i class="fa fa-palette"></i></span>
                        <div>
                            <h3 class="pd-variant-title" id="pdColorHeading">রং নির্বাচন করুন</h3>
                            <p class="pd-variant-sub">Color — গ্যালারি আপনার নির্বাচন অনুযায়ী আপডেট হবে</p>
                        </div>
                    </div>
                </div>
                <div class="pd-variant-pick">
                    <span class="d-none d-sm-inline">নির্বাচিত: </span>
                    <strong id="pdPickColorValue">—</strong>
                </div>
            </div>
            <div class="pd-color-grid" role="radiogroup" aria-labelledby="pdColorHeading">
                @foreach ($productcolors as $procolor)
                    @php
                        $colorHex = $procolor->color ?? '#cbd5e1';
                        $colorLabel = method_exists($procolor, 'getDisplayName')
                            ? ($procolor->getDisplayName() ?? $procolor->colorName ?? $procolor->color_name ?? 'রং')
                            : ($procolor->colorName ?? $procolor->color_name ?? 'রং');
                    @endphp
                    <div class="pd-swatch-item">
                        <input type="radio"
                            id="fc-option{{ $procolor->id }}"
                            value="{{ $procolor->id }}"
                            name="product_color"
                            class="pd-swatch-input selector-item_radio emptyalert"
                            data-option-label="{{ e($colorLabel) }}"
                            required
                            aria-label="{{ $colorLabel }}" />
                        <label for="fc-option{{ $procolor->id }}" class="pd-swatch-row" title="{{ $colorLabel }}">
                            <span class="pd-swatch-visual" style="--pd-swatch: {{ $colorHex }};">
                                <span class="pd-swatch-check" aria-hidden="true"><i class="fa fa-check"></i></span>
                            </span>
                            <span class="pd-swatch-name">{{ $colorLabel }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- 📏 Size — chip style --}}
    @if ($productsizes->count() > 0)
        <div class="pd-variant-block pd-variant-block--size" id="pdVariantSize">
            <div class="pd-variant-head">
                <div>
                    <div class="pd-variant-title-row">
                        <span class="pd-variant-icon" aria-hidden="true"><i class="fa fa-text-height"></i></span>
                        <div>
                            <h3 class="pd-variant-title" id="pdSizeHeading">সাইজ নির্বাচন করুন</h3>
                            <p class="pd-variant-sub">Size &amp; variant — আপনার সঠিক সাইজ বেছে নিন</p>
                        </div>
                    </div>
                </div>
                <div class="pd-variant-pick">
                    <span class="d-none d-sm-inline">নির্বাচিত: </span>
                    <strong id="pdPickSizeValue">—</strong>
                </div>
            </div>
            <div class="pd-size-grid" role="radiogroup" aria-labelledby="pdSizeHeading">
                @foreach ($productsizes as $prosize)
                    @php
                        $sizeLabel = $prosize->sizeName ?? $prosize->name ?? 'সাইজ';
                    @endphp
                    <div>
                        <input type="radio"
                            id="f-option{{ $prosize->id }}"
                            value="{{ $prosize->id }}"
                            name="product_size"
                            class="pd-size-input selector-item_radio emptyalert"
                            data-option-label="{{ e($sizeLabel) }}"
                            required
                            aria-label="{{ $sizeLabel }}" />
                        <label for="f-option{{ $prosize->id }}" class="pd-size-label">{{ $sizeLabel }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endif





                                                        @if ($details->pro_unit)
                                                            <div class="pro_unig">
                                                                <label>Unit: {{ $details->pro_unit }}</label>
                                                                <input type="hidden" name="pro_unit"
                                                                    value="{{ $details->pro_unit }}" />
                                                            </div>
                                                        @endif
                                                        <div class="pro_brand">
                                                            <p>Brand :
                                                                {{ $details->brand ? $details->brand->name : 'N/A' }}
                                                            </p>
                                                        </div>

                                                        <div class="row pd-qty-row">
                                                            <div class="qty-cart col-sm-12">
                                                                <div class="quantity">
                                                                    <span class="minus">-</span>
                                                                    <input type="text" name="qty"
                                                                        value="1" />
                                                                    <span class="plus">+</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="pd-actions single_product d-flex flex-wrap">
                                                                    <input type="submit" class="add_cart_btn" onclick="return sendSuccess();" name="add_cart" value="কার্টে যোগ করুন" />
                                                                    <input type="submit" class="order_now_btn order_now_btn_m" onclick="return sendSuccess();" name="order_now" value="অর্ডার করুন" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="pd-support-grid">
                                                            <a class="pd-support-btn pd-support-btn--phone call_now_btn"
                                                                href="tel:{{ preg_replace('/\s+/', '', $contact->hotline ?? '') }}">
                                                                <i class="fa fa-phone-square"></i>
                                                                {{ $contact->hotline }}
                                                            </a>
                                                            <a class="pd-support-btn pd-support-btn--wa call_now_btn"
                                                                href="https://api.whatsapp.com/send?phone={{ $contact->whatsapp }}&text=হ্যালো, আমি এই পণ্যটির ব্যাপারে জানতে চাই: {{ urlencode(Request::url()) }}"
                                                                target="_blank" rel="noopener">
                                                                <i class="fa fa-whatsapp"></i>
                                                                WhatsApp
                                                            </a>
                                                        </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        </div>
    </div>
</section>

<div class="description-nav-wrapper pd-desc-nav-wrap">
    <div class="container">
        <div class="row">

            <div class="col-sm-12">
                <div class="description-nav">
                    <ul class="desc-nav-ul">
                        {{-- <li class="active">
                            <a href="#specification" target="_self">Specification</a>
                        </li> --}}
                        <li>
                            <a href="#description" target="_self">Description</a>
                        </li>
                        {{-- <li>
                            <a href="#question" target="_self">Questions (0)</a>
                        </li> --}}
                        <li>
                            <a href="#writeReview" target="_self">Reviews ({{ $reviews->count() }}) </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="pro_details_area pd-details-body">
    @php
        $videoType = $details->pro_video_type ?? ($details->pro_video ? 'youtube' : null);
        $youtubeRaw = trim((string) ($details->pro_video ?? ''));
        $youtubeId = null;

        if ($youtubeRaw !== '') {
            // Support both plain YouTube ID and full YouTube URL.
            if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $youtubeRaw)) {
                $youtubeId = $youtubeRaw;
            } elseif (preg_match('/(?:v=|\/embed\/|youtu\.be\/|\/shorts\/)([a-zA-Z0-9_-]{11})/', $youtubeRaw, $m)) {
                $youtubeId = $m[1];
            }
        }

        $hasYoutubeVideo = $videoType === 'youtube' && !empty($youtubeId);
        $hasUploadedVideo = $videoType === 'upload' && !empty($details->pro_video_path);
        $hasVideo = $hasYoutubeVideo || $hasUploadedVideo;
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="description tab-content details-action-box" id="description">
                    <h2>বিস্তারিত</h2>
                    <p>{!! $details->description !!}</p>
                </div>
                @if($hasVideo)
                <div class="pro_vide details-action-box mt-3 mb-3" id="productVideo">
                    <h2>ভিডিও</h2>
                    @if($hasYoutubeVideo)
                    <div class="pd-video-embed">
                        <iframe
                            src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0"
                            title="YouTube video player"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </div>
                    @elseif($hasUploadedVideo)
                    <div class="pd-video-embed">
                        <video controls playsinline>
                            <source src="{{ asset($details->pro_video_path) }}" type="video/mp4">
                            <source src="{{ asset($details->pro_video_path) }}" type="video/webm">
                            <source src="{{ asset($details->pro_video_path) }}" type="video/ogg">
                            আপনার ব্রাউজার ভিডিও সাপোর্ট করে না।
                        </video>
                    </div>
                    @else
                    <div class="alert alert-light mb-0">ভিডিও পাওয়া যায়নি।</div>
                    @endif
                </div>
                @endif
                <div class="tab-content details-action-box" id="writeReview">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                
							  
							  
							  
							<section class="gomobd-review-section mt-5" id="reviewsList">
    <div class="gomobd-review-header d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 class="gomobd-review-title fw-bold mb-2 mb-md-0">
            Customer Reviews ({{ $reviews->count() }})
        </h3>
        <button type="button" class="gomobd-review-btn btn btn-success btn-sm"
            data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fa fa-edit me-1"></i> Write a Review
        </button>
    </div>

    @if ($reviews->count() > 0)
    <div class="gomobd-review-list row g-3">
        @foreach ($reviews as $review)
        <div class="col-12">
            <div class="gomobd-review-card shadow-sm">
                <div class="gomobd-review-card-header d-flex justify-content-between align-items-start flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="gomobd-review-avatar">
                            {{ strtoupper(substr($review->name, 0, 1)) }}
                        </div>
                        <div class="gomobd-review-meta">
                            <h6 class="gomobd-review-name">{{ $review->name }}</h6>
                            <small class="gomobd-review-date">{{ $review->created_at->format('d M Y') }}</small>
                        </div>
                    </div>
                    <div class="gomobd-review-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->ratting)
                                <i class="fa-solid fa-star"></i>
                            @else
                                <i class="fa-regular fa-star"></i>
                            @endif
                        @endfor
                    </div>
                </div>
                <div class="gomobd-review-body mt-2">
                    <p><i class="fa-regular fa-comment-dots text-success me-1"></i> {{ $review->review }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="gomobd-review-empty text-center py-5">
        <i class="fa fa-clipboard-list fs-1 text-muted mb-3"></i>
        <p>This product has no reviews yet.<br><strong>Be the first one to write a review.</strong></p>
    </div>
    @endif
</section>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($products->count() > 0)
<div class="pd-related-wrap">
    <div class="container pd-modern-inner">
        <section class="category-products-section">
            <div class="category-products-head">
                <h2 class="category-products-title">সম্পর্কিত পণ্য</h2>
            </div>
            <div class="category-products-slider">
                @foreach ($products as $key => $value)
                    <div class="item">
                        @include('frontEnd.layouts.partials.product_card_compact', ['value' => $value])
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endif

{{-- Review Modal: body এর কাছাকাছি রাখা হয়েছে যাতে z-index/stacking issue না হয় --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Your review</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="insert-review">
                    @if (Auth::guard('customer')->user())
                        <form action="{{ route('customer.review') }}" id="review-form" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $details->id }}">
                            <div class="fz-12 mb-2">
                                <div class="rating">
                                    <label title="Excelent">☆ <input required type="radio" name="ratting" value="5" /></label>
                                    <label title="Best">☆ <input required type="radio" name="ratting" value="4" /></label>
                                    <label title="Better">☆ <input required type="radio" name="ratting" value="3" /></label>
                                    <label title="Very Good">☆ <input required type="radio" name="ratting" value="2" /></label>
                                    <label title="Good">☆ <input required type="radio" name="ratting" value="1" /></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Message:</label>
                                <textarea required class="form-control radius-lg" name="review" id="message-text"></textarea>
                                <span id="validation-message" style="color: red;"></span>
                            </div>
                            <div class="form-group">
                                <button class="details-review-button" type="submit">Submit Review</button>
                            </div>
                        </form>
                    @else
                        <a class="customer-login-redirect" href="{{ route('customer.login') }}">Login to Post Your Review</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection @push('script')
<script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>

<script src="{{ asset('public/frontEnd/js/zoomsl.min.js') }}"></script>
<script>
    // Review modal কে body তে সরিয়ে দেওয়া - #content এর z-index:1 এর জন্য modal backdrop এর পিছনে পড়ছিল
    document.addEventListener('DOMContentLoaded', function() {
        var m = document.getElementById('exampleModal');
        if (m && document.body) document.body.appendChild(m);
    });
</script>
<script>
    const variants = @json($details->variantPrices);

    @if($details->is_wholesale && $details->wholesalePrices && $details->wholesalePrices->count() > 0)
    var wholesaleTiers = [
        @foreach($details->wholesalePrices->sortBy('min_quantity') as $tier)
        {
            min_quantity: {{ $tier->min_quantity }},
            max_quantity: {{ $tier->max_quantity ?? 999999 }},
            price: {{ $tier->wholesale_price }}
        }@if(!$loop->last),@endif
        @endforeach
    ];
    var regularPrice = {{ $details->new_price }};

    function getWholesalePrice(qty) {
        for (var i = 0; i < wholesaleTiers.length; i++) {
            if (qty >= wholesaleTiers[i].min_quantity && qty <= wholesaleTiers[i].max_quantity) {
                return wholesaleTiers[i].price;
            }
        }
        return null;
    }
    @endif

    function updateVariantPrice() {
        let color = $("input[name='product_color']:checked").val() || null;
        let size  = $("input[name='product_size']:checked").val() || null;

        let match = null;

        // ✅ color + size (both selected)
        if (color && size) {
            match = variants.find(v => {
                let vColorId = v.color_id ?? v.color;
                let vSizeId = v.size_id ?? v.size;
                return String(vColorId) == String(color) && String(vSizeId) == String(size);
            });
        }

        // ✅ only color (no size selected)
        if (!match && color && !size) {
            match = variants.find(v => {
                let vColorId = v.color_id ?? v.color;
                let vSizeId = v.size_id ?? v.size;
                return String(vColorId) == String(color) && (vSizeId === null || vSizeId === '');
            });
        }

        // ✅ only size (no color selected)
        if (!match && size && !color) {
            match = variants.find(v => {
                let vColorId = v.color_id ?? v.color;
                let vSizeId = v.size_id ?? v.size;
                return String(vSizeId) == String(size) && (vColorId === null || vColorId === '');
            });
        }

        // ✅ update UI
        let basePrice = parseFloat({{ $details->new_price }});
        if (match && match.price !== undefined && match.price !== null) {
            // Variant price is the actual price for this color/size combination
            basePrice = parseFloat(match.price);
        }

        // Apply wholesale price if applicable (wholesale price overrides variant price)
        @if($details->is_wholesale && $details->wholesalePrices && $details->wholesalePrices->count() > 0)
        let qty = parseInt($("input[name='qty']").val()) || 1;
        let wholesalePrice = getWholesalePrice(qty);
        if (wholesalePrice !== null) {
            basePrice = parseFloat(wholesalePrice);
        }
        @endif

        $('#newPrice').text('৳' + basePrice.toFixed(2));
    }

    function refreshVariantPickLabels() {
        var $c = $("input[name='product_color']:checked");
        var $s = $("input[name='product_size']:checked");
        if ($("#pdPickColorValue").length) {
            $("#pdPickColorValue").text($c.length ? ($c.attr("data-option-label") || "—") : "—");
        }
        if ($("#pdPickSizeValue").length) {
            $("#pdPickSizeValue").text($s.length ? ($s.attr("data-option-label") || "—") : "—");
        }
    }

    $(document).ready(function() {
        updateVariantPrice();
        refreshVariantPickLabels();
    });

    $(document).on(
        'change',
        "input[name='product_color'], input[name='product_size']",
        function() {
            updateVariantPrice();
            refreshVariantPickLabels();
        }
    );

    // কালার সিলেক্ট করলে ঐ কালারের ইমেজ দেখাবে
    var productImages = @json($details->images->map(function($img) {
        return ['src' => asset($img->image), 'color_id' => $img->color_id];
    }));

    function updateImagesByColor(colorId) {
        var colorIdStr = colorId ? String(colorId) : null;
        var filteredImages = [];

        if (colorIdStr) {
            var colorSpecific = productImages.filter(function(img) {
                return img.color_id && String(img.color_id) === colorIdStr;
            });
            var defaultImages = productImages.filter(function(img) { return !img.color_id; });
            filteredImages = colorSpecific.length > 0 ? colorSpecific : defaultImages;
        } else {
            filteredImages = productImages.filter(function(img) { return !img.color_id; });
            if (filteredImages.length === 0) filteredImages = productImages;
        }
        if (filteredImages.length === 0) filteredImages = productImages;

        var $slider = $(".details_slider");
        var owl = $slider.data("owl.carousel");
        if (owl) owl.destroy();

        var sliderHtml = filteredImages.map(function(img, i) {
            return '<div class="dimage_item"><img src="' + img.src + '" class="block__pic" /></div>';
        }).join('');
        $slider.html(sliderHtml);

        var thumbHtml = filteredImages.map(function(img, i) {
            return '<div class="indicator-item" data-id="' + i + '"><img src="' + img.src + '" /></div>';
        }).join('');
        var $thumbWrapper = $("#indicator_thumb_wrapper");
        var thumbOwl = $thumbWrapper.data("owl.carousel");
        if (thumbOwl) thumbOwl.destroy();
        $thumbWrapper.removeClass("thumb_slider owl-carousel").html(thumbHtml);
        if (filteredImages.length > 4) {
            $thumbWrapper.addClass("thumb_slider owl-carousel");
            $thumbWrapper.owlCarousel({ margin: 15, items: 4, loop: true, dots: false, nav: true, autoplayTimeout: 6000, autoplayHoverPause: true });
        }

        $slider.owlCarousel({
            margin: 15,
            items: 1,
            loop: filteredImages.length > 1,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
        });

        $(".indicator-item").off("click").on("click", function() {
            var slideIndex = parseInt($(this).data("id"), 10);
            $slider.trigger("to.owl.carousel", slideIndex);
        });

        if ($(".block__pic").length && typeof $(".block__pic").imagezoomsl === "function") {
            $(".block__pic").imagezoomsl({ zoomrange: [3, 3] });
        }
    }

    $(document).on("change", "input[name='product_color']", function() {
        updateImagesByColor($(this).val() || null);
    });
</script>



<script>
    $(document).ready(function() {
        $(".details_slider").owlCarousel({
            margin: 15,
            items: 1,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
        });
        $(".indicator-item").on("click", function() {
            var slideIndex = $(this).data("id");
            $(".details_slider").trigger("to.owl.carousel", slideIndex);
        });
    });
</script>
<!--Data Layer Start-->
<script type="text/javascript">
    window.dataLayer = window.dataLayer || [];
    dataLayer.push({
        ecommerce: null
    });
    dataLayer.push({
        event: "view_item",
        ecommerce: {
            items: [{
                item_name: "{{ $details->name }}",
                item_id: "{{ $details->id }}",
                price: "{{ $details->new_price }}",
                item_brand: "{{ $details->brand?$details->brand->name:'' }}",
                item_category: "{{ $details->category->name }}",
                item_variant: "{{ $details->pro_unit }}",
                currency: "BDT",
                quantity: {{ $details->stock ?? 0 }}
            }],
            impression: [
                @foreach ($products as $value)
                    {
                        item_name: "{{ $value->name }}",
                        item_id: "{{ $value->id }}",
                        price: "{{ $value->new_price }}",
                        item_brand: "{{ $details->brand?$details->brand->name:'' }}",
                        item_category: "{{ $value->category ? $value->category->name : '' }}",
                        item_variant: "{{ $value->pro_unit }}",
                        currency: "BDT",
                        quantity: {{ $value->stock ?? 0 }}
                    },
                @endforeach
            ]
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#add_to_cart').click(function() {
            gtag("event", "add_to_cart", {
                currency: "BDT",
                value: "1.5",
                items: [
                    @foreach (Cart::instance('shopping')->content() as $cartInfo)
                        {
                            item_id: "{{$details->id}}",
                            item_name: "{{$details->name}}",
                            price: "{{$details->new_price}}",
                            currency: "BDT",
                            quantity: {{ $cartInfo->qty ?? 0 }}
                        },
                    @endforeach
                ]
            });
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#order_now').click(function() {
            gtag("event", "add_to_cart", {
                currency: "BDT",
                value: "1.5",
                items: [
                    @foreach (Cart::instance('shopping')->content() as $cartInfo)
                        {
                            item_id: "{{$details->id}}",
                            item_name: "{{$details->name}}",
                            price: "{{$details->new_price}}",
                            currency: "BDT",
                            quantity: {{ $cartInfo->qty ?? 0 }}
                        },
                    @endforeach
                ]
            });
        });
    });
</script>

<!-- Data Layer End-->

{{-- 🔹 নতুন dataLayer + Facebook Pixel ইভেন্ট (আগের কিছু না কেটে শুধু যোগ করা) --}}
<script type="text/javascript">
    window.dataLayer = window.dataLayer || [];

    (function () {

        var productItem = {
            item_id: "{{ $details->id }}",
            item_name: @json($details->name),
            price: {{ (float) $details->new_price }},
            item_brand: @json(optional($details->brand)->name),
            item_category: @json(optional($details->category)->name),
            item_variant: @json($details->pro_unit),
            currency: "BDT",
            quantity: {{ $details->stock ?? 0 }}
        };

        var relatedItems = [
            @foreach ($products as $value)
            {
                item_id: "{{ $value->id }}",
                item_name: @json($value->name),
                price: {{ (float) $value->new_price }},
                item_brand: @json(optional($value->brand)->name),
                item_category: @json(optional($value->category)->name),
                item_variant: @json($value->pro_unit),
                currency: "BDT",
                quantity: {{ $value->stock ?? 0 }}
            }@if(!$loop->last),@endif
            @endforeach
        ];

        // view_item_list (Related products)
        if (relatedItems.length) {
            window.dataLayer.push({
                event: "view_item_list",
                ecommerce: {
                    item_list_name: "Related Products",
                    currency: "BDT",
                    items: relatedItems
                }
            });
        }

        // Facebook Pixel: ViewContent
        if (typeof fbq === "function") {
            fbq("track", "ViewContent", {
                content_ids: [productItem.item_id],
                content_name: productItem.item_name,
                content_category: productItem.item_category,
                value: productItem.price,
                currency: "BDT"
            });
        }

        // Helper: qty সহ item তৈরি
        function buildCurrentItem() {
            var qtyInput = document.querySelector("input[name='qty']");
            var qty = parseInt(qtyInput ? qtyInput.value : "1", 10);
            if (isNaN(qty) || qty < 1) qty = 1;

            return {
                item_id: productItem.item_id,
                item_name: productItem.item_name,
                price: productItem.price,
                item_brand: productItem.item_brand,
                item_category: productItem.item_category,
                item_variant: productItem.item_variant,
                currency: "BDT",
                quantity: qty
            };
        }

        // "কার্টে যোগ করুন" -> add_to_cart + FB AddToCart
        $(document).on("click", ".add_cart_btn", function () {
            var item  = buildCurrentItem();
            var value = item.price * item.quantity;

            window.dataLayer.push({ ecommerce: null });
            window.dataLayer.push({
                event: "add_to_cart",
                ecommerce: {
                    currency: "BDT",
                    value: value,
                    items: [item]
                }
            });

            if (typeof fbq === "function") {
                fbq("track", "AddToCart", {
                    content_ids: [item.item_id],
                    content_name: item.item_name,
                    value: value,
                    currency: "BDT",
                    contents: [
                        { id: item.item_id, quantity: item.quantity }
                    ]
                });
            }
        });

        // "অর্ডার করুন" -> add_to_cart + begin_checkout + FB InitiateCheckout
        $(document).on("click", ".order_now_btn", function () {
            var item  = buildCurrentItem();
            var value = item.price * item.quantity;

            // GA4 add_to_cart
            window.dataLayer.push({ ecommerce: null });
            window.dataLayer.push({
                event: "add_to_cart",
                ecommerce: {
                    currency: "BDT",
                    value: value,
                    items: [item]
                }
            });

            // GA4 begin_checkout
            window.dataLayer.push({
                event: "begin_checkout",
                ecommerce: {
                    currency: "BDT",
                    value: value,
                    items: [item]
                }
            });

            // FB Pixel
            if (typeof fbq === "function") {
                fbq("track", "AddToCart", {
                    content_ids: [item.item_id],
                    content_name: item.item_name,
                    value: value,
                    currency: "BDT",
                    contents: [
                        { id: item.item_id, quantity: item.quantity }
                    ]
                });

                fbq("track", "InitiateCheckout", {
                    value: value,
                    currency: "BDT",
                    num_items: item.quantity
                });
            }
        });

    })();
</script>

<script>
    $(document).ready(function() {
        $(".minus").click(function() {
            var $input = $(this).parent().find("input");
            var count = parseInt($input.val()) - 1;
            count = count < 1 ? 1 : count;
            $input.val(count);
            $input.change();
            return false;
        });
        $(".plus").click(function() {
            var $input = $(this).parent().find("input");
            $input.val(parseInt($input.val()) + 1);
            $input.change();
            return false;
        });

        // Wholesale Price Update on Quantity Change - Modern Card Design
        @if($details->is_wholesale && $details->wholesalePrices && $details->wholesalePrices->count() > 0)
        var wholesaleTiers = [
            @foreach($details->wholesalePrices->sortBy('min_quantity') as $tier)
            {
                min_quantity: {{ $tier->min_quantity }},
                max_quantity: {{ $tier->max_quantity ?? 999999 }},
                price: {{ $tier->wholesale_price }}
            }@if(!$loop->last),@endif
            @endforeach
        ];
        var regularPrice = {{ $details->new_price }};

        function updatePriceBasedOnQuantity() {
            var qty = parseInt($("input[name='qty']").val()) || 1;
            var selectedPrice = regularPrice;
            var matchedTier = null;

            // Find matching wholesale tier
            for (var i = 0; i < wholesaleTiers.length; i++) {
                if (qty >= wholesaleTiers[i].min_quantity && qty <= wholesaleTiers[i].max_quantity) {
                    selectedPrice = wholesaleTiers[i].price;
                    matchedTier = wholesaleTiers[i];
                    break;
                }
            }

            // Update price display
            $('#newPrice').text('৳' + selectedPrice.toFixed(2));

            // Highlight matching tier row
            $('.wholesale-tier-row').removeClass('active-tier');
            if (matchedTier) {
                $('.wholesale-tier-row').each(function() {
                    var minQty = parseInt($(this).data('min-qty'));
                    var maxQty = parseInt($(this).data('max-qty'));
                    if (qty >= minQty && qty <= maxQty) {
                        $(this).addClass('active-tier');
                    }
                });
            }
        }

        // Update price when quantity changes
        $("input[name='qty']").on('change keyup', function() {
            updatePriceBasedOnQuantity();
        });

        // Click on tier row to set quantity to minimum
        $('.wholesale-tier-row').on('click', function() {
            var minQty = parseInt($(this).data('min-qty'));
            $("input[name='qty']").val(minQty).trigger('change');
        });

        // Initial price update
        updatePriceBasedOnQuantity();
        @endif
    });
</script>

<script>
    function sendSuccess() {
        var form = document.forms["formName"];
        if (!form) return true;
        var sizeEl = form["product_size"];
        var colorEl = form["product_color"];
        if (sizeEl) {
            var size = sizeEl.value || "";
            if (size === "") {
                toastr.warning("Please select any size");
                return false;
            }
        }
        if (colorEl) {
            var color = colorEl.value || "";
            if (color === "") {
                toastr.error("Please select any color");
                return false;
            }
        }
        return true;
    }
    function handleDetailsCartSubmit(event) {
        if (!sendSuccess()) return false;
        event.preventDefault();
        var form = document.getElementById("productDetailsCartForm");
        if (!form || form.classList.contains("cart-ajax-submit")) return false;
        var isOrderNow = event.submitter && event.submitter.name === "order_now";
        // অ্যাজাক্স কলব্যাকে event.submitter কখনও হারিয়ে যেতে পারে — এখানে ধরে রাখা
        var $flySource = (event.submitter && event.submitter.name === "add_cart")
            ? $(event.submitter)
            : $(form);
        form.classList.add("cart-ajax-submit");
        var formData = new FormData(form);
        if (isOrderNow) formData.append("order_now", "1");
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "{{ route('cart.store') }}");
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').content);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.onload = function() {
            form.classList.remove("cart-ajax-submit");
            try {
                var data = JSON.parse(xhr.responseText);
                if (data && data.reload) {
                    window.location.reload();
                    return;
                }
                if (data && data.success) {
                    if (typeof toastr !== "undefined") toastr.success("কার্টে যোগ হয়েছে!", "সফল");
                    if (typeof cart_count === "function") cart_count();
                    if (typeof mobile_cart === "function") mobile_cart();
                    if (typeof sidebarCartRefresh === "function") sidebarCartRefresh();
                    if (isOrderNow) {
                        window.location.href = "{{ route('customer.checkout') }}";
                    } else {
                        if (typeof runFlyToCart === "function") {
                            runFlyToCart($flySource, function() {
                                if (typeof openSidebarCart === "function") openSidebarCart();
                            });
                        } else if (typeof openSidebarCart === "function") {
                            openSidebarCart();
                        }
                    }
                } else {
                    var errTitle = (data && data.title) ? data.title : "";
                    var errMsg = (data && data.message) ? data.message : "";
                    if (errMsg && typeof toastr !== "undefined") {
                        toastr.error(errMsg, errTitle || "Error");
                    } else {
                        form.submit();
                    }
                }
            } catch (e) {
                form.submit();
            }
        };
        xhr.onerror = function() {
            form.classList.remove("cart-ajax-submit");
            form.submit();
        };
        var params = new URLSearchParams(formData).toString();
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(params);
        return false;
    }
</script>
<script>
    $(document).ready(function() {
        $(".rating label").click(function() {
            $(".rating label").removeClass("active");
            $(this).addClass("active");
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".thumb_slider").owlCarousel({
            margin: 15,
            items: 4,
            loop: true,
            dots: false,
            nav: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
        });
    });
</script>

<script type="text/javascript">
    $(".block__pic").imagezoomsl({
        zoomrange: [3, 3]
    });
</script>
@endpush
