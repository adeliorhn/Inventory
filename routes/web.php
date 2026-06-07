<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::post('/barang', [ItemController::class, 'store'])->name('items.store');
Route::put('/barang/{item}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/barang/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

Route::post('/mutasi-stok', [StockMovementController::class, 'store'])->name('stock-movements.store');

Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
Route::get('/laporan/cetak', [ReportController::class, 'print'])->name('reports.print');

Route::post('/komunikasi', [NotificationController::class, 'storeMessage'])->name('messages.store');
Route::post('/notifikasi/{alert}/baca', [NotificationController::class, 'markAlertRead'])->name('alerts.read');
Route::post('/komunikasi/{message}/selesai', [NotificationController::class, 'resolveMessage'])->name('messages.resolve');
