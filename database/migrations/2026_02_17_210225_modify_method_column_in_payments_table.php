<?php
// database/migrations/2026_02_17_xxxxxx_add_wallet_to_payments_method_enum.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL ENUM modification requires raw SQL
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'upi', 'card', 'net_banking', 'emi', 'wallet') DEFAULT 'cash'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'upi', 'card', 'net_banking', 'emi') DEFAULT 'cash'");
    }
};
