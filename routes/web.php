<?php

use App\Http\Controllers\GoogleLoginController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', function () {
    return redirect()->route('google.redirect');
})->name('login');

Route::get('/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');

Route::get('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

Route::post('/logout', [GoogleLoginController::class, 'logout'])->name('logout');

Route::get('/produk', [HomeController::class, 'products'])->name('products');

Route::get('/product/{id}', [HomeController::class, 'Product'])->name('product.show');

Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');

Route::middleware(['auth'])->group(function () {
    Route::post('/add-to-cart', [HomeController::class, 'addToCart'])->name('addToCart');
    Route::get('/cart', [HomeController::class, 'viewCart'])->name('cart');
    Route::patch('/cart/{id}', [HomeController::class, 'updateCart'])->name('updateCart');
    Route::delete('/cart/{id}', [HomeController::class, 'removeCart'])->name('removeCart');
    Route::get('/checkout', [HomeController::class, 'checkoutPage'])->name('checkout.page');
    Route::post('/checkout', [HomeController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/success/{id}', [HomeController::class, 'checkoutSuccess'])->name('checkout.success');
    Route::get('/pesanan', [HomeController::class, 'orders'])->name('orders');
    Route::get('/pesanan/{id}', [HomeController::class, 'orderDetail'])->name('order.detail');
    Route::delete('/pesanan/{id}', [HomeController::class, 'deleteOrder'])->name('order.delete');
    Route::get('/profile', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
});
