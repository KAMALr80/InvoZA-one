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
        Schema::table('emi_plans', function (Blueprint $table) {
            // Check if columns don't already exist before adding
            if (!Schema::hasColumn('emi_plans', 'principal')) {
                $table->decimal('principal', 10, 2)->nullable()->after('down_payment');
            }

            if (!Schema::hasColumn('emi_plans', 'interest_rate')) {
                $table->decimal('interest_rate', 5, 2)->default(12)->after('principal');
            }

            if (!Schema::hasColumn('emi_plans', 'total_interest')) {
                $table->decimal('total_interest', 10, 2)->nullable()->after('interest_rate');
            }

            if (!Schema::hasColumn('emi_plans', 'total_payable')) {
                $table->decimal('total_payable', 10, 2)->nullable()->after('total_interest');
            }

            if (!Schema::hasColumn('emi_plans', 'next_due_date')) {
                $table->date('next_due_date')->nullable()->after('start_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emi_plans', function (Blueprint $table) {
            $table->dropColumn([
                'principal',
                'interest_rate',
                'total_interest',
                'total_payable',
                'next_due_date'
            ]);
        });
    }
};
