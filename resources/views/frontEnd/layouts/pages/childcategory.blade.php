@extends('frontEnd.layouts.master') 
@section('title',$childcategory->childcategoryName) 
@push('css')
<link rel="stylesheet" href="{{asset('public/frontEnd/css/jquery-ui.css')}}" />
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
    .filter_btn {
        width: 38px;
        height: 38px;
        padding: 0;
        border-radius: 50%;
        font-size: 17px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    }
    /* ডেস্কটপে সাইডবার সবসময় দেখা যায় — উপরে/সাইডবারে ডুপ্লিকেট ফিল্টার বাটন লুকানো */
    @media (min-width: 992px) {
        .filter_btn,
        .filter_close {
            display: none !important;
        }
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
    .filter_sidebar .accordion-button:not(.collapsed) {
        background: {{ $generalsetting->primary_color ?? '#7e22ce' }};
        color: #fff;
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
    .ui-state-default,
    .ui-widget-content .ui-state-default,
    .ui-widget-header .ui-state-default {
        border-radius: 50px;
        border-color: #d1d5db !important;
    }

    @media (max-width: 991.98px) {
        .showing-data { justify-content: flex-start; margin-top: 8px; }
        .filter_sort { justify-content: flex-end; margin-top: 8px; flex-wrap: wrap; }
        .page-sort { justify-content: flex-start; }
        .page-sort .form-select { min-width: 160px; width: 100%; }
        .product-section .row > .col-sm-3.filter_sidebar,
        .product-section .row > .col-sm-9 { width: 100%; }
        .filter_sidebar { margin-bottom: 14px; }
    }
    @media (max-width: 767.98px) {
        .product-section { padding-top: 10px; }
        .sorting-section { padding: 10px; }
        .category-breadcrumb { margin-bottom: 6px; }
        .category-breadcrumb a,
        .category-breadcrumb strong,
        .showing-data span { font-size: 13px; }
        .filter_btn, .filter_close { padding: 6px 10px; font-size: 12px; }
        .filter_sidebar .accordion-button { font-size: 13px; padding: 9px 12px; }
        .cust_according_body ul li a { font-size: 13px; }
    }
</style>
@endpush 
@push('seo')
<meta name="app-url" content="{{route('products',$childcategory->slug)}}" />
<meta name="robots" content="index, follow" />
<meta name="description" content="{{ $childcategory->meta_description}}" />
<meta name="keywords" content="{{ $childcategory->slug }}" />

<!-- Twitter Card data -->
<meta name="twitter:card" content="product" />
<meta name="twitter:site" content="{{$childcategory->childcategoryName}}" />
<meta name="twitter:title" content="{{$childcategory->childcategoryName}}" />
<meta name="twitter:description" content="{{ $childcategory->meta_description}}" />
<meta name="twitter:creator" content="gomobd.com" />
<meta property="og:url" content="{{route('products',$childcategory->slug)}}" />
<meta name="twitter:image" content="{{asset($childcategory->image)}}" />

<!-- Open Graph data -->
<meta property="og:title" content="{{$childcategory->childcategoryName}}" />
<meta property="og:type" content="product" />
<meta property="og:url" content="{{route('products',$childcategory->slug)}}" />
<meta property="og:image" content="{{asset($childcategory->image)}}" />
<meta property="og:description" content="{{ $childcategory->meta_description}}" />
<meta property="og:site_name" content="{{$childcategory->childcategoryName}}" />
@endpush 
@section('content')
<section class="product-section">
    <div class="container">
        <div class="sorting-section">
            <div class="row">
                <div class="col-sm-6">
                    <div class="category-breadcrumb d-flex align-items-center">
                        <a href="{{ route('home') }}">Home</a>
                        <span>/</span>
                        <strong>{{ $childcategory->childcategoryName }}</strong>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="showing-data">
                                <span>Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} Results</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="filter_sort">
                                <div class="filter_btn">
                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </div>
                                <div class="page-sort">
                                    <form action="" class="sort-form">
                                        <select name="sort" class="form-control form-select sort">
                                            <option value="1" @if(request()->get('sort')==1)selected @endif>Product: Latest</option>
                                            <option value="2" @if(request()->get('sort')==2)selected @endif>Product: Oldest</option>
                                            <option value="3" @if(request()->get('sort')==3)selected @endif>Price: High To Low</option>
                                            <option value="4" @if(request()->get('sort')==4)selected @endif>Price: Low To High</option>
                                            <option value="5" @if(request()->get('sort')==5)selected @endif>Name: A-Z</option>
                                            <option value="6" @if(request()->get('sort')==6)selected @endif>Name: Z-A</option>
                                        </select>
                                        <input type="hidden" name="min_price" value="{{request()->get('min_price')}}" />
                                        <input type="hidden" name="max_price" value="{{request()->get('max_price')}}" />
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
                <form action="" class="attribute-submit">
                    <div class="sidebar_item wraper__item">
                        <div class="accordion" id="category_sidebar">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCat" aria-expanded="true" aria-controls="collapseOne">
                                        {{ $childcategory->childcategoryName }}
                                    </button>
                                </h2>
                                <div id="collapseCat" class="accordion-collapse collapse show"
                                    data-bs-parent="#category_sidebar">
                                    <div class="accordion-body cust_according_body">
                                        <ul>
                                            @foreach ($childcategories as $key => $childcat)
                                                <li>
                                                    <a href="{{ url('products/' . $childcat->slug) }}">{{ $childcat->childcategoryName }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--sidebar item end-->
                    @if($products->count() > 0)
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
                                                            <p class="min-price">৳<input type="text"
                                                                    name="min_price" id="min_price" readonly="" />
                                                            </p>
                                                            <p class="max-price">৳<input type="text"
                                                                    name="max_price" id="max_price" readonly="" />
                                                            </p>
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
                    <!--sidebar item end-->
                </form>
            </div>
            <div class="col-sm-9">
                <section class="category-products-section">
                    <div class="category-products-head">
                        <h2 class="category-products-title">{{ $childcategory->childcategoryName }}</h2>
                    </div>
                    <div class="category-products-slider">
                        @foreach($products as $key => $value)
                            <div class="item">
                                @include('frontEnd.layouts.partials.product_card_compact', ['value' => $value])
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="custom_paginate">
                    {{$products->links('pagination::bootstrap-4')}}
                </div>
            </div>
        </div>

    </div>
</section>
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="meta_des">
                    {!!$childcategory->meta_description!!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script>
        $(".form-attribute, .form-checkbox").on('change click', function() {
            $(".attribute-submit").submit();
        });
        $(".sort").on('change', function() {
            $(".sort-form").submit();
        });
    </script>
    <script>
        $(function() {
            var $range = $("#price-range");
            if (!$range.length) {
                return;
            }
            var pMin = {{ (int) ($min_price ?? 0) }};
            var pMax = {{ (int) ($max_price ?? 0) }};
            if (pMax <= pMin) {
                return;
            }
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
            var s = {{ (int) $min_price }};
            $("#min_price").val(s);
            $("#max_price").val(s);
        });
    </script>
    @endif

    {{-- 🔹 GA4 DataLayer + Facebook Pixel for Childcategory Page --}}
    <script type="text/javascript">
        window.dataLayer = window.dataLayer || [];

        (function () {
            var listName = @json($childcategory->childcategoryName);
            var listSlug = @json($childcategory->slug);

            var listItems = [
                @foreach($products as $index => $value)
                {
                    item_id: "{{ $value->id }}",
                    item_name: @json($value->name),
                    price: {{ (float) $value->new_price }},
                    item_brand: @json(optional($value->brand)->name),
                    item_category: @json(optional($value->category)->name ?? $childcategory->childcategoryName),
                    item_list_id: listSlug,
                    item_list_name: listName,
                    index: {{ $loop->iteration }},
                    slug: @json($value->slug),
                    currency: "BDT"
                }@if(!$loop->last),@endif
                @endforeach
            ];

            // GA4: view_item_list
            if (listItems.length) {
                window.dataLayer.push({ ecommerce: null });
                window.dataLayer.push({
                    event: "view_item_list",
                    ecommerce: {
                        item_list_id: listSlug,
                        item_list_name: listName,
                        items: listItems.map(function (item) {
                            return {
                                item_id: item.item_id,
                                item_name: item.item_name,
                                index: item.index,
                                price: item.price,
                                item_brand: item.item_brand,
                                item_category: item.item_category,
                                item_list_id: item.item_list_id,
                                item_list_name: item.item_list_name,
                                currency: item.currency
                            };
                        })
                    }
                });
            }

            // Facebook Pixel: ViewChildCategory (custom)
            if (typeof fbq === "function") {
                fbq("trackCustom", "ViewChildCategory", {
                    content_category: listName,
                    content_ids: listItems.map(function (i) { return i.item_id; }),
                    currency: "BDT"
                });
            }

            function findItemByHref(href) {
                if (!href) return null;
                try {
                    var parts = href.split("/");
                    var last = parts[parts.length - 1].split("?")[0];
                    return listItems.find(function (i) { return i.slug === last; }) || null;
                } catch (e) {
                    return null;
                }
            }

            // product click -> select_item + FB event
            $(document).on("click", ".category-product .product_item a", function () {
                var href = $(this).attr("href") || "";
                var item = findItemByHref(href);
                if (!item) return;

                window.dataLayer.push({ ecommerce: null });
                window.dataLayer.push({
                    event: "select_item",
                    ecommerce: {
                        item_list_id: listSlug,
                        item_list_name: listName,
                        items: [{
                            item_id: item.item_id,
                            item_name: item.item_name,
                            index: item.index,
                            price: item.price,
                            item_brand: item.item_brand,
                            item_category: item.item_category,
                            item_list_id: item.item_list_id,
                            item_list_name: item.item_list_name,
                            currency: item.currency
                        }]
                    }
                });

                if (typeof fbq === "function") {
                    fbq("trackCustom", "ChildCategoryProductClick", {
                        content_ids: [item.item_id],
                        content_name: item.item_name,
                        content_category: item.item_category,
                        value: item.price,
                        currency: "BDT"
                    });
                }
            });

        })();
    </script>
@endpush
