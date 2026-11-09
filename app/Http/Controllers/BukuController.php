<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Buku;

class BukuController extends Controller
{
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

    public function show($id)
    {
        $detailBuku = null;

        $bukus = Buku::with('kategori')->get();

        foreach ($bukus as $data) {
            if ($data->id == $id) {
                $detailBuku = $data;
            }
        }

        return view('buku-detail', ['buku' => $detailBuku]);
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
        $user = Auth::user();
        $userId = Auth::id();

        // Sengaja dinonaktifkan untuk membuktikan celah IDOR (Soal 1.A4)
        /*
        if (($userId != $id) && !($user && method_exists($user, 'hasRole') && $user->hasRole('Admin'))) {
            abort(403, 'Anda tidak diizinkan melihat riwayat peminjaman ini.');
        }
        */

        // Mengambil data peminjaman berdasarkan parameter $id URL tanpa validasi kepemilikan
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
        
        // CELAH A2: Menggabungkan variabel langsung ke string SQL tanpa validasi/binding
        $buku = \Illuminate\Support\Facades\DB::select("SELECT * FROM bukus WHERE judul = '$judul'");
        
        // Mengembalikan hasil sebagai JSON
        return response()->json($buku);
    }

}