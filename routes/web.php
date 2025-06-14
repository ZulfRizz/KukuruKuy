<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Kasir\CashierController;
use App\Http\Controllers\Stok\StockController;
use App\Http\Controllers\Pengadaan\ProcurementController;

// ===== DOMAIN UTAMA UNTUK ADMIN PANEL (BIOS) =====
Route::domain('app.kukurukuy.test')->group(function () {
    // Arahkan halaman utama ke panel admin filament
    Route::get('/', function () {
        return redirect('/admin');
    });
});


// ===== DOMAIN UNTUK OPERASIONAL CABANG =====
// Semua rute di bawah ini dilindungi oleh middleware 'auth'
// yang berarti pengguna harus login untuk mengaksesnya.

// Domain untuk Kasir
Route::domain('kasir.kukurukuy.test')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('kasir.index');
    Route::post('/order', [CashierController::class, 'storeOrder'])->name('kasir.order.store');

    // Rute profil yang dibuat oleh Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('kasir.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('kasir.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('kasir.profile.destroy');
});

// ===== DOMAIN UNTUK SEMUA AKTIVITAS STOK =====
Route::domain('stok.kukurukuy.test')->middleware(['auth', 'verified'])->group(function () {
    // Rute untuk menampilkan halaman utama (melihat stok & form pengadaan)
    Route::get('/', [StockController::class, 'index'])->name('stok.index');

    // Rute untuk menyimpan permintaan pengadaan baru
    Route::post('/request', [ProcurementController::class, 'store'])->name('stok.request.store');

    // Rute profil yang dibuat oleh Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('stok.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('stok.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('stok.profile.destroy');
});


// Route fallback jika akses tanpa subdomain
Route::get('/', function () {
    // Arahkan ke halaman login kasir sebagai default
    return redirect('http://kasir.kukurukuy.test/login');
});


// Memuat rute autentikasi (login, logout, register, dll) yang dibuat oleh Breeze.
require __DIR__ . '/auth.php';
