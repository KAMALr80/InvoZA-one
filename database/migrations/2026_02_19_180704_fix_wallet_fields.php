<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ❌ wrong column remove (safe way)
        try {
            DB::statement("ALTER TABLE payments DROP FOREIGN KEY payments_source_advance_id_foreign");
        } catch (\Exception $e) {}

        try {
            DB::statement("ALTER TABLE payments DROP INDEX payments_source_advance_id_index");
        } catch (\Exception $e) {}

        try {
            DB::statement("ALTER TABLE payments DROP COLUMN source_advance_id");
        } catch (\Exception $e) {}

        // ✅ correct column add
        try {
            DB::statement("
                ALTER TABLE payments
                ADD COLUMN source_wallet_id BIGINT UNSIGNED NULL AFTER wallet_id,
                ADD CONSTRAINT payments_source_wallet_id_foreign
                FOREIGN KEY (source_wallet_id)
                REFERENCES customer_wallets(id)
                ON DELETE SET NULL
            ");
        } catch (\Exception $e) {}

        // EMI fields remove
        try {
            DB::statement("
                ALTER TABLE payments
                DROP COLUMN emi_months,
                DROP COLUMN down_payment,
                DROP COLUMN emi_amount
            ");
        } catch (\Exception $e) {}
    }

    public function down(): void
    {
        // optional rollback
    }
};
