<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterBranch extends Model
{
    protected $table = 'master_branch';

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'branch_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'branch_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'branch_id');
    }
}
