<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\KategoriController;
 use App\Http\Controllers\LaporanController;

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
    Route::post('/transaksi',       [TransaksiController::class, 'store'])
    ->middleware('transaksi.limit')
    ->name('transaksi.store');

    // Budget
    Route::get('/budget',          [BudgetController::class, 'index'])  ->name('budget.index');
    Route::post('/budget',         [BudgetController::class, 'store'])  ->name('budget.store');
    Route::delete('/budget/{id}',  [BudgetController::class, 'destroy'])->name('budget.destroy');

    // Kategori
    Route::get('/kategori',         [KategoriController::class, 'index'])  ->name('kategori.index');
    Route::post('/kategori',        [KategoriController::class, 'store'])  ->name('kategori.store');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
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
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/',                              [AdminController::class, 'index'])         ->name('index');
    Route::get('/payments/{payment}',            [AdminController::class, 'paymentDetail']) ->name('payment.detail');
    Route::post('/payments/{payment}/confirm',   [AdminController::class, 'confirm'])       ->name('payment.confirm');
    Route::post('/payments/{payment}/reject',    [AdminController::class, 'reject'])        ->name('payment.reject');
    Route::get('/users',                         [AdminController::class, 'users'])         ->name('users');
    Route::post('/users/{user}/suspend',         [AdminController::class, 'suspend'])       ->name('user.suspend');
    Route::post('/users/{user}/unsuspend',       [AdminController::class, 'unsuspend'])     ->name('user.unsuspend');
    Route::post('/users/{user}/reset-password',  [AdminController::class, 'resetPassword']) ->name('user.reset-password');

});