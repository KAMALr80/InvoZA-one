<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('action');
            $table->json('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('sale_id');
            $table->index('customer_id');
            $table->index('action');
            $table->index('created_at');

            // Foreign keys (optional - comment out if not needed)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('sale_id')->references('id')->on('sales')->onDelete('set null');
            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_logs');
    }
};
