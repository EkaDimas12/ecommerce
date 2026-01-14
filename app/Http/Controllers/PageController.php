<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;

class PageController extends Controller
{
    /**
     * HOME PAGE
     * - Categories untuk homepage (blade akan mengatur jadi 8 kotak)
     * - Best Sellers untuk section best seller (blade bisa menambah kotak jika kurang)
     * - Testimonials aktif untuk ditampilkan di homepage
     */
    public function home()
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $bestSellers = Product::query()
            ->with('category')
            ->where('is_best_seller', true)
            ->latest()
            ->take(8)
            ->get();

        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        return view('pages.home', compact(
            'categories',
            'bestSellers',
            'testimonials'
        ));
    }

    /**
     * PROFILE / ABOUT PAGE
     */
    public function profile()
    {
        return view('pages.profile');
    }

    /**
     * ORDERING INFORMATION PAGE
     */
    public function orderingInfo()
    {
        return view('pages.ordering-info');
    }

    /**
     * CONTACT PAGE
     * (Form testimoni ada di view contact.blade.php -> POST ke testimonials.store)
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * HELP / FAQ PAGE
     */
    public function help()
    {
        return view('pages.help');
    }
}
