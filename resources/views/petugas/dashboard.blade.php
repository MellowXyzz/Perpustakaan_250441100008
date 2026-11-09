@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div style="padding: 40px 20px; max-width: 1000px; margin: auto;">

    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 2rem; color: #111827; margin-bottom: 8px;">Dashboard Petugas</h1>
        <p style="color: #6b7280;">Selamat datang, {{ auth()->user()->name }}. Kelola operasional perpustakaan dengan mudah dari sini.</p>
    </div>

    <!-- KARTU STATISTIK -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 35px;">
        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #4f46e5;">
            <p style="color: #6b7280; font-size: 0.9rem; font-weight: 600; margin-bottom: 5px;">Total Koleksi Buku</p>
            <h3 style="font-size: 1.8rem; color: #111827; margin: 0;">{{ $totalBuku }}</h3>
        </div>
        <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #f59e0b;">
            <p style="color: #6b7280; font-size: 0.9rem; font-weight: 600; margin-bottom: 5px;">Peminjaman Aktif</p>
            <h3 style="font-size: 1.8rem; color: #111827; margin: 0;">{{ $peminjamanAktif }}</h3>
        </div>
    </div>

    <!-- MENU NAVIGASI CEPAT PETUGAS -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="font-size: 1.25rem; color: #374151; margin-bottom: 20px;">Menu Operasional Petugas</h2>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="{{ route('buku.index') }}" style="padding: 12px 20px; background: #4f46e5; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;">Kelola & Lihat Buku</a>
            <a href="{{ route('petugas.peminjaman.index') }}" style="padding: 12px 20px; background: #f59e0b; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;">Kelola Peminjaman & Pengembalian</a>
            <a href="{{ route('petugas.laporan') }}" style="padding: 12px 20px; background: #06b6d4; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;">Lihat Laporan</a>
            <a href="{{ route('profile.edit') }}" style="padding: 12px 20px; background: #6b7280; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;">Ubah Profil Saya</a>
            <a href="{{ route('riwayat.saya', auth()->id()) }}" style="padding: 12px 20px; background: #8b5cf6; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;">Riwayat Saya</a>
        </div>
    </div>

</div>
@endsection