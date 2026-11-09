<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\User; // <-- Pastikan model User sudah di-import di atas
use App\Models\Peminjaman;

class PetugasController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $peminjamanAktif = Peminjaman::whereNull('tanggal_kembali')->count();

        return view('petugas.dashboard', compact('totalBuku', 'peminjamanAktif'));
    }

    public function laporan()
    {
        $totalBuku = Buku::count();
        $totalPengguna = User::count(); // <-- TAMBAHKAN BARIS INI
        $totalPeminjaman = Peminjaman::count();
        $peminjamanAktif = Peminjaman::whereNull('tanggal_kembali')->count();
        $riwayat = Peminjaman::with(['anggota', 'buku'])->latest()->get();

        return view('petugas.laporan', compact(
            'totalBuku', 
            'totalPengguna', // <-- PASTIKAN DISERTAKAN DI COMPACT
            'totalPeminjaman', 
            'peminjamanAktif', 
            'riwayat'
        ));
    }
}