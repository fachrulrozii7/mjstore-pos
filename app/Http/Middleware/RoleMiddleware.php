<?php

// app/Http/Middleware/RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\RolePermission;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\UserController;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $menuKey): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Gunakan fungsi canAccess yang sudah kita buat di Model
        // Fungsi ini akan mengecek apakah menuKey ada di dalam array JSON database
        if (RolePermission::canAccess($menuKey)) {
            return $next($request);
        }

        // 3. Jika tidak punya akses, lempar error 403
        abort(403, 'Akses ke menu ' . $menuKey . ' dibatasi.');
    }
}