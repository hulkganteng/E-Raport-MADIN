<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\PublicRapotController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public access: cek nilai santri tanpa login (rate limited to prevent brute-force)
Route::middleware(['throttle:5,1'])->group(function () {
    Route::get('/cek-nilai', [PublicRapotController::class, 'showForm'])->name('public.cek_nilai');
    Route::post('/cek-nilai', [PublicRapotController::class, 'check'])->name('public.cek_nilai.check');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Only: Create, Edit, Update, Destroy (Defined FIRST to prevent shadowing by show/{id})
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('mapel', MapelController::class)->except(['index', 'show']);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas'])->except(['index', 'show']);
        
        // Wali Kelas & Mapel Management
        Route::get('kelas-wali/manage', [KelasController::class, 'manageWali'])->name('kelas.manage_wali');
        Route::post('kelas-wali/update', [KelasController::class, 'updateWali'])->name('kelas.update_wali');
        Route::get('kelas/{kelas}/assign-wali', [KelasController::class, 'editWali'])->name('kelas.edit_wali');
        Route::put('kelas/{kelas}/assign-wali', [KelasController::class, 'updateWaliSingle'])->name('kelas.update_wali_single');
        Route::get('kelas/{kelas}/mapel', [KelasController::class, 'manageMapel'])->name('kelas.manage_mapel');
        Route::put('kelas/{kelas}/mapel', [KelasController::class, 'updateMapel'])->name('kelas.update_mapel');
        
        Route::resource('santri', SantriController::class)->except(['index', 'show']);
        
        Route::resource('periode', \App\Http\Controllers\PeriodeController::class)->except(['show']);
        Route::patch('/periode/{periode}/activate', [\App\Http\Controllers\PeriodeController::class, 'activate'])->name('periode.activate');
        
        Route::resource('users', UserController::class)->except(['show']);

        // Kenaikan Kelas & Kelulusan
        // Kenaikan Kelas & Kelulusan (Require Active Period)
        Route::middleware(['active_period'])->group(function () {
             Route::get('/kenaikan', [App\Http\Controllers\KenaikanKelasController::class, 'index'])->name('kenaikan.index');
             Route::get('/kenaikan/{kelas}', [App\Http\Controllers\KenaikanKelasController::class, 'show'])->name('kenaikan.show');
             Route::post('/kenaikan/{kelas}', [App\Http\Controllers\KenaikanKelasController::class, 'process'])->name('kenaikan.process');
        });
    });

    // Public Read-Only for Authenticated Users (Index/Show) or Specific Input Routes
    Route::resource('mapel', MapelController::class)->only(['index']);
    Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas'])->only(['index']);
    Route::resource('santri', SantriController::class)->only(['index']);

    Route::middleware(['active_period'])->group(function () {
        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::get('/nilai/input/{kelas_mapel}', [NilaiController::class, 'input'])->name('nilai.input');
        Route::post('/nilai/store/{kelas_mapel}', [NilaiController::class, 'store'])->name('nilai.store');
        
        Route::get('/rekap/kelas/{kelas}', [RekapController::class, 'indexByKelas'])->name('rekap.index');
        Route::post('/rekap/ranking/{kelas}', [RekapController::class, 'generateRanking'])->name('rekap.ranking');
        Route::put('/rekap/update/{rekap}', [RekapController::class, 'update'])->name('rekap.update');
    });
    Route::get('/rekap/print/{santri}', [RekapController::class, 'printRapot'])->name('rekap.print');
    Route::get('/rekap/print-all/{kelas}', [RekapController::class, 'printAllRapot'])->name('rekap.print_all');
    
    // User Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
