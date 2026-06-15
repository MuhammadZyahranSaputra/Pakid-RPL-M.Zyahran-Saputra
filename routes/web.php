<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Karya;
use App\Http\Controllers\KaryaController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Middleware auth bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/karya/{id}/vote', [KaryaController::class, 'toggleVote'])->name('karya.vote');

    Route::get('/karya/{id}', [KaryaController::class, 'show'])->name('karya.show');
    Route::post('/karya/{id}/komentar', [KaryaController::class, 'storeKomentar'])->name('karya.komentar.store');
    Route::get('/leaderboard', [KaryaController::class, 'leaderboard'])->name('leaderboard');
});

// --- IMPLEMENTASI MULTI-ROLE PAKID --- //

// Route Khusus Admin (Satpam Pameran)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/update-token', [AdminController::class, 'updateToken'])->name('admin.token.update');
    Route::get('/admin/export-laporan', [AdminController::class, 'exportLaporanPdf'])->name('admin.laporan.pdf');
    
    Route::delete('/admin/karya/{id}', [AdminController::class, 'destroyKarya'])->name('admin.karya.destroy');
    Route::delete('/admin/komentar/{id}', [AdminController::class, 'destroyKomentar'])->name('admin.komentar.destroy');
});

// Route Khusus Peserta
Route::middleware(['auth', 'role:peserta'])->group(function () {
    Route::get('/peserta/galeri', [KaryaController::class, 'galeriPribadi'])->name('peserta.galeri');
    Route::get('/peserta/upload', [KaryaController::class, 'create'])->name('peserta.upload');
    Route::post('/peserta/upload', [KaryaController::class, 'store'])->name('peserta.karya.store');
    Route::get('/peserta/karya/{karya}/edit', [KaryaController::class, 'edit'])->name('karya.edit');
    Route::post('/peserta/karya/{karya}/update', [KaryaController::class, 'update'])->name('karya.update');
    Route::delete('/peserta/karya/{karya}', [KaryaController::class, 'destroy'])->name('peserta.karya.destroy');
});

// Dashboard Galeri Umum (Akses untuk Semua Role yang Login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $userId = auth()->id();

        $karyas = Karya::with(['kategori', 'user'])
            ->withCount('votes')
            ->with(['votes' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->latest()
            ->paginate(9);
        
        return Inertia::render('Dashboard', [
            'karyas' => $karyas
        ]);
    })->name('dashboard');
});



require __DIR__.'/auth.php';