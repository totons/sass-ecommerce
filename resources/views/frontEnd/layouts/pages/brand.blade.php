@extends('frontEnd.layouts.master')
@section('title', $brand->name)
@push('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/jquery-ui.css') }}" />
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
        .showing-data {
            height: 100%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .showing-data span { color: #6b7280; font-size: 14px; font-weight: 600; }
        .filter_sort { display: flex; align-items: center; justify-content: flex-end; gap: 10px; }
        .filter_btn, .filter_close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: {{ $generalsetting->primary_color ?? '#7e22ce' }};
            color: #fff;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 700;
            border: none;
            cursor: pointer;
        }
        @media (min-width: 992px) {
            .filter_btn, .filter_close { display: none !important; }
        }
        .page-sort .form-select {
            border-radius: 8px;
            min-height: 38px;
            border: 1px solid #d1d5db;
            font-size: 13px;
            font-weight: 600;
            min-width: 190px;
        }
        .filter_sidebar { background: transparent; border: none; padding: 0 4px 0 0; }
        .filter_sidebar .sidebar_item {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 14px;
            overflow: hidden;
            background: #fff;
        }
        .filter_sidebar .accordion-item { border: none; }
        .filter_sidebar .accordion-button {
            background: {{ $generalsetting->primary_color ?? '#7e22ce' }};
            color: #fff;
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 700;
            border-radius: 0;
            padding: 10px 14px;
            box-shadow: none !important;
        }
        .filter_sidebar .accordion-button::after { filter: brightness(0) invert(1); }
        .filter_sidebar .accordion-body { padding: 10px 12px; background: #fff; }
        .cust_according_body ul { list-style: none; margin: 0; padding: 0; }
        .cust_according_body ul li { margin-bottom: 8px; }
        .cust_according_body ul li a {
            color: #333;
            font-size: 15px;
            font-weight: 500;
        }
        .cust_according_body ul li a:hover { color: {{ $generalsetting->primary_color ?? '#7e22ce' }}; }
        .slider-box { padding: 8px 8px 4px; }
        .filter-price-inputs {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }
        .filter-price-inputs p {
            flex: 1;
            border: none;
            margin: 0;
            padding: 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        .filter-price-inputs input {
            border: none;
            width: 100%;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            background: transparent;
        }
        .filter-price-inputs input:focus { outline: none; }
        .ui-slider-horizontal .ui-slider-range { background-color: {{ $generalsetting->primary_color ?? '#7e22ce' }}; }
        .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
            border-radius: 50px;
            border-color: #d1d5db !important;
        }
        @media (max-width: 991.98px) {
            .showing-data { justify-content: flex-start; margin-top: 8px; }
            .filter_sort { justify-content: flex-start; margin-top: 8px; }
            .page-sort .form-select { min-width: 160px; width: 100%; }
            .product-section .row > .col-sm-3.filter_sidebar,
            .product-section .row > .col-sm-9 { width: 100%; }
            .filter_sidebar { margin-bottom: 14px; }
        }
        @media (max-width: 767.98px) {
            .product-section { padding-top: 10px; }
            .sorting-section { padding: 10px; }
            .category-breadcrumb { margin-bottom: 6px; }
            .category-breadcrumb a, .category-breadcrumb strong, .showing-data span { font-size: 13px; }
            .filter_btn, .filter_close { padding: 6px 10px; font-size: 12px; }
            .filter_sidebar .accordion-button { font-size: 13px; padding: 9px 12px; }
            .cust_according_body ul li a { font-size: 13px; }
        }
    </style>
@endpush

@section('content')
<section class="product-section">
    <div class="container">
        <div class="sorting-section">
            <div class="row">
                <div class="col-sm-6">
                    <div class="category-breadcrumb d-flex align-items-center flex-wrap">
                        <a href="{{ route('home') }}">Home</a>
                        <span>/</span>
                        <a href="{{ route('brand') }}">Brands</a>
                        <span>/</span>
                        <strong>{{ $brand->name }}</strong>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="showing-data">
                                <span>Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} Results</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="filter_sort">
                                <div class="filter_btn"><i class="fa fa-list-ul"></i></div>
                                <div class="page-sort">
                                    <form action="{{ route('brand.products', $brand->slug) }}" method="GET" class="sort-form">
                                        <select name="sort" class="form-control form-select sort">
                                            <option value="1" @if(request()->get('sort')==1) selected @endif>Product: Latest</option>
                                            <option value="2" @if(request()->get('sort')==2) selected @endif>Product: Oldest</option>
                                            <option value="3" @if(request()->get('sort')==3) selected @endif>Price: High To Low</option>
                                            <option value="4" @if(request()->get('sort')==4) selected @endif>Price: Low To High</option>
                                            <option value="5" @if(request()->get('sort')==5) selected @endif>Name: A-Z</option>
                                            <option value="6" @if(request()->get('sort')==6) selected @endif>Name: Z-A</option>
                                        </select>
                                        <input type="hidden" name="min_price" value="{{ request()->get('min_price') }}" />
                                        <input type="hidden" name="max_price" value="{{ request()->get('max_price') }}" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3 filter_sidebar">
                <div class="filter_close"><i class="fa fa-long-arrow-left"></i> Filter</div>
                <form action="{{ route('brand.products', $brand->slug) }}" method="GET" class="attribute-submit">
                    <input type="hidden" name="sort" value="{{ request()->get('sort') }}" />
                    <div class="sidebar_item wraper__item">
                        <div class="accordion" id="category_sidebar">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCat" aria-expanded="true" aria-controls="collapseOne">
                                        Categories
                                    </button>
                                </h2>
                                <div id="collapseCat" class="accordion-collapse collapse show"
                                    data-bs-parent="#category_sidebar">
                                    <div class="accordion-body cust_according_body">
                                        <ul>
                                            @foreach ($categories as $cat)
                                                <li>
                                                    <a href="{{ route('category', $cat->slug) }}">{{ $cat->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($products->count() > 0 || isset($min_price, $max_price))
                    <div class="sidebar_item wraper__item">
                        <div class="accordion" id="price_sidebar">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapsePrice" aria-expanded="true" aria-controls="collapseOne">
                                        Price
                                    </button>
                                </h2>
                                <div id="collapsePrice" class="accordion-collapse collapse show"
                                    data-bs-parent="#price_sidebar">
                                    <div class="accordion-body cust_according_body">
                                        <div class="category-filter-box category__wraper" id="categoryFilterBox">
                                            <div class="category-filter-item">
                                                <div class="filter-body">
                                                    <div class="slider-box">
                                                        <div class="filter-price-inputs">
                                                            <p class="min-price">৳<input type="text" name="min_price" id="min_price" readonly="" /></p>
                                                            <p class="max-price">৳<input type="text" name="max_price" id="max_price" readonly="" /></p>
                                                        </div>
                                                        @if(isset($min_price, $max_price) && $min_price < $max_price)
                                                            <div id="price-range" class="slider form-attribute"></div>
                                                        @else
                                                            <p class="text-muted small mb-0 mt-1" style="font-size: 13px;">
                                                                এই রেঞ্জে একই মূল্যের পণ্য (৳{{ $min_price ?? 0 }})
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
            <div class="col-sm-9">
                <section class="category-products-section">
                    <div class="category-products-head">
                        <h2 class="category-products-title">{{ $brand->name }}</h2>
                    </div>
                    <div class="category-products-slider">
                        @forelse($products as $key => $value)
                            <div class="item">
                                @include('frontEnd.layouts.partials.product_card_compact', ['value' => $value])
                            </div>
                        @empty
                            <div class="w-100 text-center py-5">
                                <p class="text-muted">এই ব্র্যান্ডে কোন প্রোডাক্ট খুঁজে পাওয়া যায়নি।</p>
                                <a href="{{ route('shop') }}" class="btn btn-primary">শপে যান</a>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        @if($products->hasPages())
        <div class="row">
            <div class="col-sm-12">
                <div class="custom_paginate">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script>
        $(".form-attribute").on('change click', function() {
            $(".attribute-submit").submit();
        });
        $(".sort").on('change', function() {
            $(".sort-form").submit();
        });
    </script>
    <script>
        $(function() {
            var $range = $("#price-range");
            if (!$range.length) return;
            var pMin = {{ (int) ($min_price ?? 0) }};
            var pMax = {{ (int) ($max_price ?? 0) }};
            if (pMax <= pMin) return;
            var reqMin = {{ request()->get('min_price') !== null && request()->get('min_price') !== '' ? (int) request()->get('min_price') : 'null' }};
            var reqMax = {{ request()->get('max_price') !== null && request()->get('max_price') !== '' ? (int) request()->get('max_price') : 'null' }};
            var v0 = reqMin !== null ? reqMin : pMin;
            var v1 = reqMax !== null ? reqMax : pMax;
            if (v0 < pMin) v0 = pMin;
            if (v1 > pMax) v1 = pMax;
            if (v1 < v0) v1 = v0;
            $range.slider({
                step: 5,
                range: true,
                min: pMin,
                max: pMax,
                values: [v0, v1],
                slide: function(event, ui) {
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                },
                stop: function() {
                    $(".attribute-submit").submit();
                }
            });
            $("#min_price").val(v0);
            $("#max_price").val(v1);
        });
    </script>
    @if(isset($min_price, $max_price) && !($min_price < $max_price))
    <script>
        $(function() {
            var s = {{ (int) ($min_price ?? 0) }};
            $("#min_price").val(s);
            $("#max_price").val(s);
        });
    </script>
    @endif
@endpush
