<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
});

/*
|--------------------------------------------------------------------------
| AUTH (BREEZE)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| PAYMENT — tidak perlu login (user belum punya password)
|--------------------------------------------------------------------------
*/
Route::prefix('payment')->name('payment.')->group(function () {
    // Halaman instruksi bayar + form upload
    Route::get('/{user}',           [PaymentController::class, 'show'])   ->name('show');
    // Proses upload bukti
    Route::post('/{user}/upload',   [PaymentController::class, 'upload']) ->name('upload');
    // Halaman status pembayaran
    Route::get('/{user}/status',    [PaymentController::class, 'status']) ->name('status');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD — harus login & status active
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'plan.active'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Transaksi
    Route::get('/transaksi',        [TransaksiController::class, 'index']) ->name('transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi',       [TransaksiController::class, 'store']) ->name('transaksi.store');

});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])   ->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update']) ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});