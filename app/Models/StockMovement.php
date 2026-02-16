<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'mj_stock_movements';
    protected $fillable = [
            'branch_id',
            'product_id',
            'type',
            'qty',  
            'created_at'
        ];
    public function branch()
    {
        return $this->belongsTo(MasterBranch::class, 'branch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
