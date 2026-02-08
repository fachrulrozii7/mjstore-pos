<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kita gunakan dropIfExists agar jika tabel sudah ada, Laravel akan menghapusnya dulu
        // Ini solusi untuk error "Table already exists" yang Anda alami tadi.
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true); // id int(11) NOT NULL AUTO_INCREMENT
            $table->string('username', 50)->default('0');
            $table->string('branch_id', 10)->default('0');
            $table->string('role', 10)->default('NEW');
            $table->string('name', 50)->default('0');
            $table->string('password', 100)->default('0');
            $table->string('email', 50)->default('0');
            $table->enum('is_active', ['0', '1'])->default('0');
            $table->string('group', 50)->default('0');
            $table->timestamps(); // Mengcover created_at dan updated_at

            // Menambahkan index sesuai SQL Anda
            $table->index('role', 'username'); 
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
