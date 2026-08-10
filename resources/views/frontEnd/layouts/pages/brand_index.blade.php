@extends('frontEnd.layouts.master')
@section('title', 'ব্র্যান্ডসমূহ')

@push('css')
    <style>
        .product-section { padding-top: 28px; }
        .sorting-section {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 14px;
        }
        .category-breadcrumb a { color: #6b7280; font-weight: 600; }
        .category-breadcrumb span { margin: 0 6px; color: #9ca3af; }
        .category-breadcrumb strong { color: #111827; font-weight: 800; }
        .brand-index-grid { margin-bottom: 28px; }
        .brand-index-card {
            display: block;
            height: 100%;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px 12px;
            text-align: center;
            text-decoration: none;
            color: #111827;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .brand-index-card:hover {
            border-color: {{ $generalsetting->primary_color ?? '#7e22ce' }};
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            color: #111827;
        }
        .brand-index-card-media {
            width: 100%;
            aspect-ratio: 1;
            max-height: 100px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .brand-index-card-media img {
            max-width: 100%;
            max-height: 100px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        .brand-index-card-placeholder {
            width: 72px;
            height: 72px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            background: {{ $generalsetting->primary_color ?? '#7e22ce' }};
        }
        .brand-index-card-name {
            font-size: 14px;
            font-weight: 700;
            line-height: 1.35;
            word-break: break-word;
        }
        .brand-index-empty {
            text-align: center;
            padding: 48px 20px;
            background: #fff;
            border: 1px dashed #e5e7eb;
            border-radius: 10px;
            color: #6b7280;
        }
        @media (max-width: 767.98px) {
            .product-section { padding-top: 10px; }
            .sorting-section { padding: 10px; }
            .category-breadcrumb a, .category-breadcrumb strong { font-size: 13px; }
        }
    </style>
@endpush

@section('content')
    <section class="product-section">
        <div class="container">
            <div class="sorting-section">
                <div class="row">
                    <div class="col-12">
                        <div class="category-breadcrumb d-flex align-items-center flex-wrap">
                            <a href="{{ route('home') }}">Home</a>
                            <span>/</span>
                            <strong>ব্র্যান্ডসমূহ</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="category-products-head mb-3">
                <h2 class="category-products-title">সকল ব্র্যান্ড</h2>
            </div>

            @if ($brands->count() > 0)
                <div class="row g-3 brand-index-grid">
                    @foreach ($brands as $brand)
                        @php
                            $raw = $brand->logo ?? $brand->image ?? null;
                            $brandImg = null;
                            if (! empty($raw)) {
                                $brandImg = \Illuminate\Support\Str::startsWith($raw, ['http://', 'https://'])
                                    ? $raw
                                    : asset($raw);
                            }
                            $initial = mb_substr((string) $brand->name, 0, 1, 'UTF-8');
                            if ($initial === '') {
                                $initial = '?';
                            }
                        @endphp
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="{{ route('brand.products', $brand->slug) }}" class="brand-index-card">
                                <div class="brand-index-card-media">
                                    @if ($brandImg)
                                        <img src="{{ $brandImg }}" alt="{{ $brand->name }}" loading="lazy" />
                                    @else
                                        <span class="brand-index-card-placeholder" aria-hidden="true">{{ $initial }}</span>
                                    @endif
                                </div>
                                <div class="brand-index-card-name">{{ $brand->name }}</div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="brand-index-empty">
                    <p class="mb-0">কোন ব্র্যান্ড পাওয়া যায়নি।</p>
                </div>
            @endif
        </div>
    </section>
@endsection
