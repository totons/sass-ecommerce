@extends('frontEnd.layouts.master')
@section('title', $page->title ?? 'Page')

@push('css')
<style>
.policy-page-wrap {
    --policy-primary: {{ optional($generalsetting)->primary_color ?? '#e11d74' }};
    --policy-secondary: {{ optional($generalsetting)->secodery_color ?? '#1e293b' }};
    padding: 22px 0 36px;
    background: #f8fafc;
}
.policy-page-wrap .policy-container {
    max-width: 1200px;
}
.policy-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    padding: 12px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #fff;
    color: #64748b;
    font-size: 13px;
    font-weight: 600;
}
.policy-breadcrumb a {
    color: #64748b;
    text-decoration: none;
}
.policy-breadcrumb a:hover { color: var(--policy-primary); }
.policy-breadcrumb .sep { color: #cbd5e1; }
.policy-breadcrumb .current {
    color: #0f172a;
    font-weight: 800;
}
.policy-grid {
    display: grid;
    grid-template-columns: 300px minmax(0, 1fr);
    gap: 16px;
}
.policy-sidebar {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
    align-self: start;
    position: sticky;
    top: 94px;
}
.policy-sidebar-title {
    margin: 0 0 10px;
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
}
.policy-menu {
    margin: 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.policy-menu a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 11px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #fff;
    color: #334155;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: all 0.2s ease;
}
.policy-menu a::after {
    content: "›";
    color: #94a3b8;
    font-size: 16px;
    line-height: 1;
}
.policy-menu a:hover {
    border-color: var(--policy-primary);
    color: var(--policy-primary);
    transform: translateX(2px);
}
.policy-menu a.active {
    background: linear-gradient(135deg, var(--policy-primary), #c2185b);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 8px 20px rgba(225, 29, 116, 0.24);
}
.policy-menu a.active::after { color: #fff; }
.policy-content-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}
.policy-header {
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid #e2e8f0;
}
.policy-title {
    margin: 0;
    color: #0f172a;
    font-size: clamp(20px, 2.4vw, 28px);
    font-weight: 900;
    line-height: 1.25;
}
.policy-content {
    color: #334155;
    font-size: 15px;
    line-height: 1.9;
    word-break: break-word;
}
.policy-content p { margin-bottom: 12px; }
.policy-content h1,
.policy-content h2,
.policy-content h3,
.policy-content h4,
.policy-content h5,
.policy-content h6 {
    color: #0f172a;
    margin: 18px 0 10px;
    line-height: 1.35;
    font-weight: 800;
}
.policy-content ul,
.policy-content ol {
    margin: 0 0 14px 18px;
}
.policy-content img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}
.policy-content table {
    width: 100%;
    display: block;
    overflow-x: auto;
    border-collapse: collapse;
}
.policy-content table td,
.policy-content table th {
    border: 1px solid #e2e8f0;
    padding: 8px 10px;
}

@media (max-width: 991.98px) {
    .policy-page-wrap { padding-top: 16px; }
    .policy-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .policy-sidebar {
        position: static;
        padding: 10px;
    }
    .policy-sidebar-title {
        font-size: 14px;
        margin-bottom: 8px;
    }
    .policy-menu {
        flex-direction: row;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 8px;
        padding-bottom: 2px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }
    .policy-menu li { flex: 0 0 auto; }
    .policy-menu a {
        white-space: nowrap;
        transform: none !important;
    }
    .policy-content-card { padding: 16px; }
}
</style>
@endpush

@section('content')
<section class="policy-page-wrap">
    <div class="container policy-container">
        <nav class="policy-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">/</span>
            <span class="current">{{ $page->title }}</span>
        </nav>

        <div class="policy-grid">
            <aside class="policy-sidebar">
                <h3 class="policy-sidebar-title">Useful Pages</h3>
                <ul class="policy-menu">
                    @foreach($cmnmenu as $value)
                        <li>
                            <a href="{{ route('page', $value->slug) }}"
                               class="{{ ($value->slug ?? null) === ($page->slug ?? null) ? 'active' : '' }}">
                                {{ $value->name }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a>
                    </li>
                </ul>
            </aside>

            <article class="policy-content-card">
                <header class="policy-header">
                    <h1 class="policy-title">{{ $page->title }}</h1>
                </header>
                <div class="policy-content">
                    {!! $page->description !!}
                </div>
            </article>
        </div>
    </div>
</section>
@endsection
