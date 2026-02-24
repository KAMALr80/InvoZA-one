<?php
// database/migrations/2024_xx_xx_fix_wallet_fields.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // ❌ गलत column हटाएँ
            if (Schema::hasColumn('payments', 'source_advance_id')) {
                $table->dropForeign(['source_advance_id']);
                $table->dropColumn('source_advance_id');
            }

            // ✅ सही column add करें
            if (!Schema::hasColumn('payments', 'source_wallet_id')) {
                $table->foreignId('source_wallet_id')
                      ->nullable()
                      ->after('wallet_id')
                      ->constrained('customer_wallets')
                      ->nullOnDelete();
            }

            // EMI fields को हटाएँ
            if (Schema::hasColumn('payments', 'emi_months')) {
                $table->dropColumn(['emi_months', 'down_payment', 'emi_amount']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['source_wallet_id']);
            $table->dropColumn('source_wallet_id');
        });
    }
};
