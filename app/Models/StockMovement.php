<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'stock_movements';

    public function branch()
    {
        return $this->belongsTo(MasterBranch::class, 'branch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
