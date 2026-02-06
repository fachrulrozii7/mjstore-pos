<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    // Add all the fields from your form validation here
    protected $fillable = [
        'product_name',
        'product_id',
        'unit',
        'category',
        'purchase_price',
        'selling_price',
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
}