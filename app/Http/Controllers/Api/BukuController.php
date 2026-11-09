<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use Illuminate\Http\Request;
use App\Http\Resources\BukuResource;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data buku beserta relasi kategori, diurutkan dari yang terbaru
        $buku = Buku::with('kategori')->latest()->get(); 
        
        // Mengembalikan data menggunakan format dari BukuResource
        return response()->json([
            'data' => BukuResource::collection($buku)
        ], 200);
    }

    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());
        return response()->json($buku, 201);
    }

    public function show($id)
    {
        $buku = Buku::with(['kategori', 'peminjaman'])->find($id);
        
        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }
        
        return response()->json($buku, 200);
    }

    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);
        
        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        $buku->update($request->validated());
        return response()->json($buku, 200);
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);
        
        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        $buku->delete();
        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }
}

