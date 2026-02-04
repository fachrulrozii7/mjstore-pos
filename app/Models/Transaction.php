<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    protected $dates = ['transaction_date'];

    public function branch()
    {
        return $this->belongsTo(MasterBranch::class, 'branch_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
