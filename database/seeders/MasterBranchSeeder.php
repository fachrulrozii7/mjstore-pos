<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_branch')->insert([
            [
                'branch_id' => 'CBG-01',
                'branch_name' => 'MJ Depstore Tebo',
                'address' => 'Kabupaten Tebo',
                'phone' => '081234567890',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'branch_id' => 'CBG-02',
                'branch_name' => 'MJ Depstore Pulau Punjung',
                'address' => 'Dharmasraya',
                'phone' => '081234567891',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'branch_id' => 'CBG-03',
                'branch_name' => 'MJ Depstore Bungo',
                'address' => 'Muaro Bungo',
                'phone' => '081234567892',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

    }
}
