<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- TAMBAHKAN LOGIKA PENGECEKAN ROLE DI SINI ---
        $user = $request->user();

        if ($user->hasRole('Admin')) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->hasRole('Petugas')) {
            return redirect()->intended(route('petugas.dashboard'));
        }
        
        // Jika yang login adalah Siswa, arahkan ke daftar buku
        return redirect()->intended(route('buku.index'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
