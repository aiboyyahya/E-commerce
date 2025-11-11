<?php

use App\Http\Controllers\GoogleLoginController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\RajaOngkirController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/login', [GoogleLoginController::class, 'loginForm'])->name('login.form');
Route::post('/login/email', [GoogleLoginController::class, 'loginEmail'])->name('login.email');
Route::get('/register', [GoogleLoginController::class, 'registerForm'])->name('register.form');
Route::post('/register/email', [GoogleLoginController::class, 'registerEmail'])->name('register.email');
Route::get('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

Route::post('/logout', [GoogleLoginController::class, 'logout'])->name('logout');

Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handleWebhook']);

Route::get('/produk', [HomeController::class, 'products'])->name('products');

Route::get('/product/{id}', [HomeController::class, 'Product'])->name('product.show');

Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');

Route::get('/rajaongkir/provinces', [RajaOngkirController::class, 'getProvinces'])->name('rajaongkir.provinces');
Route::get('/rajaongkir/cities', [RajaOngkirController::class, 'getCities'])->name('rajaongkir.cities');
Route::get('/rajaongkir/districts', [RajaOngkirController::class, 'getDistricts'])->name('rajaongkir.districts');
Route::post('/rajaongkir/cost', [RajaOngkirController::class, 'getShippingCost'])->name('rajaongkir.cost');
Route::get('/rajaongkir/couriers', [RajaOngkirController::class, 'getCouriers'])->name('rajaongkir.couriers');

Route::middleware(['auth'])->group(function () {
    Route::post('/add-to-cart', [HomeController::class, 'addToCart'])->name('addToCart');
    Route::get('/cart', [HomeController::class, 'viewCart'])->name('cart');
    Route::patch('/cart/{id}', [HomeController::class, 'updateCart'])->name('updateCart');
    Route::delete('/cart/{id}', [HomeController::class, 'removeCart'])->name('removeCart');
    Route::get('/checkout', [HomeController::class, 'checkoutPage'])->name('checkout.page');
    Route::post('/checkout', [HomeController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/payment/{id}', [HomeController::class, 'checkoutPayment'])->name('checkout.payment');
    Route::get('/checkout/success/{id}', [HomeController::class, 'checkoutSuccess'])->name('checkout.success');
    Route::get('/transaction/{id}/snap-token', [HomeController::class, 'getSnapToken'])->name('transaction.getSnapToken');
    Route::get('/pesanan', [HomeController::class, 'orders'])->name('orders');
    Route::get('/pesanan/{id}', [HomeController::class, 'orderDetail'])->name('order.detail');
    Route::delete('/pesanan/{id}', [HomeController::class, 'deleteOrder'])->name('order.delete');
    Route::get('/profile', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
});
