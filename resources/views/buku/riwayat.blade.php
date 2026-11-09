@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div style="padding: 40px 20px; max-width: 1000px; margin: auto;">

    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 2rem; color: #111827; margin-bottom: 8px;">Riwayat Peminjaman Buku</h1>
        <p style="color: #6b7280;">Daftar buku yang pernah atau sedang Anda pinjam di perpustakaan.</p>
    </div>

    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; color: #374151;">
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">No</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Judul Buku</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Tanggal Pinjam</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Status Pengembalian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $index => $p)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb; font-weight: 500;">{{ $p->buku->judul_buku ?? $p->buku->judul ?? 'Tidak diketahui' }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">{{ $p->tanggal_pinjam ?? $p->created_at }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">
                            @php
                                $isReturned = !empty($p->tanggal_kembali);
                                $statusClass = $isReturned ? 'status-returned' : 'status-borrowed';
                                $statusText = $isReturned ? 'Dikembalikan' : 'Dipinjam';
                            @endphp
                            <span class="{{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px; color: #6b7280;">Anda belum memiliki riwayat peminjaman buku.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection