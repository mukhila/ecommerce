<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\CategoryController;

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('products', ProductController::class)->except(['index', 'show'])->names('product');
});

// Public routes
Route::get('products', [ProductController::class, 'index'])->name('product.index');
Route::get('products/{product:slug}', [ProductController::class, 'show'])->name('product.show');

// Category routes
Route::get('category', [CategoryController::class, 'index'])->name('category.index');
Route::get('category/{slug}', [CategoryController::class, 'show'])->name('category.show');
