<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = DB::table('master_branch')->get();
        $products = DB::table('product')->get();

        foreach ($branches as $branch) {
            foreach ($products as $product) {
                DB::table('inventory')->insert([
                    'branch_id' => $branch->id,
                    'product_id' => $product->id,
                    'stock' => rand(10, 100),
                    'min_stock' => 10,
                    'last_stock_update' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
