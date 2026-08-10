{{-- পুরনো master.blade.php এর মতোই: Bootstrap #popShopModal + Popup মডেল (অ্যাডমিন Popup Offer) --}}
@php
    use App\Models\Popup;
    try {
        $popup = Popup::where('status', 1)->latest('id')->first();
    } catch (\Throwable $e) {
        $popup = null;
    }
    // ইমেজ URL: এই প্রজেক্টে master এ asset('public/...') প্যাটার্ন — পপআপ DB তে সাধারণত uploads/popup/...
    // শুধু asset('uploads/...') দিলে XAMPP/htdocs সেটআপে ছবি ৪০৪ হয়ে ভেঙে যেতে পারে
    $popupImageUrl = '';
    if ($popup && !empty($popup->image)) {
        $raw = trim(str_replace('\\', '/', (string) $popup->image));
        if (\Illuminate\Support\Str::startsWith($raw, ['http://', 'https://'])) {
            $popupImageUrl = $raw;
        } else {
            $rel = ltrim(preg_replace('#^public/#', '', $raw), '/');
            $rel = preg_replace('#^/+#', '', $rel);
            $popupImageUrl = asset('public/' . $rel);
        }
    }
    // শুধু ইমেইজ — যখন বর্ণনা ও লিংক খালি
    $popupImageOnly = $popup && $popupImageUrl !== '' && !trim($popup->description ?? '') && !trim($popup->link ?? '');
@endphp
@if($popup && $popupImageUrl !== '')
<div class="modal fade pop-banner-modal" id="popShopModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="true" style="z-index: 10000;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ $popupImageOnly ? 'pop-banner-dialog' : 'modal-lg' }}">
        <div class="modal-content ps-content {{ $popupImageOnly ? 'ps-content-image-only' : '' }}">
            <button type="button" class="ps-close pop-banner-close" data-bs-dismiss="modal" aria-label="বন্ধ করুন">
                <span aria-hidden="true">&times;</span>
            </button>

            @if($popupImageOnly)
            <div class="ps-image-only-wrap">
                @if(trim($popup->link ?? ''))
                <a href="{{ $popup->link }}" target="_blank" rel="noopener noreferrer" class="d-block">
                    <img src="{{ $popupImageUrl }}" alt="{{ $popup->title ?? 'Promotional offer' }}" class="img-fluid w-100">
                </a>
                @else
                <img src="{{ $popupImageUrl }}" alt="{{ $popup->title ?? 'Promotional offer' }}" class="img-fluid w-100">
                @endif
            </div>
            @else
            <div class="ps-layout">
                <div class="ps-text-section">
                    <h3 class="ps-brand">{{ $popup->title ?? 'Offer' }}</h3>

                    <div class="ps-headline">
                        <p>{!! nl2br(e($popup->description ?? '')) !!}</p>
                    </div>

                    @if($popup->offer_end_text)
                    <p class="ps-deadline">{{ $popup->offer_end_text }}</p>
                    @endif

                    <a href="{{ $popup->link ?? '#' }}" class="ps-btn" @if(!empty($popup->link) && $popup->link !== '#') target="_blank" rel="noopener noreferrer" @endif>
                        {{ $popup->btn_text ?? 'Shop the Sale' }}
                    </a>

                    <div class="ps-footer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16" aria-hidden="true">
                          <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                        </svg>
                        <span>POWERED BY <strong>{{ optional($generalsetting ?? null)->name ?? config('app.name', 'Store') }}</strong></span>
                    </div>
                </div>

                <div class="ps-image-section">
                    <img src="{{ $popupImageUrl }}" alt="{{ $popup->title ?? 'Offer' }}">
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .ps-content {
        border: none;
        border-radius: 0px;
        overflow: hidden;
        background-color: #fff;
        box-shadow: 0 15px 50px rgba(0,0,0,0.3);
        max-width: 850px;
        margin: 0 auto;
    }
    .ps-layout {
        display: flex;
        flex-direction: row;
        min-height: 450px;
    }
    .ps-text-section {
        width: 50%;
        padding: 50px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: left;
        position: relative;
    }
    .ps-brand {
        color: #b93a3a;
        font-family: 'Georgia', 'Times New Roman', serif;
        font-weight: 700;
        font-size: 38px;
        margin-bottom: 15px;
        line-height: 1;
    }
    .ps-headline {
        color: #222;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 15px;
    }
    .ps-headline span, .ps-headline p {
        font-weight: 400;
        font-size: 16px;
        color: #555;
        margin-top: 10px;
    }
    .ps-deadline {
        color: #888;
        font-size: 14px;
        margin-bottom: 30px;
    }
    .ps-btn {
        background-color: #2c3e50;
        color: #fff !important;
        text-decoration: none;
        padding: 15px 30px;
        text-align: center;
        font-weight: 600;
        font-size: 16px;
        border-radius: 2px;
        display: block;
        width: 100%;
        transition: 0.3s;
    }
    .ps-btn:hover {
        background-color: #000;
        color: #fff !important;
    }
    .ps-footer {
        margin-top: 40px;
        font-size: 10px;
        color: #aaa;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
    }
    .ps-footer strong { color: #333; }
    .ps-image-section {
        width: 50%;
        position: relative;
        background: #f0f0f0;
    }
    .ps-image-section img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .ps-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #fff;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        font-size: 24px;
        line-height: 32px;
        color: #333;
        cursor: pointer;
        z-index: 1050;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: 0.2s;
    }
    .ps-close:hover {
        color: #b93a3a;
        transform: scale(1.1);
    }
    body:has(#popShopModal.show) .modal-backdrop {
        opacity: 0.72 !important;
        background-color: #0d0d0d !important;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }
    .pop-banner-modal .modal-dialog.pop-banner-dialog {
        max-width: min(560px, 95vw);
        width: 100%;
        margin: 1rem auto;
    }
    .ps-content-image-only {
        max-width: 100% !important;
        border-radius: 16px !important;
        overflow: visible !important;
        background: #fff !important;
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.08) !important;
        position: relative;
        padding: 10px;
    }
    .ps-content-image-only .ps-image-only-wrap {
        padding: 0;
        line-height: 0;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }
    .ps-content-image-only .ps-image-only-wrap img {
        width: 100%;
        height: auto;
        max-height: min(90vh, 920px);
        object-fit: contain;
        display: block;
        vertical-align: top;
    }
    .ps-content-image-only .ps-image-only-wrap a {
        display: block;
        line-height: 0;
    }
    .ps-content-image-only .ps-image-only-wrap a:hover img {
        opacity: 0.97;
    }
    .pop-banner-close,
    .ps-content-image-only .pop-banner-close {
        position: absolute;
        top: 6px;
        right: 6px;
        z-index: 1060;
        width: 36px;
        height: 36px;
        padding: 0;
        margin: 0;
        border: none;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.96) !important;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.18);
        color: #111 !important;
        font-size: 26px;
        line-height: 1;
        font-weight: 300;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s, background 0.2s;
    }
    .pop-banner-close:hover,
    .ps-content-image-only .pop-banner-close:hover {
        background: #fff !important;
        color: #000 !important;
        transform: scale(1.06);
    }
    .pop-banner-close span {
        line-height: 0.85;
        margin-top: -2px;
    }
    .ps-content:not(.ps-content-image-only) .pop-banner-close {
        top: 15px;
        right: 15px;
        width: 34px;
        height: 34px;
        font-size: 22px;
    }
    .pop-banner-modal .ps-content:not(.ps-content-image-only) {
        border-radius: 12px;
        overflow: hidden;
    }
    @media (max-width: 768px) {
        .ps-layout {
            flex-direction: column-reverse;
        }
        .ps-text-section { width: 100%; padding: 30px; }
        .ps-image-section { width: 100%; height: 250px; }
        .ps-brand { font-size: 30px; }
        .pop-banner-modal .modal-dialog.pop-banner-dialog {
            max-width: 96vw;
            margin: 0.5rem auto;
        }
        .ps-content-image-only .ps-image-only-wrap img {
            max-height: 88vh;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var modalEl = document.getElementById('popShopModal');
        if (!modalEl) return;

        var hoursToWait = 3;
        var delayInSeconds = 2;
        var timeLimit = hoursToWait * 60 * 60 * 1000;
        var lastShown = localStorage.getItem('popupLastShown');
        var now = new Date().getTime();

        if (!lastShown || (now - parseInt(lastShown, 10) > timeLimit)) {
            setTimeout(function() {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.modal === 'function') {
                    jQuery('#popShopModal').modal('show');
                } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    var myModal = new bootstrap.Modal(modalEl);
                    myModal.show();
                }
                localStorage.setItem('popupLastShown', String(now));
            }, delayInSeconds * 1000);
        }
    });
</script>
@endif
