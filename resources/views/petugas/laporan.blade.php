@extends('layouts.app')

@section('title', 'Laporan Perpustakaan')

@section('content')
<div style="padding: 40px 20px; max-width: 1000px; margin: auto;">

    <div style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 2rem; color: #111827; margin-bottom: 8px;">Laporan & Statistik Perpustakaan</h1>
            <p style="color: #6b7280;">Rekapitulasi data buku, pengguna, dan aktivitas peminjaman secara keseluruhan.</p>
        </div>
    </div>

    <!-- KARTU STATISTIK RINGKASAN -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 35px;">
        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #4f46e5;">
            <p style="color: #6b7280; font-size: 0.9rem; font-weight: 600; margin-bottom: 5px;">Total Buku</p>
            <h3 style="font-size: 1.8rem; color: #111827; margin: 0;">{{ $totalBuku }}</h3>
        </div>
        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #10b981;">
            <p style="color: #6b7280; font-size: 0.9rem; font-weight: 600; margin-bottom: 5px;">Total Pengguna</p>
            <h3 style="font-size: 1.8rem; color: #111827; margin: 0;">{{ $totalPengguna }}</h3>
        </div>
        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #f59e0b;">
            <p style="color: #6b7280; font-size: 0.9rem; font-weight: 600; margin-bottom: 5px;">Sedang Dipinjam</p>
            <h3 style="font-size: 1.8rem; color: #111827; margin: 0;">{{ $peminjamanAktif }}</h3>
        </div>
        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #8b5cf6;">
            <p style="color: #6b7280; font-size: 0.9rem; font-weight: 600; margin-bottom: 5px;">Total Transaksi</p>
            <h3 style="font-size: 1.8rem; color: #111827; margin: 0;">{{ $totalPeminjaman }}</h3>
        </div>
    </div>

    <!-- TABEL DETAIL RIWAYAT PEMINJAMAN -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="font-size: 1.25rem; color: #374151; margin-bottom: 20px;">Detail Riwayat & Status Peminjaman</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; color: #374151;">
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">No</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Peminjam</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Judul Buku</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Tanggal Pinjam</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $index => $p)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb; font-weight: 500;">{{ $p->anggota->nama ?? $p->anggota->name ?? 'Tidak diketahui' }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">{{ $p->buku->judul_buku ?? $p->buku->judul ?? 'Tidak diketahui' }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">{{ $p->tanggal_pinjam ?? $p->created_at }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">
                            @php
                                $statusLabel = $p->status ?? 'Dipinjam';
                                $statusClass = ($statusLabel === 'Dikembalikan') ? 'status-success' : 'status-warning';
                            @endphp
                            <span class="{{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px; color: #6b7280;">Belum ada data transaksi peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection