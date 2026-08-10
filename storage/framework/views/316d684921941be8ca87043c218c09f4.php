<?php
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
?>
<?php $__env->startSection('title', $metaTitle); ?>
<?php $__env->startPush('dataLayer'); ?>
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
    event: 'view_item',
    ecommerce: {
        currency: 'BDT',
        value: <?php echo e($detailTrackPrice); ?>,
        items: [{
            item_id: '<?php echo e($details->id); ?>',
            item_name: '<?php echo e(addslashes($details->name)); ?>',
            price: <?php echo e($detailTrackPrice); ?>,
            item_category: '<?php echo e(addslashes(optional($details->category)->name ?? '')); ?>',
            quantity: 1
        }]
    }
});
if (typeof fbq === 'function') {
    fbq('track', 'ViewContent', {
        content_ids: ['<?php echo e($details->id); ?>'],
        content_name: '<?php echo e(addslashes($details->name)); ?>',
        content_type: 'product',
        value: <?php echo e($detailTrackPrice); ?>,
        currency: 'BDT'
    }, { eventID: '<?php echo e($vc_event_id); ?>' });
}
if (typeof ttq !== 'undefined') {
    ttq.track('ViewContent', {
        content_type: 'product',
        content_id: '<?php echo e($details->id); ?>',
        content_name: '<?php echo e(addslashes($details->name)); ?>',
        value: <?php echo e($detailTrackPrice); ?>,
        currency: 'BDT'
    });
}
<?php $__env->stopPush(); ?>
<?php $__env->startPush('seo'); ?>
<link rel="canonical" href="<?php echo e(route('product', $details->slug)); ?>" />

<meta name="app-url" content="<?php echo e(route('product', $details->slug)); ?>" />
<meta name="robots" content="index, follow" />


<meta name="title" content="<?php echo e($metaTitle); ?>" />
<meta name="description" content="<?php echo e($metaDescription); ?>" />
<meta name="keywords" content="<?php echo e($metaKeywords); ?>" />

<!-- Twitter Card data -->
<meta name="twitter:card" content="summary_large_image" />
<?php if(filled(optional($generalsetting)->twitter)): ?>
<meta name="twitter:site" content="<?php echo e(Str::startsWith($generalsetting->twitter, '@') ? $generalsetting->twitter : '@' . ltrim($generalsetting->twitter, '@')); ?>" />
<?php endif; ?>
<meta name="twitter:title" content="<?php echo e($metaTitle); ?>" />
<meta name="twitter:description" content="<?php echo e($metaDescription); ?>" />
<meta name="twitter:image" content="<?php echo e($metaImage); ?>" />

<!-- Open Graph data -->
<meta property="og:title" content="<?php echo e($metaTitle); ?>" />
<meta property="og:type" content="product" />
<meta property="og:url" content="<?php echo e(route('product', $details->slug)); ?>" />
<meta property="og:image" content="<?php echo e($metaImage); ?>" />
<meta property="og:description" content="<?php echo e($metaDescription); ?>" />
<meta property="og:site_name" content="<?php echo e($generalsetting->name ?? config('app.name', 'Shop')); ?>" />
<?php $__env->stopPush(); ?>


