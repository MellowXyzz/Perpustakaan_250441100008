@extends('layouts.app')

@section('title', 'Kelola Peminjaman - Petugas')

@section('content')
<style>
    .status-pill {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: bold;
        line-height: 1.5;
    }

    .status-dikembalikan {
        background: #ecfdf5;
        color: #047857;
    }

    .status-dipinjam {
        background: #fef3c7;
        color: #d97706;
    }
</style>

<div style="padding: 40px 20px; max-width: 1000px; margin: auto;">

    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 2rem; color: #111827; margin-bottom: 8px;">Kelola Data Peminjaman & Pengembalian</h1>
        <p style="color: #6b7280;">Pantau daftar buku yang sedang dipinjam dan perbarui status pengembaliannya.</p>
    </div>
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 1.25rem; color: #374151; margin: 0;">Daftar Transaksi Peminjaman</h2>
        <a href="{{ route(auth()->user()->hasRole('Petugas') ? 'petugas.peminjaman.create' : 'admin.peminjaman.create') }}" style="background: #4f46e5; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">
            + Tambah Peminjaman
        </a>
    </div>
    @if(session('success'))
        <div style="color: #047857; padding: 12px; border: 1px solid #10b981; background-color: #ecfdf5; border-radius: 6px; margin-bottom: 15px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABEL PEMINJAMAN -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="font-size: 1.25rem; color: #374151; margin-bottom: 20px;">Daftar Transaksi Peminjaman</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; color: #374151;">
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">No</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Peminjam</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Judul Buku</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Tanggal Pinjam</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Status</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $index => $p)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb; font-weight: 500;">{{ $p->anggota->nama ?? $p->anggota->name ?? 'Tidak diketahui' }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">{{ $p->buku->judul_buku ?? $p->buku->judul ?? 'Tidak diketahui' }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">{{ $p->tanggal_pinjam ?? $p->created_at }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">
                            @php
                                $isReturned = !empty($p->tanggal_kembali);
                            @endphp
                            <span class="status-pill {{ $isReturned ? 'status-dikembalikan' : 'status-dipinjam' }}">
                                {{ $isReturned ? 'Dikembalikan' : 'Dipinjam' }}
                            </span>
                        </td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">
                            @if(!$p->tanggal_kembali)
                            <form action="{{ route('petugas.peminjaman.update', $p->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; font-weight: bold;">Selesaikan</button>
                            </form>
                            @else
                            <span style="color: #6b7280; font-size: 0.85rem;">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #6b7280;">Belum ada data peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection