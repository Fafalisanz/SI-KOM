<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BorrowingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // PENTING: route statis (index, create) HARUS didaftarkan SEBELUM route dengan wildcard ({product})
    // Kalau tidak, "/products/create" akan tertangkap oleh pola "/products/{product}" duluan

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');

    // Hanya Admin (1) & Staff (2) yang boleh Tambah/Edit/Hapus
    Route::middleware('role:1,2')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
        Route::get('/borrowings/{borrowing}/edit', [BorrowingController::class, 'edit'])->name('borrowings.edit');
        Route::put('/borrowings/{borrowing}', [BorrowingController::class, 'update'])->name('borrowings.update');
        Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return');
        Route::delete('/borrowings/{borrowing}', [BorrowingController::class, 'destroy'])->name('borrowings.destroy');
    });

    // Route show (wildcard) DITARUH DI PALING BAWAH, setelah semua route statis di atasnya
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');

});

require __DIR__.'/auth.php';