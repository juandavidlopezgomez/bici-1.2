<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseInvoiceController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para Productos (Frontend - Inertia)
    Route::get('/products/create', function () {
        return Inertia::render('Products/Create');
    })->name('products.create');

    Route::get('/products', function () {
        return Inertia::render('Products/Index');
    })->name('products.index');

    // Rutas para Categorías (Frontend - Inertia)
    Route::get('/categories', function () {
        return Inertia::render('Categories/Index');
    })->name('categories.index');

    // Rutas para Proveedores (Frontend - Inertia)
    Route::get('/suppliers', function () {
        return Inertia::render('Suppliers/Index');
    })->name('suppliers.index');

    // Rutas API (Backend - AJAX)
    Route::resource('products', ProductController::class)->except(['create', 'edit']);
    Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
    Route::resource('suppliers', SupplierController::class)->except(['create', 'edit']);
    Route::resource('purchase-invoices', PurchaseInvoiceController::class)->except(['create', 'edit']);
});

require __DIR__.'/auth.php';