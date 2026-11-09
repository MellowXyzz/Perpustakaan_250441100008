<?php



namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Anda bisa mengembalikan view dashboard admin di sini
        // Sesuaikan 'admin.dashboard' dengan lokasi file blade dashboard admin Anda
        return view('admin.dashboard'); 
    }
    public function kelolaBuku()
    {
        return view('admin.buku');
    }

    public function laporan()
    {
        $totalBuku = Buku::count();
        $totalPengguna = User::count();
        $totalPeminjaman = Peminjaman::count();
        $peminjamanAktif = Peminjaman::where('status', '!=', 'Dikembalikan')->count();
        
        // Ambil data riwayat peminjaman lengkap dengan relasinya
        $riwayat = Peminjaman::with(['anggota', 'buku'])->latest()->get();

        return view('admin.laporan.index', compact(
            'totalBuku', 
            'totalPengguna', 
            'totalPeminjaman', 
            'peminjamanAktif', 
            'riwayat'
        ));
    }
}