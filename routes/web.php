<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Authenticated Routes (Multi-Page Architecture)
Route::middleware(['auth'])->group(function () {
    // 1. Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // 2. Master Produk
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // 3. Stok Produk
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');

    // 4. Riwayat Mutasi Stok
    Route::get('/stock-history', [StockHistoryController::class, 'index'])->name('stock-history.index');

    // 5. Kategori Produk
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    // 6. Media Manager / Upload Gambar MinIO
    Route::get('/uploads', [UploadController::class, 'index'])->name('uploads.index');

    // 7. Laporan & Cetak PDF
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/cetak', [ReportController::class, 'print'])->name('reports.print');

    // 8. Pengaturan System & Profile Admin
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

    // Actions & CRUD Routes
    Route::post('/barang', [ItemController::class, 'store'])->name('items.store');
    Route::put('/barang/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::patch('/barang/{item}/toggle-status', [ItemController::class, 'toggleStatus'])->name('items.toggle-status');
    Route::delete('/barang/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    Route::post('/mutasi-stok', [StockMovementController::class, 'store'])->name('stock-movements.store');

    Route::post('/komunikasi', [NotificationController::class, 'storeMessage'])->name('messages.store');
    Route::post('/notifikasi/{alert}/baca', [NotificationController::class, 'markAlertRead'])->name('alerts.read');
    Route::post('/komunikasi/{message}/selesai', [NotificationController::class, 'resolveMessage'])->name('messages.resolve');

    Route::post('/theme', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);
        
        $theme = $request->input('theme');
        
        if (auth()->check()) {
            auth()->user()->update(['theme' => $theme]);
        }
        
        return response()->json(['success' => true])
            ->cookie('theme', $theme, 60 * 24 * 365, null, null, false, false);
    })->name('theme.update');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
