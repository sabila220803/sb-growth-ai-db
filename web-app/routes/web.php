<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChildController;   // Controller Data Anak
use App\Http\Controllers\GrowthController;  // Controller AI Stunting
use App\Models\Child;                       // Model Anak (PENTING)
use Illuminate\Support\Facades\Auth;

// --- HALAMAN DEPAN ---
Route::get('/', function () {
    return view('welcome');
});

// --- DASHBOARD (Dengan Data Anak) ---
Route::get('/dashboard', function () {
    // Ambil data anak milik user yang sedang login
    $children = Child::where('user_id', Auth::id())->get();
    
    // Kirim data ke tampilan dashboard
    return view('dashboard', compact('children'));
})->middleware(['auth', 'verified'])->name('dashboard');

// --- GROUP MENU (Hanya User Login) ---
Route::middleware('auth')->group(function () {
    
    // 1. Manajemen Profil (Bawaan Laravel)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2. Manajemen Data Anak (CRUD)
    // Tambah Anak
    Route::get('/anak/tambah', [ChildController::class, 'create'])->name('children.create');
    Route::post('/anak/simpan', [ChildController::class, 'store'])->name('children.store');
    
    // Lihat Riwayat (Detail)
    Route::get('/anak/riwayat/{id}', [ChildController::class, 'show'])->name('children.show');
    
    // Hapus Anak (Delete)
    Route::delete('/anak/{id}', [ChildController::class, 'destroy'])->name('children.destroy');

    // 3. Fitur Cek Stunting (AI)
    Route::get('/cek-stunting', [GrowthController::class, 'index'])->name('growth.index');
    Route::post('/analyze', [GrowthController::class, 'process'])->name('analyze.image');
});

require __DIR__.'/auth.php';