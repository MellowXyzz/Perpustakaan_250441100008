<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PenggunaController extends Controller
{
    // Menampilkan daftar pengguna
    public function index(Request $request)
    {
        $users = User::with('roles')->get();

        if ($request->wantsJson()) {
            return response()->json($users);
        }

        return view('admin.pengguna.index', compact('users'));
    }

    // Menyimpan pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return response()->json(['message' => 'Pengguna berhasil ditambahkan!', 'data' => $user]);
    }

    // Menampilkan detail pengguna untuk diedit
    public function show($id, Request $request)
    {
        $user = User::with('roles')->findOrFail($id);
        
        if ($request->wantsJson()) {
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->pluck('name')->first()
            ]);
        }

        return response()->json($user);
    }

    // Mengupdate data pengguna
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return response()->json(['message' => 'Pengguna berhasil diperbarui!']);
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Mencegah admin menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'Anda tidak dapat menghapus akun sendiri!'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Pengguna berhasil dihapus!']);
    }
}