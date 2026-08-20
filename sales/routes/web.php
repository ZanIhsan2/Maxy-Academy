<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route dibungkus middleware auth dan permission Spatie
Route::middleware(['auth', 'permission:manage-purchase'])->group(function () {
    Route::get('/purchase-order', [PurchaseOrderController::class, 'index'])->name('purchase-order.index');
    Route::get('/purchase-order/create', [PurchaseOrderController::class, 'create'])->name('purchase-order.create');
    Route::post('/purchase-order', [PurchaseOrderController::class, 'store'])->name('purchase-order.store');
    Route::get('/purchase-order/{id}', [PurchaseOrderController::class, 'show'])->name('purchase-order.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
    Route::get('/categories/data', [CategoriesController::class, 'data'])->name('categories.data');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/data', [ProductsController::class, 'data'])->name('products.data');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductsController::class, 'destroy'])->name('products.destroy');
});

require __DIR__ . '/auth.php';
