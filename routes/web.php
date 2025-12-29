<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MeasureUnitController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;

Route::get('/', function () {
    return redirect()->route('sales.index');
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::middleware(['auth'])->prefix('/categories')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index')->name('categories.index');
    /* Route::get('/{id}', 'show')->name('categories.show'); */
    Route::post('/', 'store')->name('categories.store');
    Route::put('/{id}', 'update')->name('categories.update');
    Route::delete('/{id}', 'destroy')->name('categories.destroy');
});

Route::middleware(['auth'])->prefix('/measure-units')->controller(MeasureUnitController::class)->group(function () {
    Route::get('/', 'index')->name('measure-units.index');
    /* Route::get('/{id}', 'show')->name('measure-units.show'); */
    Route::post('/', 'store')->name('measure-units.store');
    Route::put('/{id}', 'update')->name('measure-units.update');
    Route::delete('/{id}', 'destroy')->name('measure-units.destroy');
});

Route::middleware(['auth'])->prefix('/providers')->controller(ProviderController::class)->group(function () {
    Route::get('/', 'index')->name('providers.index');
    /* Route::get('/{id}', 'show')->name('providers.show'); */
    Route::post('/', 'store')->name('providers.store');
    Route::put('/{id}', 'update')->name('providers.update');
    Route::delete('/{id}', 'destroy')->name('providers.destroy');
});

Route::middleware(['auth'])->prefix('/products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index')->name('products.index');
    Route::get('/search', 'search')->name('products.search');
    Route::get('/import', 'importForm')->name('products.import');
    /* Route::get('/{id}', 'show')->name('products.show'); */
    Route::post('/', 'store')->name('products.store');
    Route::post('/import', 'import')->name('products.import.process');
    Route::put('/{id}', 'update')->name('products.update');
    Route::delete('/{id}', 'destroy')->name('products.destroy');
});

Route::middleware(['auth'])->prefix('/purchases')->controller(PurchaseController::class)->group(function () {
    Route::get('/', 'index')->name('purchases.index');
    Route::get('/{id}', 'show')->name('purchases.show');
    Route::post('/', 'store')->name('purchases.store');
    Route::put('/{id}', 'update')->name('purchases.update');
    Route::delete('/{id}', 'destroy')->name('purchases.destroy');
});

Route::middleware(['auth'])->prefix('/sales')->controller(SaleController::class)->group(function () {
    Route::get('/', 'index')->name('sales.index');
    Route::get('/{id}', 'show')->name('sales.show');
    Route::post('/', 'store')->name('sales.store');
    Route::put('/{id}', 'update')->name('sales.update');
    Route::delete('/{id}', 'destroy')->name('sales.destroy');
});
