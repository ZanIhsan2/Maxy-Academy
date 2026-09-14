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
    $stats = [
        'products' => \App\Models\Product::count(),
        'categories' => \App\Models\Category::count(),
        'stock' => \App\Models\Product::sum('stock') ?? 0,
        'inventory_value' => (float) (\App\Models\Product::query()
            ->selectRaw('SUM(price * stock) as total_value')
            ->value('total_value') ?? 0),
        'low_stock_products' => \App\Models\Product::where('stock', '<', 10)->count(),
        'average_price' => \App\Models\Product::avg('price') ?? 0,
    ];

    $recentProducts = \App\Models\Product::with('category')
        ->latest()
        ->take(5)
        ->get();

    $categorySummary = \App\Models\Category::withCount('products')
        ->orderByDesc('products_count')
        ->take(5)
        ->get();

    return view('dashboard', compact('stats', 'recentProducts', 'categorySummary'));
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
