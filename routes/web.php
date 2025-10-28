<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MeasureUnitController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::middleware(['auth'])->prefix('categories')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index')->name('categories.index');
    Route::get('/{id}', 'show')->name('categories.show');
    Route::post('/', 'store')->name('categories.store');
    Route::put('/{id}', 'update')->name('categories.update');
    Route::delete('/{id}', 'destroy')->name('categories.destroy');
});

Route::middleware(['auth'])->prefix('measure-units')->controller(MeasureUnitController::class)->group(function () {
    Route::get('/', 'index')->name('measure-units.index');
    Route::get('/{id}', 'show')->name('measure-units.show');
    Route::post('/', 'store')->name('measure-units.store');
    Route::put('/{id}', 'update')->name('measure-units.update');
    Route::delete('/{id}', 'destroy')->name('measure-units.destroy');
});