<?php $__env->startPush('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('public/frontEnd/css/zoomsl.css')); ?>">
<style>
.pd-modern.main-details-page {
    --brand-primary: <?php echo e(optional($generalsetting)->primary_color ?? '#e11d74'); ?>;
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<section class="pd-modern main-details-page" id="productDetailsTop">
    <div class="container pd-modern-inner">
        <nav class="pd-breadcrumb-bar" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>">Home</a>
            <?php if(optional($details->category)->slug): ?>
                <span class="pd-bc-sep">/</span>
                <a href="<?php echo e(route('category', $details->category->slug)); ?>"><?php echo e($details->category->name); ?></a>
            <?php endif; ?>
            <?php if($details->subcategory && $details->subcategory->slug): ?>
                <span class="pd-bc-sep">/</span>
                <a href="<?php echo e(route('subcategory', $details->subcategory->slug)); ?>"><?php echo e($details->subcategory->subcategoryName); ?></a>
            <?php endif; ?>
            <?php if($details->childcategory && $details->childcategory->slug): ?>
                <span class="pd-bc-sep">/</span>
                <a href="<?php echo e(route('products', $details->childcategory->slug)); ?>"><?php echo e($details->childcategory->childcategoryName); ?></a>
            <?php endif; ?>
            <span class="pd-bc-sep">/</span>
            <span class="pd-bc-current"><?php echo e(Str::limit($details->name, 48)); ?></span>
        </nav>

        <div class="row g-3 g-lg-4 pd-hero-row">
            <div class="col-lg-6 position-relative">
                <div class="pd-gallery-card">
                                <?php if($details->old_price): ?>
                                <div class="product-details-discount-badge">
                                    <div class="sale-badge">
                                        <div class="sale-badge-inner">
                                            <div class="sale-badge-box">
                                                <span class="sale-badge-text">
                                                    <p> <?php $discount=(((($details->old_price)-($details->new_price))*100) / ($details->old_price)) ?> <?php echo e(number_format($discount, 0)); ?>%</p>
                                                    ছাড়
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="details_slider owl-carousel" id="details_slider_main">
                                    <?php $__currentLoopData = $details->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="dimage_item" data-color-id="<?php echo e($value->color_id ?? ''); ?>">
                                            <img src="<?php echo e(asset($value->image)); ?>" class="block__pic" />
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div
                                    class="indicator_thumb <?php if($details->images->count() > 4): ?> thumb_slider owl-carousel <?php endif; ?>" id="indicator_thumb_wrapper">
                                    <?php $__currentLoopData = $details->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="indicator-item" data-id="<?php echo e($key); ?>" data-color-id="<?php echo e($image->color_id ?? ''); ?>">
                                            <img src="<?php echo e(asset($image->image)); ?>" />
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="pd-info-card">
                                <div class="details_right">

                                    <div class="product">
                                        <div class="product-cart">
                                            <p class="name"><?php echo e($details->name); ?></p>
                                            <p class="details-price">
                                                <?php if($details->old_price): ?>
                                                    <del>৳<?php echo e($details->old_price); ?></del>
                                                <?php endif; ?> <span id="newPrice">৳<?php echo e($details->new_price); ?></span>

                                            </p>
                                            <div class="details-ratting-wrapper">
                                            <?php
                                                $averageRating = $reviews->avg('ratting');
                                                $filledStars = floor($averageRating);
                                                $emptyStars = 5 - $filledStars;
                                            ?>
                                            
                                            <?php if($averageRating >= 0 && $averageRating <= 5): ?>
                                                <?php for($i = 1; $i <= $filledStars; $i++): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php endfor; ?>
                                            
                                                <?php if($averageRating == $filledStars): ?>
                                                    
                                                <?php else: ?>
                                                    <i class="far fa-star-half-alt"></i>
                                                <?php endif; ?>
                                            
                                                <?php for($i = 1; $i <= $emptyStars; $i++): ?>
                                                    <i class="far fa-star"></i>
                                                <?php endfor; ?>
                                            
                                                <span><?php echo e(number_format($averageRating, 2)); ?>/5</span>
                                            <?php else: ?>
                                                <span>Invalid rating range</span>
                                            <?php endif; ?>
                                            <a class="all-reviews-button" href="#writeReview">See Reviews</a>
                                            </div>
                                            <div class="product-code">
                                                <p><span>প্রোডাক্ট কোড : </span><?php echo e($details->product_code); ?></p>
                                            </div>

                                            
                                            <?php
                                                $productTypeText = $details->is_digital
                                                    ? 'Digital'
                                                    : 'Physical';
                                            ?>
                                            <div class="pro_brand">
                                                <p>
                                                  Product Type: <?php echo e($productTypeText); ?>

                                                </p>
                                            </div>
                                            

                                            
                                            <?php if($details->is_wholesale && $details->wholesalePrices && $details->wholesalePrices->count() > 0): ?>
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
                                                            <?php $__currentLoopData = $details->wholesalePrices->sortBy('min_quantity'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr class="wholesale-tier-row" 
                                                                data-min-qty="<?php echo e($tier->min_quantity); ?>" 
                                                                data-max-qty="<?php echo e($tier->max_quantity ?? 999999); ?>" 
                                                                data-price="<?php echo e($tier->wholesale_price); ?>"
                                                                style="cursor: pointer; transition: background 0.2s;">
                                                                <td style="padding: 12px; font-size: 14px;">
                                                                    <?php echo e($tier->min_quantity); ?><?php echo e($tier->max_quantity ? ' - ' . $tier->max_quantity : '+'); ?> pcs
                                                                </td>
                                                                <td style="padding: 12px; font-size: 14px; font-weight: 600; color: #28a745;">
                                                                    ৳<?php echo e(number_format($tier->wholesale_price, 2)); ?>

                                                                </td>
                                                                <td style="padding: 12px; font-size: 14px; color: <?php echo e(($tier->stock ?? 0) > 0 ? '#28a745' : '#dc3545'); ?>;">
                                                                    <?php echo e($tier->stock ?? 0); ?> pcs
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <p class="text-muted mt-2 mb-0" style="font-size: 12px;">
                                                    <i class="fa fa-info-circle me-1"></i> Quantity select করলে wholesale price automatically apply হবে
                                                </p>
                                            </div>
                                            <?php endif; ?>
                                            

                                            <form action="<?php echo e(route('cart.store')); ?>" method="POST" name="formName" class="ajax-cart-form" id="productDetailsCartForm" onsubmit="return handleDetailsCartSubmit(event)">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="id" value="<?php echo e($details->id); ?>" />



<?php if($details->variantPrices->count() > 0): ?>
    <?php
        $productcolors = $details->variantPrices->pluck('color')->unique('id')->filter();
        $productsizes = $details->variantPrices->pluck('size')->unique('id')->filter();
    ?>

    
    <?php if($productcolors->count() > 0): ?>
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
                <?php $__currentLoopData = $productcolors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $procolor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $colorHex = $procolor->color ?? '#cbd5e1';
                        $colorLabel = method_exists($procolor, 'getDisplayName')
                            ? ($procolor->getDisplayName() ?? $procolor->colorName ?? $procolor->color_name ?? 'রং')
                            : ($procolor->colorName ?? $procolor->color_name ?? 'রং');
                    ?>
                    <div class="pd-swatch-item">
                        <input type="radio"
                            id="fc-option<?php echo e($procolor->id); ?>"
                            value="<?php echo e($procolor->id); ?>"
                            name="product_color"
                            class="pd-swatch-input selector-item_radio emptyalert"
                            data-option-label="<?php echo e(e($colorLabel)); ?>"
                            required
                            aria-label="<?php echo e($colorLabel); ?>" />
                        <label for="fc-option<?php echo e($procolor->id); ?>" class="pd-swatch-row" title="<?php echo e($colorLabel); ?>">
                            <span class="pd-swatch-visual" style="--pd-swatch: <?php echo e($colorHex); ?>;">
                                <span class="pd-swatch-check" aria-hidden="true"><i class="fa fa-check"></i></span>
                            </span>
                            <span class="pd-swatch-name"><?php echo e($colorLabel); ?></span>
                        </label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($productsizes->count() > 0): ?>
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
                <?php $__currentLoopData = $productsizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prosize): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $sizeLabel = $prosize->sizeName ?? $prosize->name ?? 'সাইজ';
                    ?>
                    <div>
                        <input type="radio"
                            id="f-option<?php echo e($prosize->id); ?>"
                            value="<?php echo e($prosize->id); ?>"
                            name="product_size"
                            class="pd-size-input selector-item_radio emptyalert"
                            data-option-label="<?php echo e(e($sizeLabel)); ?>"
                            required
                            aria-label="<?php echo e($sizeLabel); ?>" />
                        <label for="f-option<?php echo e($prosize->id); ?>" class="pd-size-label"><?php echo e($sizeLabel); ?></label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>





                                                        <?php if($details->pro_unit): ?>
                                                            <div class="pro_unig">
                                                                <label>Unit: <?php echo e($details->pro_unit); ?></label>
                                                                <input type="hidden" name="pro_unit"
                                                                    value="<?php echo e($details->pro_unit); ?>" />
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="pro_brand">
                                                            <p>Brand :
                                                                <?php echo e($details->brand ? $details->brand->name : 'N/A'); ?>

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
                                                                href="tel:<?php echo e(preg_replace('/\s+/', '', $contact->hotline ?? '')); ?>">
                                                                <i class="fa fa-phone-square"></i>
                                                                <?php echo e($contact->hotline); ?>

                                                            </a>
                                                            <a class="pd-support-btn pd-support-btn--wa call_now_btn"
                                                                href="https://api.whatsapp.com/send?phone=<?php echo e($contact->whatsapp); ?>&text=হ্যালো, আমি এই পণ্যটির ব্যাপারে জানতে চাই: <?php echo e(urlencode(Request::url())); ?>"
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
                        
                        <li>
                            <a href="#description" target="_self">Description</a>
                        </li>
                        
                        <li>
                            <a href="#writeReview" target="_self">Reviews (<?php echo e($reviews->count()); ?>) </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="pro_details_area pd-details-body">
    <?php
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
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="description tab-content details-action-box" id="description">
                    <h2>বিস্তারিত</h2>
                    <p><?php echo $details->description; ?></p>
                </div>
                <?php if($hasVideo): ?>
                <div class="pro_vide details-action-box mt-3 mb-3" id="productVideo">
                    <h2>ভিডিও</h2>
                    <?php if($hasYoutubeVideo): ?>
                    <div class="pd-video-embed">
                        <iframe
                            src="https://www.youtube.com/embed/<?php echo e($youtubeId); ?>?rel=0"
                            title="YouTube video player"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </div>
                    <?php elseif($hasUploadedVideo): ?>
                    <div class="pd-video-embed">
                        <video controls playsinline>
                            <source src="<?php echo e(asset($details->pro_video_path)); ?>" type="video/mp4">
                            <source src="<?php echo e(asset($details->pro_video_path)); ?>" type="video/webm">
                            <source src="<?php echo e(asset($details->pro_video_path)); ?>" type="video/ogg">
                            আপনার ব্রাউজার ভিডিও সাপোর্ট করে না।
                        </video>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-light mb-0">ভিডিও পাওয়া যায়নি।</div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <div class="tab-content details-action-box" id="writeReview">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                
							  
							  
							  
							<section class="gomobd-review-section mt-5" id="reviewsList">
    <div class="gomobd-review-header d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 class="gomobd-review-title fw-bold mb-2 mb-md-0">
            Customer Reviews (<?php echo e($reviews->count()); ?>)
        </h3>
        <button type="button" class="gomobd-review-btn btn btn-success btn-sm"
            data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fa fa-edit me-1"></i> Write a Review
        </button>
    </div>

    <?php if($reviews->count() > 0): ?>
    <div class="gomobd-review-list row g-3">
        <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-12">
            <div class="gomobd-review-card shadow-sm">
                <div class="gomobd-review-card-header d-flex justify-content-between align-items-start flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="gomobd-review-avatar">
                            <?php echo e(strtoupper(substr($review->name, 0, 1))); ?>

                        </div>
                        <div class="gomobd-review-meta">
                            <h6 class="gomobd-review-name"><?php echo e($review->name); ?></h6>
                            <small class="gomobd-review-date"><?php echo e($review->created_at->format('d M Y')); ?></small>
                        </div>
                    </div>
                    <div class="gomobd-review-stars">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php if($i <= $review->ratting): ?>
                                <i class="fa-solid fa-star"></i>
                            <?php else: ?>
                                <i class="fa-regular fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="gomobd-review-body mt-2">
                    <p><i class="fa-regular fa-comment-dots text-success me-1"></i> <?php echo e($review->review); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php else: ?>
    <div class="gomobd-review-empty text-center py-5">
        <i class="fa fa-clipboard-list fs-1 text-muted mb-3"></i>
        <p>This product has no reviews yet.<br><strong>Be the first one to write a review.</strong></p>
    </div>
    <?php endif; ?>
</section>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if($products->count() > 0): ?>
<div class="pd-related-wrap">
    <div class="container pd-modern-inner">
        <section class="category-products-section">
            <div class="category-products-head">
                <h2 class="category-products-title">সম্পর্কিত পণ্য</h2>
            </div>
            <div class="category-products-slider">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item">
                        <?php echo $__env->make('frontEnd.layouts.partials.product_card_compact', ['value' => $value], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    </div>
</div>
<?php endif; ?>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Your review</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="insert-review">
                    <?php if(Auth::guard('customer')->user()): ?>
                        <form action="<?php echo e(route('customer.review')); ?>" id="review-form" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo e($details->id); ?>">
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
                    <?php else: ?>
                        <a class="customer-login-redirect" href="<?php echo e(route('customer.login')); ?>">Login to Post Your Review</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?> <?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('public/frontEnd/js/owl.carousel.min.js')); ?>"></script>

<script src="<?php echo e(asset('public/frontEnd/js/zoomsl.min.js')); ?>"></script>
<script>
    // Review modal কে body তে সরিয়ে দেওয়া - #content এর z-index:1 এর জন্য modal backdrop এর পিছনে পড়ছিল
    document.addEventListener('DOMContentLoaded', function() {
        var m = document.getElementById('exampleModal');
        if (m && document.body) document.body.appendChild(m);
    });
</script>
<script>
    const variants = <?php echo json_encode($details->variantPrices, 15, 512) ?>;

    <?php if($details->is_wholesale && $details->wholesalePrices && $details->wholesalePrices->count() > 0): ?>
    var wholesaleTiers = [
        <?php $__currentLoopData = $details->wholesalePrices->sortBy('min_quantity'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        {
            min_quantity: <?php echo e($tier->min_quantity); ?>,
            max_quantity: <?php echo e($tier->max_quantity ?? 999999); ?>,
            price: <?php echo e($tier->wholesale_price); ?>

        }<?php if(!$loop->last): ?>,<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ];
    var regularPrice = <?php echo e($details->new_price); ?>;

    function getWholesalePrice(qty) {
        for (var i = 0; i < wholesaleTiers.length; i++) {
            if (qty >= wholesaleTiers[i].min_quantity && qty <= wholesaleTiers[i].max_quantity) {
                return wholesaleTiers[i].price;
            }
        }
        return null;
    }
    <?php endif; ?>

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
        let basePrice = parseFloat(<?php echo e($details->new_price); ?>);
        if (match && match.price !== undefined && match.price !== null) {
            // Variant price is the actual price for this color/size combination
            basePrice = parseFloat(match.price);
        }

        // Apply wholesale price if applicable (wholesale price overrides variant price)
        <?php if($details->is_wholesale && $details->wholesalePrices && $details->wholesalePrices->count() > 0): ?>
        let qty = parseInt($("input[name='qty']").val()) || 1;
        let wholesalePrice = getWholesalePrice(qty);
        if (wholesalePrice !== null) {
            basePrice = parseFloat(wholesalePrice);
        }
        <?php endif; ?>

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
    var productImages = <?php echo json_encode($details->images->map(function($img) {
        return ['src' => asset($img->image), 'color_id' => $img->color_id];
    }), 512) ?>;

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
                item_name: "<?php echo e($details->name); ?>",
                item_id: "<?php echo e($details->id); ?>",
                price: "<?php echo e($details->new_price); ?>",
                item_brand: "<?php echo e($details->brand?$details->brand->name:''); ?>",
                item_category: "<?php echo e($details->category->name); ?>",
                item_variant: "<?php echo e($details->pro_unit); ?>",
                currency: "BDT",
                quantity: <?php echo e($details->stock ?? 0); ?>

            }],
            impression: [
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    {
                        item_name: "<?php echo e($value->name); ?>",
                        item_id: "<?php echo e($value->id); ?>",
                        price: "<?php echo e($value->new_price); ?>",
                        item_brand: "<?php echo e($details->brand?$details->brand->name:''); ?>",
                        item_category: "<?php echo e($value->category ? $value->category->name : ''); ?>",
                        item_variant: "<?php echo e($value->pro_unit); ?>",
                        currency: "BDT",
                        quantity: <?php echo e($value->stock ?? 0); ?>

                    },
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__currentLoopData = Cart::instance('shopping')->content(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            item_id: "<?php echo e($details->id); ?>",
                            item_name: "<?php echo e($details->name); ?>",
                            price: "<?php echo e($details->new_price); ?>",
                            currency: "BDT",
                            quantity: <?php echo e($cartInfo->qty ?? 0); ?>

                        },
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__currentLoopData = Cart::instance('shopping')->content(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            item_id: "<?php echo e($details->id); ?>",
                            item_name: "<?php echo e($details->name); ?>",
                            price: "<?php echo e($details->new_price); ?>",
                            currency: "BDT",
                            quantity: <?php echo e($cartInfo->qty ?? 0); ?>

                        },
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            });
        });
    });
