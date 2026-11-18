<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Buku;

class BukuController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $buku = \App\Models\Buku::all();

        // Jika dipanggil via Fetch API (JavaScript), kembalikan JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $buku,
            ]);
        }

        return view('buku', compact('buku'));
    }

   public function show(Request $request, $id)
{
    $buku = Buku::findOrFail($id);

    if ($request->wantsJson()) {
        return response()->json([
            'data' => $buku
        ]);
    }

    // Sesuaikan dengan nama view detail buku milik Anda
    return view('buku', compact('buku'));
}

    public function rekapBukuPerKategori()
    {
        $rekap = \App\Models\Kategori::withCount('bukus')->get();

        return response()->json($rekap, 200);
    }

    public function rekapPeminjamanPerBuku()
    {
        $rekap = Buku::withCount('peminjaman')->get();

        return response()->json($rekap, 200);
    }

    public function riwayatPeminjaman($id)
{
    $peminjaman = \App\Models\Peminjaman::findOrFail($id);

    // MENGGUNAKAN POLICY UNTUK MENUTUP CELAH IDOR (SOAL 4)
    // Laravel akan otomatis mencocokkan dengan PeminjamanPolicy@view
    $this->authorize('view', $peminjaman);

    // Atau jika ingin langsung mengambil data berdasarkan ID user yang login (alternatif aman):
    $riwayat = \App\Models\Peminjaman::with(['buku', 'anggota'])
        ->where('anggota_id', $id)
        ->get();

    return view('buku.riwayat', compact('riwayat'));
}

    public function store(Request $request)
    {
        // 1. Validasi data yang dikirim dari form JS
        $validatedData = $request->validate([
            'kategori_id'  => 'required|integer',
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
        ]);

        // 2. Simpan data ke database
        $buku = Buku::create([
            'kategori_id'  => $validatedData['kategori_id'],
            'judul'        => $validatedData['judul'],
            'penulis'      => $validatedData['penulis'],
            'tahun_terbit' => $validatedData['tahun_terbit'],
        ]);

        // 3. Kembalikan response JSON (karena Anda menggunakan fetch/JS)
        return response()->json([
            'message' => 'Data buku berhasil ditambahkan.',
            'data'    => $buku
        ], 201);
    }

    public function destroy($id)
    {
        // Cari data buku berdasarkan ID
        $buku = \App\Models\Buku::find($id);

        // Jika buku tidak ditemukan, kembalikan error 404
        if (!$buku) {
            return response()->json([
                'message' => 'Data buku tidak ditemukan.'
            ], 404);
        }

        // Hapus data buku
        $buku->delete();

        // Kembalikan response sukses
        return response()->json([
            'message' => 'Data buku berhasil dihapus.'
        ], 200);
    }
    public function cari(\Illuminate\Http\Request $request)
    {
        // Membaca query string 'judul'
        $judul = $request->query('judul');
        
        // PERBAIKAN A2: Menggunakan binding (?) agar input aman dari SQL Injection
    $buku = \Illuminate\Support\Facades\DB::select('SELECT * FROM bukus WHERE judul = ?', [$judul]);
        
        // Mengembalikan hasil sebagai JSON
        return response()->json($buku);
    }

}