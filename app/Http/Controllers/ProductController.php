<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $activeCategory = (string) $request->query('category', '');
        $activeSort = (string) $request->query('sort', 'newest');

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $query = Product::query()
            ->with('category');

        // Filter kategori
        if ($activeCategory !== '') {
            $query->whereHas('category', function ($cq) use ($activeCategory) {
                $cq->where('slug', $activeCategory);
            });
        }

        // Search
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Sorting
        switch ($activeSort) {
            case 'cheapest':
                $query->orderBy('price', 'asc');
                break;

            case 'best_seller':
                // best seller dulu, lalu terbaru
                $query->orderByDesc('is_best_seller')
                      ->latest();
                break;

            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'q' => $q,
            'activeCategory' => $activeCategory,
            'activeSort' => $activeSort,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::query()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        // rekomendasi produk lain (kategori sama)
        $related = Product::query()
            ->with('category')
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
