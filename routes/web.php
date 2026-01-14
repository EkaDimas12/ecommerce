<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
  Route::get('/signup', [AuthController::class, 'showRegister'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});



/*
|--------------------------------------------------------------------------
| ORDER
|--------------------------------------------------------------------------
*/
Route::get('/order/{code}', [OrderController::class, 'show'])
    ->name('order.show');

Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
Route::get('/payment/finish', [MidtransController::class, 'finish'])->name('payment.finish');
Route::get('/payment/unfinish', [MidtransController::class, 'unfinish'])->name('payment.unfinish');
Route::get('/payment/error', [MidtransController::class, 'error'])->name('payment.error');


/*
|--------------------------------------------------------------------------
| PAGES
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/profil', [PageController::class, 'profile'])->name('profile');

Route::get('/informasi-pemesanan', [PageController::class, 'orderingInfo'])
    ->name('ordering.info');

Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');
Route::post('/testimoni', [TestimonialController::class, 'store'])->name('testimonials.store');

Route::get('/bantuan', [PageController::class, 'help'])->name('help');


/*
|--------------------------------------------------------------------------
| PRODUCTS / SHOP
|--------------------------------------------------------------------------
*/
Route::get('/produk', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/produk/{slug}', [ProductController::class, 'show'])
    ->name('products.show');


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/




Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/keranjang/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/keranjang/clear', [CartController::class, 'clear'])->name('cart.clear');





/*
|--------------------------------------------------------------------------
| CHECKOUT & ORDER
|--------------------------------------------------------------------------
*/
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/order/{code}', [OrderController::class, 'show'])
    ->name('order.show');
    Route::get('/order/{code}/print', [OrderController::class, 'print'])->name('order.print');



/*
|--------------------------------------------------------------------------
| SHIPPING (ONGKIR)
|--------------------------------------------------------------------------
*/
Route::post('/shipping/check', [ShippingController::class, 'check'])
    ->name('shipping.check');


/*
|--------------------------------------------------------------------------
| ROUTES BARU (TAMBAHAN – AMAN)
|--------------------------------------------------------------------------
| Tujuan:
| - Alias URL (lebih fleksibel)
| - SEO / user friendly
|--------------------------------------------------------------------------
*/

/* Alias bahasa Inggris (opsional, aman) */
Route::get('/shop', fn () => redirect()->route('products.index'));
Route::get('/about', fn () => redirect()->route('profile'));
Route::get('/contact-us', fn () => redirect()->route('contact'));
Route::get('/help-center', fn () => redirect()->route('help'));

/* Best Seller shortcut */
Route::get('/produk-terlaris', function () {
    return redirect()->route('products.index', ['sort' => 'best_seller']);
})->name('products.best_seller');

/* Search shortcut (optional) */
Route::get('/search', function () {
    return redirect()->route('products.index', request()->query());
})->name('products.search');
