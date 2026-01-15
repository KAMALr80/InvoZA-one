<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
                $table->id();

                // Customer (nullable for walk-in)
                $table->foreignId('customer_id')
                      ->nullable()
                      ->constrained('customers')
                      ->nullOnDelete();

                $table->string('invoice_no')->unique();
                $table->date('sale_date');

                $table->decimal('sub_total', 10, 2);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('grand_total', 10, 2);

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
