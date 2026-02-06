<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    public function branch()
    {
        return $this->belongsTo(MasterBranch::class, 'branch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeWithProductData($query)
    {
        return $query->join('product', 'inventory.product_id', '=', 'product.id')
                     ->select('inventory.*', 'product.product_name', 'product.product_id', 'product.unit', 'product.category');
    }

    public function scopeSearchProduct($query, $term)
    {
        return $query->when($term, function ($q) use ($term) {
            $q->where('product.product_name', 'like', "%{$term}%")
              ->orWhere('product.product_id', 'like', "%{$term}%");
        });
    }

    public function scopeSortInventory($query, $field, $order)
    {
        $order = ($order === 'desc') ? 'desc' : 'asc';
        if ($field === 'stock') {
            return $query->orderBy('inventory.stock', $order);
        }
        return $query->orderBy('product.product_name', $order);
    }
}
