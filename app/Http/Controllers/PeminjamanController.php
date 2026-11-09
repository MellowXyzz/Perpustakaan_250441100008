<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman; // Sesuaikan dengan nama model peminjaman Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // Menampilkan halaman / data peminjaman
    public function index(Request $request)
    {
        // Menggunakan relasi 'anggota' dan 'buku' sesuai model Anda
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->get();

        if ($request->wantsJson()) {
            return response()->json($peminjaman);
        }

        return view('petugas.peminjaman', compact('peminjaman'));
    }

    // Menampilkan form tambah peminjaman (untuk Admin/Petugas)
    public function create()
    {
        // Ambil daftar buku dan daftar pengguna (anggota/siswa) untuk dipilih di form
        $buku = \App\Models\Buku::all();
        $pengguna = \App\Models\User::all(); // Atau sesuaikan dengan model anggota Anda

        // Cek apakah diakses oleh petugas atau admin, arahkan ke view yang sesuai
        $user = Auth::user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('Petugas')) {
            return view('petugas.peminjaman-create', compact('buku', 'pengguna'));
        }

        return view('admin.peminjaman-create', compact('buku', 'pengguna'));
    }

    // Menyimpan data peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:users,id',
            'buku_id' => 'required',
            'tanggal_pinjam' => 'required|date',
        ]);

        \App\Models\Peminjaman::create([
            'anggota_id' => $request->anggota_id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => null, // Status aktif dipinjam
        ]);

        // Arahkan kembali sesuai role yang sedang login
        $user = Auth::user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('Petugas')) {
            return redirect()->route('petugas.peminjaman.index')->with('success', 'Data peminjaman baru berhasil ditambahkan!');
        }

        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman baru berhasil ditambahkan!');
    }

    // Memproses status pengembalian buku (jika ada aksi konfirmasi)
    public function updateStatus(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Contoh mengubah status menjadi 'Di kembalikan' atau 'Selesai'
        $peminjaman->update([
            'status' => $request->status ?? 'Dikembalikan',
            'tanggal_kembali' => now()
        ]);

        return response()->json(['message' => 'Status peminjaman berhasil diperbarui!']);
    }

    // Menghapus riwayat peminjaman jika diperlukan
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();

        return response()->json(['message' => 'Data peminjaman berhasil dihapus!']);
    }

    
}