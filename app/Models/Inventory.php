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
}
