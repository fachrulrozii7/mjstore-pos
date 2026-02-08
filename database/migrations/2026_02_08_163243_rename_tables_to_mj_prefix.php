<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    private $tables = [
        'inventory',
        'product',
        'stock_movements',
        'transaction',
        'transaction_detail',
        'users',
        'master_branch',
        'user_settings'
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            // Cek dulu apakah tabel lama ada sebelum di-rename
            if (Schema::hasTable($table)) {
                Schema::rename($table, 'mj_' . $table);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            // Kembalikan ke nama asli tanpa prefix
            if (Schema::hasTable('mj_' . $table)) {
                Schema::rename('mj_' . $table, $table);
            }
        }
    }
};
