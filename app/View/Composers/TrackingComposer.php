<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\EcomPixel;
use App\Models\GoogleTagManager;
use App\Models\TiktokPixel;
use App\Models\Brand;

class TrackingComposer
{
    /**
     * Share tracking pixels (Facebook, TikTok) and GTM with frontend views.
     * Ensures dataLayer-ready data for GTM, Facebook Pixel, TikTok Pixel.
     */
    public function compose(View $view)
    {
        // Only compose for frontend master layout
        if (!str_contains($view->getName(), 'frontEnd.layouts.master')) {
            return;
        }

        // Facebook Pixels - fallback if not already set
        if (!$view->offsetExists('pixels') || $view->getData()['pixels'] === null) {
            $view->with('pixels', Cache::remember('pixels_list', 3600, fn() => EcomPixel::where('status', 1)->get()));
        }

        // GTM - fallback if not already set
        if (!$view->offsetExists('gtm_code') || $view->getData()['gtm_code'] === null) {
            $view->with('gtm_code', Cache::remember('gtm_code_list', 3600, fn() => GoogleTagManager::where('status', 1)->get()));
        }

        // TikTok Pixels
        $view->with('tiktok_pixels', Cache::remember('tiktok_pixels_list', 3600, fn() => TiktokPixel::where('status', 1)->get()));

        // Hero sliders (category_id 1 = main slider) - fallback if not already set
        if (!$view->offsetExists('sliders') || $view->getData()['sliders'] === null) {
            $view->with('sliders', Cache::remember('hero_sliders', 300, fn() => Banner::where(['status' => 1, 'category_id' => 1])->select('id', 'image', 'link')->orderBy('id')->get()));
        }

        // Menu categories + subcategories + childcategories for mega menu (always set for consistent data)
        $view->with('menucategories', Cache::remember('menu_categories_v3', 600, function () {
            return Category::where('status', 1)
                ->where('parent_id', 0)
                ->orderBy('id')
                ->with([
                    'subcategories' => fn ($q) => $q->where('status', 1)->orderBy('id'),
                    'subcategories.childcategories' => fn ($q) => $q->where('status', 1)->orderBy('id'),
                ])
                ->get();
        }));

        // Home Ads (category_id 10) - for secondary banner
        if (!$view->offsetExists('homepageads') || $view->getData()['homepageads'] === null) {
            $view->with('homepageads', Cache::remember('homepage_ads', 300, fn() => Banner::where(['status' => 1, 'category_id' => 10])->select('id', 'image', 'link')->orderBy('id')->limit(1)->get()));
        }

        // Hot Deals (category_id 9) - for DEALS YOU CANNOT MISS section
        if (!$view->offsetExists('hotDealsBanners') || $view->getData()['hotDealsBanners'] === null) {
            $view->with('hotDealsBanners', Cache::remember('hot_deals_banners', 300, fn() => Banner::where(['status' => 1, 'category_id' => 9])->select('id', 'image', 'link', 'title')->orderBy('id')->limit(4)->get()));
        }

        // Home Ads 2 (category_id 11) - for Promo Grid (Top Brands)
        if (!$view->offsetExists('homepageads2') || $view->getData()['homepageads2'] === null) {
            $view->with('homepageads2', Cache::remember('homepage_ads2', 300, fn() => Banner::where(['status' => 1, 'category_id' => 11])->select('id', 'image', 'link', 'title')->orderBy('id')->limit(6)->get()));
        }

        // Home (category_id 12) - for Today's Deal
        if (!$view->offsetExists('homeBanner') || $view->getData()['homeBanner'] === null) {
            $view->with('homeBanner', Cache::remember('home_banner', 300, fn() => Banner::where(['status' => 1, 'category_id' => 12])->select('id', 'image', 'link', 'title')->orderBy('id')->limit(1)->get()));
        }

        // Offers (category_id 13) - for LIMITED TIME OFFERS / major-promo
        if (!$view->offsetExists('offersBanners') || $view->getData()['offersBanners'] === null) {
            $view->with('offersBanners', Cache::remember('offers_banners', 300, fn() => Banner::where(['status' => 1, 'category_id' => 13])->select('id', 'image', 'link', 'title')->orderBy('id')->limit(4)->get()));
        }

        // Flash sale products (same query as homepage index)
        if (!$view->offsetExists('flas_sales') || $view->getData()['flas_sales'] === null) {
            $view->with('flas_sales', Cache::remember('flash_sale_products_v1', 300, function () {
                return Product::where(['status' => 1, 'approval_status' => 'approved', 'flashsale' => 1])
                    ->orderBy('id', 'DESC')
                    ->select('id', 'name', 'slug', 'new_price', 'old_price', 'sold', 'stock')
                    ->with(['prosizes', 'procolors', 'image', 'reviews'])
                    ->limit(12)
                    ->get();
            }));
        }

        // Category-wise home products (for master sections like index)
        if (!$view->offsetExists('homeproducts') || $view->getData()['homeproducts'] === null) {
            $view->with('homeproducts', Cache::remember('home_products_by_category_v1', 300, function () {
                return Category::where(['front_view' => 1, 'status' => 1])
                    ->orderBy('id', 'ASC')
                    ->select('id', 'name', 'slug')
                    ->with([
                        'products' => function ($q) {
                            $q->select('id', 'name', 'slug', 'new_price', 'old_price', 'sold', 'stock', 'category_id', 'brand_id')
                                ->where('status', 1)
                                ->where('approval_status', 'approved')
                                ->with(['image', 'prosizes', 'procolors', 'reviews', 'brand'])
                                ->limit(12);
                        }
                    ])
                    ->get()
                    ->map(function ($query) {
                        $query->setRelation('products', $query->products->take(12));
                        return $query;
                    });
            }));
        }

        // Hot deal top products (same as index)
        if (!$view->offsetExists('hotdeal_top') || $view->getData()['hotdeal_top'] === null) {
            $view->with('hotdeal_top', Cache::remember('hot_deal_top_products_v1', 300, function () {
                return Product::where(['status' => 1, 'approval_status' => 'approved', 'topsale' => 1])
                    ->orderBy('id', 'DESC')
                    ->select('id', 'name', 'slug', 'new_price', 'old_price', 'sold', 'stock')
                    ->with(['prosizes', 'procolors', 'image', 'reviews'])
                    ->limit(12)
                    ->get();
            }));
        }

        // Brands (for master standalone section)
        if (!$view->offsetExists('brands') || $view->getData()['brands'] === null) {
            $view->with('brands', Cache::remember('brands_v1', 3600, function () {
                return Brand::where('status', 1)
                    ->select('id', 'name', 'slug', 'image')
                    ->limit(12)
                    ->get();
            }));
        }
    }
}