</script>

<!-- Data Layer End-->


<script type="text/javascript">
    window.dataLayer = window.dataLayer || [];

    (function () {

        var productItem = {
            item_id: "<?php echo e($details->id); ?>",
            item_name: <?php echo json_encode($details->name, 15, 512) ?>,
            price: <?php echo e((float) $details->new_price); ?>,
            item_brand: <?php echo json_encode(optional($details->brand)->name, 15, 512) ?>,
            item_category: <?php echo json_encode(optional($details->category)->name, 15, 512) ?>,
            item_variant: <?php echo json_encode($details->pro_unit, 15, 512) ?>,
            currency: "BDT",
            quantity: <?php echo e($details->stock ?? 0); ?>

        };

        var relatedItems = [
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            {
                item_id: "<?php echo e($value->id); ?>",
                item_name: <?php echo json_encode($value->name, 15, 512) ?>,
                price: <?php echo e((float) $value->new_price); ?>,
                item_brand: <?php echo json_encode(optional($value->brand)->name, 15, 512) ?>,
                item_category: <?php echo json_encode(optional($value->category)->name, 15, 512) ?>,
                item_variant: <?php echo json_encode($value->pro_unit, 15, 512) ?>,
                currency: "BDT",
                quantity: <?php echo e($value->stock ?? 0); ?>

            }<?php if(!$loop->last): ?>,<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        <?php if($details->is_wholesale && $details->wholesalePrices && $details->wholesalePrices->count() > 0): ?>
        var wholesaleTiers = [
            <?php $__currentLoopData = $details->wholesalePrices->sortBy('min_quantity'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            {
                min_quantity: <?php echo e($tier->min_quantity); ?>,
                max_quantity: <?php echo e($tier->max_quantity ?? 999999); ?>,
                price: <?php echo e($tier->wholesale_price); ?>

            }<?php if(!$loop->last): ?>,<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ];
        var regularPrice = <?php echo e($details->new_price); ?>;

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
        <?php endif; ?>
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
        xhr.open("POST", "<?php echo e(route('cart.store')); ?>");
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
                        window.location.href = "<?php echo e(route('customer.checkout')); ?>";
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontEnd.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dms office\pos ecommerce\resources\views/frontEnd/layouts/pages/details.blade.php ENDPATH**/ ?>