<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Models\User;
use Illuminate\Support\Facades\Route;



Route::middleware('auth')->group(function() {
    Route::get('/users', [AuthController::class, 'index'])->name('users');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::inertia('/', 'Home')->name('home');
Route::inertia('/welcome', 'Welcome')->name('welcome');

Route::get('token-response', [ProductController::class, 'fetchProducts'])->name('token.response');

Route::get('/product-details', [ProductController::class, 'show'])->name('products.show');

Route::middleware('guest')->group(function() {
    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);

});
