<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;


Route::get('rekap/buku-per-kategori', [BukuController::class, 'rekapBukuPerKategori']);
Route::get('rekap/peminjaman-per-buku', [BukuController::class, 'rekapPeminjamanPerBuku']);
//Route::apiResource('buku', BukuController::class);

