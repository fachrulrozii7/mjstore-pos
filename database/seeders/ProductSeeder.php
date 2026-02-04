<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['product_id'=>'BRG001','product_name'=>'Kaos Polos','category'=>'Pakaian','purchase_price'=>35000,'selling_price'=>50000],
            ['product_id'=>'BRG002','product_name'=>'Celana Jeans','category'=>'Pakaian','purchase_price'=>120000,'selling_price'=>175000],
            ['product_id'=>'BRG003','product_name'=>'Tas Sekolah','category'=>'Tas','purchase_price'=>90000,'selling_price'=>140000],
            ['product_id'=>'BRG004','product_name'=>'Dompet Kulit','category'=>'Aksesoris','purchase_price'=>45000,'selling_price'=>75000],
            ['product_id'=>'BRG005','product_name'=>'Popok Bayi','category'=>'Bayi','purchase_price'=>55000,'selling_price'=>85000],
        ];

        foreach ($products as $p) {
            DB::table('product')->insert([
                ...$p,
                'unit'=>20,
                'is_active'=>1,
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        }

    }
}
