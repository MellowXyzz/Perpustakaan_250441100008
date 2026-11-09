<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Permissions berdasarkan tabel di modul
        $permissions = [
            'akses dashboard admin',
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
            'lihat riwayat peminjaman sendiri',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 2. Membuat Roles dan menetapkan Permissions 
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo([
            'akses dashboard admin', 'lihat daftar buku', 'kelola data buku', 
            'kelola data pengguna', 'kelola role pengguna', 'kelola peminjaman', 
            'kelola pengembalian', 'lihat laporan perpustakaan'
        ]);

        $petugasRole = Role::create(['name' => 'Petugas']);
        $petugasRole->givePermissionTo([
            'akses dashboard petugas', 'lihat daftar buku', 'kelola data buku', 
            'kelola peminjaman', 'kelola pengembalian'
        ]);

        $siswaRole = Role::create(['name' => 'Siswa']);
        $siswaRole->givePermissionTo([
            'lihat daftar buku', 'lihat profil sendiri', 
            'ubah profil sendiri', 'lihat riwayat peminjaman sendiri'
        ]);

        // 3. Membuat User Dummy dan menetapkan role agar tidak bisa diubah lewat registrasi biasa
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@perpus.com',
            'password' => Hash::make('password')
        ]);
        $admin->assignRole('Admin');

        $petugas = User::create([
            'name' => 'Petugas Jaga',
            'email' => 'petugas@perpus.com',
            'password' => Hash::make('password')
        ]);
        $petugas->assignRole('Petugas');

        $siswa = User::create([
            'name' => 'Siswa Aktif',
            'email' => 'siswa@perpus.com',
            'password' => Hash::make('password')
        ]);
        $siswa->assignRole('Siswa');

        $siswa = User::create([
            'name' => 'Siswa 1',
            'email' => 'siswa1@perpus.com',
            'password' => Hash::make('password')
        ]);
        $siswa->assignRole('Siswa');
    }
}