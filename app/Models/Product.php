<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

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
}