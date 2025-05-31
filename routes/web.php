<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('categories', CategoryController::class);

Route::resource('stores', StoreController::class);

Route::resource('products', ProductController::class);

Route::resource('reviews', ReviewController::class);

Route::post('/favorites/add/{store}', [FavoriteController::class, 'add'])->name('favorites.add');
Route::delete('/favorites/remove/{store}', [FavoriteController::class, 'remove'])->name('favorites.remove');
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');