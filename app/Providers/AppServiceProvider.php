<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Peminjaman;
use App\Policies\PeminjamanPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('kelola-buku', function (User $user) {
            // Sesuaikan dengan pengecekan role yang Anda miliki (misal Spatie permission/role)
            return $user->hasRole(['Admin', 'Petugas']) || $user->hasRole(['admin', 'petugas']);
        });
        // DAFTARKAN POLICY DI SINI:
         \Illuminate\Support\Facades\Gate::policy(Peminjaman::class, PeminjamanPolicy::class);
    }
}
