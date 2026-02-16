<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'mj_master_categories'; // Pastikan sesuai prefix mj_

    protected $fillable = ['name', 'is_active'];

    // Relasi: Satu kategori bisa dimiliki oleh banyak produk
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}