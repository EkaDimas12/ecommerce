<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        // Produk best seller untuk grid (misal tampilkan 8)
        $bestSellers = Product::query()
            ->with('category')
            ->where('is_best_seller', true)
            ->latest()
            ->take(8)
            ->get();

        // 1 produk highlight best seller (untuk hero highlight di home)
        $featuredBestSeller = Product::query()
            ->with('category')
            ->where('is_best_seller', true)
            ->latest()
            ->first();

        // fallback kalau belum ada yg best seller
        if (!$featuredBestSeller) {
            $featuredBestSeller = Product::query()
                ->with('category')
                ->latest()
                ->first();
        }

        $testimonials = Testimonial::query()
            ->latest()
            ->take(6)
            ->get();

        return view('pages.home', compact(
            'categories',
            'bestSellers',
            'featuredBestSeller',
            'testimonials'
        ));
    }
}
