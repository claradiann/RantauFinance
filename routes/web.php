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
| PAYMENT
|--------------------------------------------------------------------------
*/
Route::prefix('payment')->name('payment.')->group(function () {
    // Halaman instruksi bayar + form upload (Guest)
    Route::get('/',           [PaymentController::class, 'show'])   ->name('show');
    // Proses upload bukti (Guest)
    Route::post('/upload',    [PaymentController::class, 'upload']) ->name('upload');

    // Upgrade plan (Harus Login)
    Route::middleware('auth')->get('/upgrade/{plan}', [PaymentController::class, 'upgrade'])->name('upgrade');

    // Halaman status pembayaran (Harus Login & Signed)
    Route::middleware(['auth', 'signed'])->get('/{user}/status', [PaymentController::class, 'status'])->name('status');
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
    Route::get('/transaksi',            [TransaksiController::class, 'index'])   ->name('transaksi.index');
    Route::get('/transaksi/export/csv', [TransaksiController::class, 'exportCSV'])->name('transaksi.export.csv');
    Route::get('/transaksi/export/pdf', [TransaksiController::class, 'exportPDF'])->name('transaksi.export.pdf');
    Route::get('/transaksi/create',     [TransaksiController::class, 'create'])  ->name('transaksi.create');
    Route::post('/transaksi',           [TransaksiController::class, 'store'])   ->middleware('transaksi.limit')->name('transaksi.store');
    Route::get('/transaksi/{id}/edit',  [TransaksiController::class, 'edit'])    ->name('transaksi.edit');
    Route::put('/transaksi/{id}',       [TransaksiController::class, 'update'])  ->name('transaksi.update');
    Route::delete('/transaksi/{id}',    [TransaksiController::class, 'destroy']) ->name('transaksi.destroy');

    // Budget (Personal & Profesional only)
    Route::middleware('feature:budget_planner')->group(function () {
        Route::get('/budget',          [BudgetController::class, 'index'])  ->name('budget.index');
        Route::post('/budget',         [BudgetController::class, 'store'])  ->name('budget.store');
        Route::delete('/budget/{id}',  [BudgetController::class, 'destroy'])->name('budget.destroy');
    });

    // Kategori
    Route::get('/kategori',         [KategoriController::class, 'index'])  ->name('kategori.index');
    Route::post('/kategori',        [KategoriController::class, 'store'])  ->name('kategori.store');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Laporan (Personal & Profesional only)
    Route::get('/laporan', [LaporanController::class, 'index'])->middleware('feature:laporan_bulanan_detail')->name('laporan.index');
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
    Route::patch('/users/{user}/change-plan',    [AdminController::class, 'changePlan'])    ->name('user.change-plan');
});