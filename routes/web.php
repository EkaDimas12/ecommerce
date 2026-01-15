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
  Route::get('/register', fn() => redirect()->route('signup'))->name('register');
    Route::post('/signup', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// Logout route (requires auth)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User order history
Route::get('/pesanan-saya', [OrderController::class, 'history'])->name('orders.history')->middleware('auth');

// User settings
Route::middleware('auth')->group(function () {
    Route::get('/pengaturan', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/pengaturan/profil', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/pengaturan/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password');
});



/*
|--------------------------------------------------------------------------
| ORDER
|--------------------------------------------------------------------------
*/
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




Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index')->middleware('auth');
Route::post('/keranjang/add', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::post('/keranjang/remove', [CartController::class, 'remove'])->name('cart.remove')->middleware('auth');
Route::post('/keranjang/clear', [CartController::class, 'clear'])->name('cart.clear')->middleware('auth');





/*
|--------------------------------------------------------------------------
| CHECKOUT & ORDER
|--------------------------------------------------------------------------
*/
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index')
    ->middleware('auth');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store')
    ->middleware('auth');

Route::get('/order/{code}', [OrderController::class, 'show'])
    ->name('order.show');
Route::get('/order/{code}/print', [OrderController::class, 'print'])->name('order.print');
Route::post('/order/{code}/cancel', [OrderController::class, 'cancel'])->name('order.cancel')->middleware('auth');



/*
|--------------------------------------------------------------------------
| SHIPPING (ONGKIR) - Komerce.id API
|--------------------------------------------------------------------------
*/
Route::prefix('shipping')->name('shipping.')->group(function () {
    Route::get('/provinces', [ShippingController::class, 'provinces'])->name('provinces');
    Route::get('/search', [ShippingController::class, 'searchDestination'])->name('search');
    Route::get('/couriers', [ShippingController::class, 'couriers'])->name('couriers');
    Route::post('/check', [ShippingController::class, 'check'])->name('check');
    Route::post('/track', [ShippingController::class, 'track'])->name('track');
    Route::get('/debug', [ShippingController::class, 'debug'])->name('debug');
});


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


/*
|--------------------------------------------------------------------------
| CUSTOM ADMIN
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\TestimonialAdminController;

Route::prefix('custom-admin')->middleware('auth')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard', [
            'productCount' => \App\Models\Product::count(),
            'categoryCount' => \App\Models\Category::count(),
            'orderCount' => \App\Models\Order::count(),
            'userCount' => \App\Models\User::count(),
            'testimonialCount' => \App\Models\Testimonial::count(),
            'pendingOrders' => \App\Models\Order::where('payment_status', 'pending')->count(),
        ]);
    })->name('dashboard');

    // Categories
    Route::get('/categories', [CategoryAdminController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryAdminController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryAdminController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryAdminController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryAdminController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryAdminController::class, 'destroy'])->name('categories.destroy');

    // Products
    Route::get('/products', [ProductAdminController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductAdminController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductAdminController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductAdminController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductAdminController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductAdminController::class, 'destroy'])->name('products.destroy');

    // Orders
    Route::get('/orders', [OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderAdminController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderAdminController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderAdminController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderAdminController::class, 'destroy'])->name('orders.destroy');

    // Users
    Route::get('/users', [UserAdminController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserAdminController::class, 'create'])->name('users.create');
    Route::post('/users', [UserAdminController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserAdminController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserAdminController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserAdminController::class, 'destroy'])->name('users.destroy');

    // Testimonials
    Route::get('/testimonials', [TestimonialAdminController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [TestimonialAdminController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [TestimonialAdminController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [TestimonialAdminController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [TestimonialAdminController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialAdminController::class, 'destroy'])->name('testimonials.destroy');
});

