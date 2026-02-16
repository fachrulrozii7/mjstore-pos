<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'mj_master_brands';

    protected $fillable = ['name', 'description', 'is_active'];

    // Relasi: Satu merk bisa dimiliki oleh banyak produk
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}