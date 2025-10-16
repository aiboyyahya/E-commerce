<?php

use App\Http\Controllers\GoogleLoginController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');

Route::get('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

Route::post('/logout', [GoogleLoginController::class, 'logout'])->name('logout');

Route::get('/product/{id}', [HomeController::class, 'showProduct'])->name('product.show');

Route::post('/add-to-cart', [HomeController::class, 'addToCart'])->name('addToCart');
Route::get('/cart', [HomeController::class, 'viewCart'])->name('cart');
Route::delete('/cart/{id}', [HomeController::class, 'removeCart'])->name('removeCart');
