<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountController;

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

Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/reviews', [ReviewController::class, 'userReviews'])->name('user.reviews');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'editUserReview'])->name('user.reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'updateUserReview'])->name('user.reviews.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/stores', [AdminController::class, 'stores'])->name('admin.stores');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/stores/{store}/update-status', [StoreController::class, 'updateStatus'])->name('stores.update-status');
    Route::post('/users/{user}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
    Route::post('/users/{user}/unban', [AdminController::class, 'unbanUser'])->name('admin.users.unban');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});

Route::middleware(['auth'])->prefix('vendor')->group(function () {
    Route::get('/my-store', [StoreController::class, 'myStore'])->name('vendor.store');
    Route::get('/products', [ProductController::class, 'vendorProducts'])->name('vendor.products');
    Route::get('/reviews', [ReviewController::class, 'vendorReviews'])->name('vendor.reviews');
});

Route::middleware('auth')->group(function () {
    Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');
    Route::put('/account/settings', [AccountController::class, 'update'])->name('account.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password');
});