<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/about', function () {
    return view('about');
});

Route::get('/services', function () {
    return view('services');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/shop/{product}', [ProductController::class, 'detail'])->name('product.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{product}', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove/{product}', [OrderController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/update', [OrderController::class, 'updateCart'])->name('cart.update');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.place');

    Route::post('/products', [ProductController::class, 'store'])->middleware('seller');
    Route::put('/products/{product}', [ProductController::class, 'update'])->middleware('seller_or_admin');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('seller_or_admin');

    Route::post('/dashboard/products', [ProductController::class, 'store'])->middleware('seller');
    Route::patch('/dashboard/orders/{order}/status', [OrderController::class, 'updateStatus'])->middleware('seller');
    Route::patch('/dashboard/orders/{order}/shipping', [OrderController::class, 'updateShipping'])->middleware('seller');

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/seller/orders', [OrderController::class, 'sellerOrders'])->middleware('seller');

    Route::post('/reviews', [ReviewController::class, 'store']);

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle']);

    Route::patch('/sellers/{seller}/approve', [AuthController::class, 'approveSeller'])->middleware('admin');

    Route::get('/admin/products/pending', [AdminController::class, 'pendingProducts'])->middleware('admin');
    Route::patch('/admin/products/{product}/approve', [AdminController::class, 'approveProductQuality'])->middleware('admin');
    Route::patch('/admin/products/{product}/reject', [AdminController::class, 'rejectProductQuality'])->middleware('admin');

    Route::get('/admin/orders', [AdminController::class, 'orders'])->middleware('admin');
    Route::get('/admin/shipments', [AdminController::class, 'shipments'])->middleware('admin');
    Route::patch('/admin/shipments/{shipment}', [AdminController::class, 'updateShipment'])->middleware('admin');
    Route::patch('/admin/payments/{payment}', [AdminController::class, 'updatePayment'])->middleware('admin');
});
