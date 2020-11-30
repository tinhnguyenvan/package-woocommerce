<?php

use Illuminate\Support\Facades\Route;
use TinhPHP\Woocommerce\Http\Controllers\ProductCategoryController;
use TinhPHP\Woocommerce\Http\Controllers\ProductController;
use TinhPHP\Woocommerce\Http\Controllers\CartController;

Route::get('collections/{slugProductCategory}/{slugProduct}.html', [ProductController::class, 'view']);
Route::get('collections/{slugProduct}.html', [ProductController::class, 'view']);
Route::get('collections/{slugCategory}', [ProductController::class, 'index']);
Route::get('collections', [ProductController::class, 'index']);

// cart
Route::get('cart', [CartController::class, 'index']);
Route::get('cart/delete/{id}', [CartController::class, 'delete']);
Route::get('cart/checkout/{token_checkout}', [CartController::class, 'checkout']);
Route::get('cart/checkout-success/{token_checkout}', [CartController::class, 'checkoutSuccess']);
Route::get('cart/checkout-error/{token_checkout}', [CartController::class, 'checkoutError']);
Route::post('cart/add', [CartController::class, 'add']);
Route::post('cart/checkout/{token_checkout}', [CartController::class, 'checkoutSave']);
