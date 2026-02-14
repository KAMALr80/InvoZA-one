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
        // Update payments table
        Schema::table('payments', function (Blueprint $table) {
            // Check if columns don't already exist
            if (!Schema::hasColumn('payments', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('method');
            }

            if (!Schema::hasColumn('payments', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }

            if (!Schema::hasColumn('payments', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('notes');
            }

            if (!Schema::hasColumn('payments', 'cancelled_by')) {
                $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')
                    ->constrained('users')->onDelete('set null');
            }

            if (!Schema::hasColumn('payments', 'cancellation_reason')) {
                $table->string('cancellation_reason')->nullable()->after('cancelled_by');
            }
        });

        // Update emi_plans table
        Schema::table('emi_plans', function (Blueprint $table) {
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
        // Remove from payments table
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'cancelled_by')) {
                $table->dropForeign(['cancelled_by']);
            }

            $columns = [
                'transaction_id',
                'notes',
                'cancelled_at',
                'cancelled_by',
                'cancellation_reason'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Remove from emi_plans table
        Schema::table('emi_plans', function (Blueprint $table) {
            $columns = [
                'principal',
                'interest_rate',
                'total_interest',
                'total_payable',
                'next_due_date'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('emi_plans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
