@extends('layouts.app')

@section('title', 'Tambah Peminjaman Baru')

@section('content')
<div style="padding: 40px 20px; max-width: 600px; margin: auto;">

    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 2rem; color: #111827; margin-bottom: 8px;">Form Tambah Peminjaman</h1>
        <p style="color: #6b7280;">Catat transaksi peminjaman buku baru untuk anggota perpustakaan.</p>
    </div>

    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <form action="{{ route(auth()->user()->hasRole('Petugas') ? 'petugas.peminjaman.store' : 'admin.peminjaman.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Pilih Anggota / Peminjam:</label>
                <select name="anggota_id" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; background: white;">
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($pengguna as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                @error('anggota_id') <small style="color: red;">{{ $message }}</small> @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Pilih Buku:</label>
                <select name="buku_id" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; background: white;">
                    <option value="">-- Pilih Buku --</option>
                    @foreach($buku as $b)
                        <option value="{{ $b->id_buku ?? $b->id }}">{{ $b->judul_buku ?? $b->judul }} (Penulis: {{ $b->nama_penulis ?? $b->penulis }})</option>
                    @endforeach
                </select>
                @error('buku_id') <small style="color: red;">{{ $message }}</small> @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Tanggal Pinjam:</label>
                <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                @error('tanggal_pinjam') <small style="color: red;">{{ $message }}</small> @enderror
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Simpan Peminjaman</button>
                <a href="{{ route(auth()->user()->hasRole('Petugas') ? 'petugas.peminjaman.index' : 'admin.peminjaman.index') }}" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; display: inline-block;">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection