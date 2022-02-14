<?php

use Illuminate\Support\Facades\Route;
use TinhPHP\Woocommerce\Http\Controllers\Admin\ProductCategoryController;
use TinhPHP\Woocommerce\Http\Controllers\Admin\ProductController;
use TinhPHP\Woocommerce\Http\Controllers\Admin\OrderController;
use TinhPHP\Woocommerce\Http\Controllers\Admin\SettingController;

Route::group(
    [
        'prefix' => 'admin/woocommerce'
    ],
    function () {
        // products
        Route::get('/', [ProductController::class, 'index'])->name('admin.products.index.core');

        Route::get('products', [ProductController::class, 'index'])->name('admin.products.index');
        Route::get('products/create', [ProductController::class, 'create'])->name('admin.products.create');
        Route::post('products', [ProductController::class, 'store'])->name('admin.products.store');
        Route::get('products/{id}', [ProductController::class, 'show'])->name('admin.products.show');
        Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::patch('products/{id}', [ProductController::class, 'update'])->name('admin.products.update.patch');
        Route::delete('products/destroy-multi', [ProductController::class, 'destroyMulti'])->name('admin.products.destroy-multi');
        Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

        // api product
        Route::get('api/products', [ProductController::class, 'apiProduct'])->name('admin.api.products.index');

        // api order
        Route::get('api/orders/find-info', [OrderController::class, 'apiOrderFindInfo'])
            ->name('admin.api.orders.find-info');

        // product_categories
        Route::get('product_categories', [ProductCategoryController::class, 'index'])->name('admin.product_categories.index');
        Route::get('product_categories/create', [ProductCategoryController::class, 'create'])->name('admin.product_categories.create');
        Route::post('product_categories', [ProductCategoryController::class, 'store'])->name('admin.product_categories.store');
        Route::get('product_categories/{id}', [ProductCategoryController::class, 'show'])->name('admin.product_categories.show');
        Route::get('product_categories/{id}/edit', [ProductCategoryController::class, 'edit'])->name('admin.product_categories.edit');
        Route::put('product_categories/{id}', [ProductCategoryController::class, 'update'])->name('admin.product_categories.update');
        Route::patch('product_categories/{id}', [ProductCategoryController::class, 'update'])->name('admin.product_categories.update.patch');
        Route::delete('product_categories/{id}', [ProductCategoryController::class, 'destroy'])->name('admin.product_categories.destroy');

        // orders
        Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('orders/report', [OrderController::class, 'report'])->name('admin.orders.report');
        Route::get('orders/get-report', [OrderController::class, 'getReport'])->name('admin.orders.get-report');
        Route::get('orders/create', [OrderController::class, 'create'])->name('admin.orders.create');
        Route::get('orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::get('orders/{id}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
        Route::post('orders', [OrderController::class, 'store'])->name('admin.orders.store');
        Route::post('orders/reorder/{id}', [OrderController::class, 'reorder'])->name('admin.reorder.store');
        Route::post('orders/resent-mail/{id}', [OrderController::class, 'resentMail'])->name('admin.resent-mail.store');
        Route::post('orders/status/{id}', [OrderController::class, 'status'])->name('admin.status.store');
        Route::put('orders/{id}', [OrderController::class, 'update'])->name('admin.orders.update');
        Route::patch('orders/{id}', [OrderController::class, 'update'])->name('admin.orders.update.patch');
        Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

        // settings
        Route::get('settings', [SettingController::class, 'index'])->name('woo.admin.settings.index');
        Route::post('settings/save', [SettingController::class, 'save'])->name('woo.admin.settings.save');
    }
);
