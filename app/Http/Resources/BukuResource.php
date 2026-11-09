<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_buku' => $this->id,
            'kategori_buku' => $this->kategori->nama ?? 'Tanpa Kategori',
            'kategori_id' => $this->kategori_id,
            'judul_buku' => $this->judul,
            'nama_penulis' => $this->penulis,
            'tahun' => $this->tahun_terbit,
        ];
    }
}