<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $branches = DB::table('master_branch')->get();
        $products = DB::table('product')->get();

        foreach (range(1, 50) as $i) {

            $branch = $branches->random();
            $trxCode = 'TRX-'.Str::upper(Str::random(8));
            $total = 0;

            DB::table('transaction')->insert([
                'transaction_id'=>$trxCode,
                'branch_id'=>$branch->id,
                'transaction_date'=>now()->subDays(rand(0,30)),
                'total_amount'=>0,
                'payment_method'=>'cash',
                'paid_amount'=>0,
                'change_amount'=>0,
                'status'=>'PAID',
                'created_at'=>now(),
                'updated_at'=>now()
            ]);

            $trxId = DB::getPdo()->lastInsertId();

            foreach ($products->random(rand(1,3)) as $product) {
                $qty = rand(1,5);
                $subtotal = $qty * $product->selling_price;
                $total += $subtotal;

                DB::table('transaction_detail')->insert([
                    'transaction_id'=>$trxId,
                    'product_id'=>$product->id,
                    'qty'=>$qty,
                    'price'=>$product->selling_price,
                    'subtotal'=>$subtotal,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ]);

                DB::table('stock_movements')->insert([
                    'branch_id'=>$branch->id,
                    'product_id'=>$product->id,
                    'type'=>'OUT',
                    'qty'=>$qty,
                    'reference'=>$trxCode,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ]);
            }

            DB::table('transaction')->where('id',$trxId)->update([
                'total_amount'=>$total,
                'paid_amount'=>$total,
            ]);
        }

    }
}
