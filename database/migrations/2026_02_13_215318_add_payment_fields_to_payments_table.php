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
        Schema::table('payments', function (Blueprint $table) {
            // Add transaction_id after method column
            $table->string('transaction_id')->nullable()->after('method');

            // Add notes after status column
            $table->text('notes')->nullable()->after('status');

            // Add cancellation fields
            $table->timestamp('cancelled_at')->nullable()->after('notes');

            // Add foreign key for cancelled_by (references users table)
            $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')
                ->constrained('users')->onDelete('set null');

            // Add cancellation reason
            $table->string('cancellation_reason')->nullable()->after('cancelled_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['cancelled_by']);

            // Drop columns
            $table->dropColumn([
                'transaction_id',
                'notes',
                'cancelled_at',
                'cancelled_by',
                'cancellation_reason'
            ]);
        });
    }
};
