@extends('frontEnd.layouts.master')
@section('title', $vendor->shop_name)

@push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/jquery-ui.css') }}">
{{-- প্রোডাক্ট কার্ড: ক্যাটাগরি পেজের মতো — styles.css এ .category-products-section / .flash-product-card --}}
@endpush

@section('content')
<section class="product-section">
    <div class="container">

        {{-- Vendor Shop Header with Background Banner --}}
        <div class="vendor-shop-header-wrapper" style="position: relative; border-radius: 15px; overflow: hidden; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            {{-- Background Banner --}}
            <div class="vendor-banner-background" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: url('{{ $vendor->banner ? asset($vendor->banner) : asset('public/frontEnd/images/default-banner.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; z-index: 0;">
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.3) 100%);"></div>
            </div>
            
            {{-- Content Overlay --}}
            <div class="vendor-shop-header" style="position: relative; padding: 40px 30px; z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <div class="vendor-logo-large" style="position: relative; display: inline-block;">
                            @if($vendor->logo)
                                <img src="{{ asset($vendor->logo) }}" alt="{{ $vendor->shop_name }}" style="width: 120px; height: 120px; border-radius: 50%; border: 5px solid #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.3); object-fit: cover;" />
                            @else
                                <div style="width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.25); border: 5px solid #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 48px; font-weight: bold; color: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                                    {{ strtoupper(substr($vendor->shop_name, 0, 1)) }}
                                </div>
                            @endif
                            @if($vendor->verification_status == 'approved')
                            <div style="position: absolute; bottom: 5px; right: 5px; width: 36px; height: 36px; background: #0d6efd; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                                <i class="fas fa-check-circle" style="color: #fff; font-size: 18px;"></i>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h1 style="color: #fff; font-size: 32px; font-weight: 700; margin-bottom: 20px; text-shadow: 0 2px 8px rgba(0,0,0,0.5);">{{ $vendor->shop_name }}</h1>
                        
                        <div class="vendor-stats" style="display: flex; gap: 30px; flex-wrap: wrap;">
                            <div style="color: #fff; background: rgba(255,255,255,0.15); padding: 12px 20px; border-radius: 10px; backdrop-filter: blur(10px);">
                                <strong style="font-size: 24px; display: block; font-weight: 700;">{{ $vendor->total_products }}</strong>
                                <span style="font-size: 13px; display: block; opacity: 0.9;">Products</span>
                            </div>
                            <div style="color: #fff; background: rgba(255,255,255,0.15); padding: 12px 20px; border-radius: 10px; backdrop-filter: blur(10px);">
                                <strong style="font-size: 24px; display: block; font-weight: 700;">{{ $vendor->total_reviews }}</strong>
                                <span style="font-size: 13px; display: block; opacity: 0.9;">Reviews</span>
                            </div>
                            <div style="color: #fff; background: rgba(255,255,255,0.15); padding: 12px 20px; border-radius: 10px; backdrop-filter: blur(10px);">
                                <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($vendor->average_rating))
                                            <i class="fas fa-star" style="color: #ffc107; font-size: 16px;"></i>
                                        @elseif($i - 0.5 <= $vendor->average_rating)
                                            <i class="fas fa-star-half-alt" style="color: #ffc107; font-size: 16px;"></i>
                                        @else
                                            <i class="far fa-star" style="color: rgba(255,255,255,0.6); font-size: 16px;"></i>
                                        @endif
                                    @endfor
                                    <span style="font-size: 16px; margin-left: 8px; font-weight: 600;">{{ $vendor->average_rating > 0 ? number_format($vendor->average_rating, 1) : '0.0' }}</span>
                                </div>
                                <span style="font-size: 13px; display: block; opacity: 0.9;">Average Rating</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Breadcrumb + Sorting --}}
        <div class="sorting-section" style="background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="category-breadcrumb d-flex align-items-center" style="font-size: 14px;">
                        <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">Home</a>
                        <span style="margin: 0 8px; color: #999;">/</span>
                        <strong style="color: #222; font-weight: 600;">{{ $vendor->shop_name }}</strong>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <div class="showing-data" style="font-size: 14px; color: #666;">
                                <span>
                                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                                    of {{ $products->total() }} Results
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <form class="sort-form vendor-shop-sort-form" method="get" action="{{ url()->current() }}">
                                <select name="sort" class="form-control form-select sort" style="border-radius: 8px; border: 1px solid #e0e0e0; padding: 8px 12px; font-size: 14px;">
                                    <option value="1" @selected(request('sort')==1)>Product: Latest</option>
                                    <option value="2" @selected(request('sort')==2)>Product: Oldest</option>
                                    <option value="3" @selected(request('sort')==3)>Price: High To Low</option>
                                    <option value="4" @selected(request('sort')==4)>Price: Low To High</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ক্যাটাগরি পেজের মতো একই গ্রিড + product_card_compact --}}
        <section class="category-products-section">
            <div class="category-products-head">
                <h2 class="category-products-title">{{ $vendor->shop_name }} — Products</h2>
            </div>
            @if($products->count() > 0)
                <div class="category-products-slider">
                    @foreach($products as $value)
                        <div class="item">
                            @include('frontEnd.layouts.partials.product_card_compact', ['value' => $value])
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted py-4 mb-0" style="font-size: 1.05rem;">এই শপে এখন কোনো প্রোডাক্ট নেই।</p>
            @endif
        </section>

        <div class="row mt-3 mb-4">
            <div class="col-sm-12">
                <div class="custom_paginate text-center">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var sortSel = document.querySelector('.vendor-shop-sort-form select.sort');
        if (sortSel) {
            sortSel.addEventListener('change', function () { this.form.submit(); });
        }
    });
</script>
@endpush
