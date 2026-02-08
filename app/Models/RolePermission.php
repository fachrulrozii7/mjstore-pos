<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = ['role', 'menu_key', 'is_active'];
    protected $table = 'user_settings';
    // Fungsi pembantu untuk cek akses

    // Cast kolom menu_keys otomatis menjadi array
    protected $casts = [
        'menu_key' => 'array',
    ];

    public static function canAccess($menuKey)
    {
        $user = auth()->user();
        if (!$user) return false;
        
        // 1. Root selalu bisa
        if ($user->role === 'root') return true;

        // 2. Ambil permission berdasarkan role user
        // Gunakan cache atau static variable jika ingin lebih cepat
        $permission = self::where('role', $user->role)->first();

        // 3. Validasi isi menu_key
        if ($permission && $permission->menu_key) {
            // Jika karena suatu hal casting gagal, kita pastikan dia array
            $keys = is_array($permission->menu_key) 
                    ? $permission->menu_key 
                    : json_decode($permission->menu_key, true);

            return in_array($menuKey, $keys ?? []);
        }

        return false;
    }
}