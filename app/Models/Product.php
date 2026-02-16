<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Brand;

class Product extends Model
{
    use HasFactory;

    protected $table = 'mj_master_product';
    // Add all the fields from your form validation here
    protected $fillable = [
        'product_name',
        'product_id',
        'unit',
        'category',
        'brand',
        'color',
        'size', 
        'purchase_price',
        'selling_price',
        'is_active',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function scopeSearch(Builder $query, $term)
    {
        return $query->when($term, function ($q) use ($term) {
            $q->where('product_name', 'like', "%{$term}%")
              ->orWhere('product_id', 'like', "%{$term}%");
        });
    }

    /**
     * Scope untuk sorting yang dinamis
     */
    public function scopeSortBy(Builder $query, $field, $order)
    {
        // Daftar kolom yang diizinkan untuk disortir agar aman dari SQL Injection
        $allowedFields = ['product_name', 'selling_price', 'stock', 'category'];
        $field = in_array($field, $allowedFields) ? $field : 'product_name';
        $order = ($order === 'desc') ? 'desc' : 'asc';

        return $query->orderBy($field, $order);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Hubungan ke Merk
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}