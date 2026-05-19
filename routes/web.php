<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;

Route::get('/', [FrontendController::class, 'home'])->name('frontend.home');
Route::get('/products', [FrontendController::class, 'products'])->name('frontend.products.index');
Route::get('/products/suggestions', [FrontendController::class, 'productSuggestions'])->name('frontend.products.suggestions');
Route::get('/product/{slug}', [FrontendController::class, 'productDetails'])->name('frontend.products.show');
Route::get('/offers', [FrontendController::class, 'offers'])->name('frontend.offers');
Route::get('/contact', [FrontendController::class, 'contact'])->name('frontend.contact');

// Cart Routes
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

Auth::routes();

Route::middleware('auth')->get('/dashboard', function () {
    return Auth::user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.home');
})->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        
        Route::post('products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        
        Route::post('banners/{banner}/toggle-status', [App\Http\Controllers\Admin\BannerController::class, 'toggleStatus'])->name('banners.toggleStatus');
        Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);

        Route::post('coupons/{coupon}/toggle-status', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggleStatus');
        Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);

        Route::post('offers/{offer}/toggle-status', [App\Http\Controllers\Admin\OfferController::class, 'toggleStatus'])->name('offers.toggleStatus');
        Route::resource('offers', App\Http\Controllers\Admin\OfferController::class);

        Route::post('orders/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    });
});

Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/user/home', [UserController::class, 'home'])->name('user.home');

    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    
    Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/success', [App\Http\Controllers\OrderController::class, 'success'])->name('orders.success');
    Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});
