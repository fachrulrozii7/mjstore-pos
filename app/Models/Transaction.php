<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    protected $table = 'transaction';
    protected $guarded = ['id'];
    protected $dates = ['transaction_date'];

    public function branch()
    {
        return $this->belongsTo(MasterBranch::class, 'branch_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function createTransaction($cart, $paymentAmount, $branchId, $userId)
    {
        return DB::transaction(function () use ($cart, $paymentAmount, $branchId, $userId) {
            $totalAmount = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);

            // 1. Buat Header Transaksi
            $transaction = self::create([
                'transaction_id' => 'TRX-' . now()->format('YmdHis'),
                'transaction_date' => now(),
                'branch_id' => $branchId,
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'paid_amount' => $paymentAmount,
                'change_amount' => $paymentAmount - $totalAmount,
                'status' => 'PAID'
            ]);

            foreach ($cart as $item) {
                // 2. Logika Validasi & Potong Stok di Inventory (Panggil via Model Inventory)
                $inventory = Inventory::where('branch_id', $branchId)
                    ->where('product_id', $item['id'])
                    ->first();

                if (!$inventory || $inventory->stock < $item['qty']) {
                    throw new \Exception("Stok produk {$item['name']} tidak mencukupi!");
                }

                // 3. Simpan Detail
                $transaction->details()->create([
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // 4. Kurangi Stok
                $inventory->decrement('stock', $item['qty']);
            }

            return $transaction;
        });
    }
 
}
