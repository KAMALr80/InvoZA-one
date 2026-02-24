<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ========== 1. FIX PAYMENTS TABLE ==========
        Schema::table('payments', function (Blueprint $table) {
            // Check aur add missing columns
            if (!Schema::hasColumn('payments', 'source_wallet_id')) {
                $table->foreignId('source_wallet_id')
                      ->nullable()
                      ->after('wallet_id')
                      ->constrained('customer_wallets')
                      ->nullOnDelete();
            }

            if (!Schema::hasColumn('payments', 'remarks')) {
                $table->string('remarks', 100)->nullable()->after('transaction_id');
            }
        });

        // ========== 2. ADD MISSING INDEXES ==========
        Schema::table('payments', function (Blueprint $table) {
            // Pehle check karo indexes exist karte hain ya nahi
            $indexes = $this->getExistingIndexes('payments');

            if (!in_array('payments_source_wallet_id_index', $indexes)) {
                $table->index('source_wallet_id');
            }

            if (!in_array('payments_customer_id_remarks_index', $indexes)) {
                $table->index(['customer_id', 'remarks']);
            }

            if (!in_array('payments_created_at_status_index', $indexes)) {
                $table->index(['created_at', 'status']);
            }
        });

        Schema::table('customer_wallets', function (Blueprint $table) {
            $indexes = $this->getExistingIndexes('customer_wallets');

            if (!in_array('customer_wallets_type_index', $indexes)) {
                $table->index('type');
            }

            if (!in_array('customer_wallets_created_at_index', $indexes)) {
                $table->index('created_at');
            }
        });

        // ========== 3. FIX ENUM VALUES ==========
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'upi', 'card', 'net_banking', 'emi', 'wallet') DEFAULT 'cash'");
    }

    private function getExistingIndexes($tableName)
    {
        try {
            $results = DB::select("SHOW INDEX FROM {$tableName}");
            return array_unique(array_column($results, 'Key_name'));
        } catch (\Exception $e) {
            return [];
        }
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['source_wallet_id']);
            $table->dropIndex(['customer_id', 'remarks']);
            $table->dropIndex(['created_at', 'status']);
        });

        Schema::table('customer_wallets', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['created_at']);
        });
    }
};
