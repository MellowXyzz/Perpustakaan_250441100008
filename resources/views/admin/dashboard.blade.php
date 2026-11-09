@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<section style="padding: 40px 20px; max-width: 1000px; margin: auto; min-height: 60vh;">
    
    <div style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h1 style="font-size: 2.2rem; color: #111827; margin-bottom: 10px;">
            Selamat Datang, <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>!
        </h1>
        <p style="color: #6b7280; font-size: 1.1rem; margin-bottom: 30px;">
            Ini adalah halaman dashboard khusus yang hanya bisa diakses oleh akun dengan Role Admin.
        </p>
        
        <h2 style="font-size: 1.3rem; margin-bottom: 20px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px; color: #374151;">
            Menu Operasional Admin
        </h2>
        
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <!-- Tombol Kelola Data Buku (Admin Khusus) -->
            <a href="{{ route('admin.buku') }}" style="display: inline-flex; align-items: center; padding: 12px 24px; background: #4f46e5; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; transition: 0.3s; box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);">
                Kelola Data Buku
            </a>

            <!-- Tombol Lihat Daftar Buku (Publik) -->
            <a href="{{ route('buku.index') }}" style="display: inline-flex; align-items: center; padding: 12px 24px; background: #10b981; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; transition: 0.3s; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);">
                Lihat Daftar Buku
            </a>
            
            <!-- Tombol Kelola Pengguna (Opsional jika rutenya sudah dibuat) -->
            <a href="{{ route('pengguna.index') }}" style="display: inline-flex; align-items: center; padding: 12px 24px; background: #f59e0b; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; transition: 0.3s; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.2);">
                Kelola Pengguna
            </a>

            <a href="{{ route('admin.peminjaman.index') }}" style="display: inline-flex; align-items: center; padding: 12px 24px; background: #8b5cf6; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 6px rgba(139, 92, 246, 0.2);">
            Kelola Peminjaman
            </a>

            <a href="{{ route('admin.laporan') }}" style="display: inline-flex; align-items: center; padding: 12px 24px; background: #06b6d4; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.2);">
                Lihat Laporan Perpustakaan
            </a>
        </div>

    </div>

</section>
@endsection