<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'mj_inventory';
    protected $fillable = [
        'branch_id',
        'product_id',
        'stock',
        'min_stock',
    ];

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
        return $query->join('mj_master_product', 'mj_inventory.product_id', '=', 'mj_master_product.id')
                     ->select('mj_inventory.*', 'mj_master_product.product_name', 'mj_master_product.product_id', 'mj_master_product.unit', 'mj_master_product.category_id');
    }

    public function scopeSearchProduct($query, $term)
    {
        return $query->when($term, function ($q) use ($term) {
            $q->where('mj_master_product.product_name', 'like', "%{$term}%")
              ->orWhere('mj_master_product.product_id', 'like', "%{$term}%");
        });
    }

    public function scopeSortInventory($query, $field, $order)
    {
        $order = ($order === 'desc') ? 'desc' : 'asc';
        if ($field === 'stock') {
            return $query->orderBy('mj_inventory.stock', $order);
        }
        return $query->orderBy('mj_master_product.product_name', $order);
    }
}
