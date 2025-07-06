
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Arsip routes
    Route::get('arsip/export', [ArsipController::class, 'export'])->name('arsip.export');
    Route::get('arsip/{arsip}/preview', [ArsipController::class, 'preview'])->name('arsip.preview');
    Route::get('arsip/{arsip}/download', [ArsipController::class, 'download'])->name('arsip.download');
    Route::resource('arsip', ArsipController::class);

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('divisi', DivisiController::class)->except(['create', 'edit', 'show']);
        Route::resource('kategori', KategoriController::class)->except(['create', 'edit', 'show']);
    });
});

require __DIR__.'/auth.php';
