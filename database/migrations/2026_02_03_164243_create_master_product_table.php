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
        // 1️⃣ Create Categories FIRST
        Schema::create('mj_master_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2️⃣ Create Brands
        Schema::create('mj_master_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3️⃣ Create Product LAST
        Schema::create('mj_master_product', function (Blueprint $table) {
            $table->id();
            $table->string('product_id', 50)->unique();
            $table->string('product_name', 150);

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('mj_master_categories')
                ->nullOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('mj_master_brands')
                ->nullOnDelete();

            $table->string('color', 20)->nullable();
            $table->string('size', 10)->nullable();
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->integer('unit')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mj_master_product');
        Schema::dropIfExists('mj_master_brands');
        Schema::dropIfExists('mj_master_categories');
    }

};
