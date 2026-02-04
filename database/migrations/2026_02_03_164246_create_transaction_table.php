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
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id', 50)->unique();
            $table->foreignId('branch_id')->constrained('master_branch');
            $table->dateTime('transaction_date');
            $table->decimal('total_amount', 15, 2);
            $table->enum('payment_method', ['cash', 'qris', 'debit', 'credit']);
            $table->decimal('paid_amount', 15, 2);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->enum('status', ['PAID', 'CANCEL'])->default('PAID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
