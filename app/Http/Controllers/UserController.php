<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        // Ambil semua setting role agar bisa ditampilkan di tabel
        $permissions = RolePermission::all()->keyBy('role');
        
        // Daftar semua menu yang tersedia di aplikasi kita
        $availableMenus = ['dashboard', 'pos', 'products', 'inventory', 'reports'];

        return view('users.index', compact('users', 'permissions', 'availableMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'is_active' => '1',
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function updatePermissions(Request $request)
    {
        // Update atau Create data di tabel user_settings (RolePermission)
        RolePermission::updateOrCreate(
            ['role' => $request->role],
            ['menu_key' => $request->menu_keys] // Ini akan tersimpan sebagai JSON otomatis
        );

        return back()->with('success', 'Hak akses role ' . $request->role . ' berhasil diperbarui');
    }
}