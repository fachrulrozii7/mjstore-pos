<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'min:3'],
            'password' => ['required'],
        ]);

        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();
        //     return redirect()->intended('/dashboard');
        // }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 1. Jika Root, langsung ke dashboard (Master Access)
            if ($user->role === 'root') {
                return redirect()->intended('/dashboard');
            }

            // 2. Jika bukan Root, cek menu pertama yang dia miliki di database
            $permission = \App\Models\RolePermission::where('role', $user->role)->first();

            if ($permission && !empty($permission->menu_key)) {
                // Kita ambil menu pertama dari array menu_key sebagai landing page
                // Contoh: jika isinya ["pos", "products"], maka dia akan diarahkan ke /pos
                $landingPage = $permission->menu_key[0]; 
                
                return redirect()->to('/' . $landingPage);
            }

            // 3. Jika tidak punya akses menu sama sekali
            return redirect()->to('/login')->withErrors(['email' => 'Akun Anda belum dikonfigurasi hak aksesnya.']);
        }

        return back()->withErrors([
            'username' => 'Email atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}