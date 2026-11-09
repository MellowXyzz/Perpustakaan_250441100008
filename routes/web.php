<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    if (method_exists($user, 'hasRole') && $user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (method_exists($user, 'hasRole') && $user->hasRole('Petugas')) {
        return redirect()->route('petugas.dashboard');
    }

    return redirect()->route('buku.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    
    // Rute utama daftar buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index')
         ->middleware('permission:lihat daftar buku');

    // --- TAMBAHKAN RUTE DETAIL BUKU INI ---
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

    // rute a2
    Route::get('/buku-cari', [BukuController::class, 'cari']);

    //rute a3
    Route::get('/buku/{buku}/hapus', [App\Http\Controllers\BukuController::class, 'destroy'])->middleware('auth');

    //rute a4
    //Route::get('/riwayat/{id}', [BukuController::class, 'riwayatPeminjaman'])->name('riwayat.saya');

    // Rute ajukan pinjam
    Route::post('/pinjam/{id}', [BukuController::class, 'storePinjam'])->name('buku.pinjam');

    // Admin & Petugas (Kelola Data Buku: Simpan, Ubah, Hapus)
    Route::middleware(['permission:kelola data buku'])->group(function () {
        Route::post('/buku', [BukuController::class, 'store']);
        Route::put('/buku/{buku}', [BukuController::class, 'update']);
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy']);
    });

    // --- KHUSUS ADMIN ---
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/buku', [AdminController::class, 'kelolaBuku'])->name('admin.buku');
        
        // Rute Laporan Perpustakaan Admin
        Route::get('/admin/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
        
        // Rute Kelola Pengguna
        Route::resource('/pengguna', PenggunaController::class);

        // Rute Kelola Peminjaman Admin
       // Rute Kelola Peminjaman Admin
        Route::get('/admin/peminjaman', [PeminjamanController::class, 'index'])->name('admin.peminjaman.index');
        Route::get('/admin/peminjaman/tambah', [PeminjamanController::class, 'create'])->name('admin.peminjaman.create');
        Route::post('/admin/peminjaman', [PeminjamanController::class, 'store'])->name('admin.peminjaman.store');
        Route::put('/admin/peminjaman/{id}', [PeminjamanController::class, 'updateStatus'])->name('admin.peminjaman.update');
        Route::delete('/admin/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('admin.peminjaman.destroy');
    });

    // --- KHUSUS PETUGAS ---
    Route::middleware(['role:Petugas'])->group(function () {
        Route::get('/petugas/dashboard', [PetugasController::class, 'index'])->name('petugas.dashboard');
        
        // Petugas bisa melihat laporan dan mengelola peminjaman/pengembalian
        Route::get('/petugas/laporan', [PetugasController::class, 'laporan'])->name('petugas.laporan');
        
        // Memanfaatkan PeminjamanController yang sudah ada untuk kelola pinjam & pengembalian
       // Rute Kelola Peminjaman Petugas
        Route::get('/petugas/peminjaman', [PeminjamanController::class, 'index'])->name('petugas.peminjaman.index');
        Route::get('/petugas/peminjaman/tambah', [PeminjamanController::class, 'create'])->name('petugas.peminjaman.create');
        Route::post('/petugas/peminjaman', [PeminjamanController::class, 'store'])->name('petugas.peminjaman.store');
        Route::put('/petugas/peminjaman/{id}', [PeminjamanController::class, 'updateStatus'])->name('petugas.peminjaman.update');
    });

    // --- ADMIN & PETUGAS (Kelola Data Buku) ---
    Route::middleware(['permission:kelola data buku'])->group(function () {
        Route::post('/buku', [BukuController::class, 'store']);
        Route::put('/buku/{buku}', [BukuController::class, 'update']);
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy']);
    });

    // --- KHUSUS SISWA ---
    Route::middleware(['role:Siswa'])->group(function () {
        // Halaman dashboard siswa dialihkan langsung ke daftar buku atau halaman khusus siswa
        Route::get('/siswa/dashboard', function() {
            return redirect()->route('buku.index');
        })->name('siswa.dashboard');

        // Riwayat peminjaman pribadi siswa
        Route::get('/riwayat/{id}', [BukuController::class, 'riwayatPeminjaman'])->name('riwayat.saya');
    });
});

require __DIR__.'/auth.php';