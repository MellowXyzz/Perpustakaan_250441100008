<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSRF Token Wajib Ada: Agar Fetch API saat ubah/hapus buku tidak error 419 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Beranda') - PerpustakaanKu</title>

    <!-- Pastikan JS juga dimuat bersama CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Wadah untuk memuat CSS tambahan dari halaman lain jika ada -->
    @stack('styles')
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">

            <a href="{{ route('home') }}" class="brand">
                <div class="brand-text">
                    <strong>PerpustakaanKu</strong>
                </div>
            </a>

            <div class="nav-menu">

                @auth
                    {{-- Menu Khusus Admin --}}
                    @role('Admin')
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard Admin</a>
                        <a href="{{ route('admin.laporan') }}" class="{{ request()->routeIs('admin.laporan') ? 'active' : '' }}">Laporan</a>
                    @endrole

                    {{-- Menu Khusus Petugas --}}
                    @role('Petugas')
                        <a href="{{ route('petugas.dashboard') }}" class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">Dashboard Petugas</a>
                        <a href="{{ route('petugas.peminjaman.index') }}" class="{{ request()->routeIs('petugas.peminjaman.*') ? 'active' : '' }}">Kelola Transaksi</a>
                        <a href="{{ route('petugas.laporan') }}" class="{{ request()->routeIs('petugas.laporan') ? 'active' : '' }}">Laporan</a>
                    @endrole

                    {{-- Menu Umum untuk Semua Pengguna yang Sudah Login (Admin, Petugas, Siswa) --}}
                    <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">
                        Daftar Buku
                    </a>

                    <a href="{{ route('riwayat.saya', auth()->id()) }}" class="{{ request()->routeIs('riwayat.saya') ? 'active' : '' }}">
                        Riwayat Saya
                    </a>

                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        Profil Saya
                    </a>

                    <!-- Tombol Logout -->
                    <form method="POST" action="{{ route('logout') }}" style="display: inline-block; margin-left: 15px;">
                        @csrf
                        <button type="submit" style="background: none; border: none; font-weight: bold; color: #e74c3c; cursor: pointer;">
                            Logout ({{ auth()->user()->name }})
                        </button>
                    </form>
                @else
                    {{-- Menu Tamu / Belum Login --}}
                    <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">
                        Daftar Buku
                    </a>
                    
                    <a href="{{ route('login') }}" style="margin-left: 15px; font-weight: bold;">
                        Login
                    </a>
                @endauth

            </div>

        </div>
    </nav>


    <main>
        <!-- Mendukung render dari halaman bawaan Breeze (misal: halaman Edit Profil) -->
        {{ $slot ?? '' }}

        <!-- Mendukung render dari template kustom buatan Anda -->
        @yield('content')
    </main>


    <footer>
        <div class="footer-container">
            <div>
                <div class="footer-brand">
                    PerpustakaanKu
                </div>
                <p>
                    Menjelajahi dunia melalui buku.
                </p>
            </div>
            <p class="copyright">
                © 2026 PerpustakaanKu
            </p>
        </div>
    </footer>

    <!-- Wadah untuk meletakkan script JS kustom dari halaman lain -->
    @stack('scripts')

</body>

</html>