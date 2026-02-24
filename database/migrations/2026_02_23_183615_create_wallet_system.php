<?php
// database/migrations/2026_02_23_183615_create_wallet_system.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ========== 1. CUSTOMERS TABLE - Add Balance Fields ==========
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'open_balance')) {
                $table->decimal('open_balance', 12, 2)
                      ->default(0)
                      ->after('address')
                      ->comment('Positive = Due, Negative = Advance');
            }

            if (!Schema::hasColumn('customers', 'wallet_balance')) {
                $table->decimal('wallet_balance', 10, 2)
                      ->default(0)
                      ->after('open_balance')
                      ->comment('Current wallet balance (redundant, kept for quick access)');
            }
        });

        // ========== 2. CUSTOMER WALLETS TABLE ==========
        if (!Schema::hasTable('customer_wallets')) {
            Schema::create('customer_wallets', function (Blueprint $table) {
                $table->id();

                // Foreign key to customers
                $table->foreignId('customer_id')
                      ->constrained()
                      ->cascadeOnDelete()
                      ->comment('Customer who owns this wallet transaction');

                // Transaction type
                $table->enum('type', ['credit', 'debit'])
                      ->comment('credit = money added, debit = money used');

                // Amount and running balance
                $table->decimal('amount', 10, 2)
                      ->comment('Transaction amount');
                $table->decimal('balance', 10, 2)
                      ->comment('Running balance after this transaction');

                // Reference and notes
                $table->string('reference')->nullable()
                      ->comment('Reference description (e.g., "Excess from invoice #...")');
                $table->text('notes')->nullable()
                      ->comment('Additional notes');

                // Timestamps
                $table->timestamps();

                // Indexes for better performance
                $table->index('customer_id');
                $table->index('type');
                $table->index('created_at');
            });
        }

        // ========== 3. PAYMENTS TABLE - Add Wallet Fields ==========
        Schema::table('payments', function (Blueprint $table) {
            // Add customer_id if not exists (for advance payments without sale)
            if (!Schema::hasColumn('payments', 'customer_id')) {
                $table->foreignId('customer_id')
                      ->nullable()
                      ->after('id')
                      ->constrained()
                      ->cascadeOnDelete()
                      ->comment('Customer ID (for advance payments without sale)');
            }

            // Add remarks if not exists
            if (!Schema::hasColumn('payments', 'remarks')) {
                $table->string('remarks', 100)
                      ->nullable()
                      ->after('transaction_id')
                      ->comment('Payment type: INVOICE, ADVANCE_USED, EXCESS_TO_ADVANCE, etc.');
            }

            // ✅ Add wallet_id (links to debit/credit wallet entry)
            if (!Schema::hasColumn('payments', 'wallet_id')) {
                $table->foreignId('wallet_id')
                      ->nullable()
                      ->after('customer_id')
                      ->constrained('customer_wallets')
                      ->nullOnDelete()
                      ->comment('Links to wallet transaction (debit for ADVANCE_USED, credit for EXCESS_TO_ADVANCE)');
            }

            // ✅ Add source_wallet_id (CRITICAL FOR FIFO!)
            if (!Schema::hasColumn('payments', 'source_wallet_id')) {
                $table->foreignId('source_wallet_id')
                      ->nullable()
                      ->after('wallet_id')
                      ->constrained('customer_wallets')
                      ->nullOnDelete()
                      ->comment('For ADVANCE_USED: which credit wallet was used (FIFO tracking)');
            }

            // 🔥 FIX: Check if indexes already exist before adding
            $indexes = $this->getExistingIndexes('payments');

            if (!in_array('payments_customer_id_index', $indexes) && Schema::hasColumn('payments', 'customer_id')) {
                $table->index('customer_id');
            }

            if (!in_array('payments_wallet_id_index', $indexes) && Schema::hasColumn('payments', 'wallet_id')) {
                $table->index('wallet_id');
            }

            if (!in_array('payments_source_wallet_id_index', $indexes) && Schema::hasColumn('payments', 'source_wallet_id')) {
                $table->index('source_wallet_id');
            }

            if (!in_array('payments_remarks_index', $indexes) && Schema::hasColumn('payments', 'remarks')) {
                $table->index('remarks');
            }
        });

        // ========== 4. Update method enum to include 'wallet' and 'emi' ==========
        // First check current enum values
        $columnInfo = DB::select("SHOW COLUMNS FROM payments WHERE Field = 'method'");
        if (!empty($columnInfo)) {
            $currentType = $columnInfo[0]->Type;
            // Only update if 'wallet' is not already in the enum
            if (strpos($currentType, 'wallet') === false) {
                DB::statement("
                    ALTER TABLE payments
                    MODIFY COLUMN method
                    ENUM('cash', 'upi', 'card', 'net_banking', 'emi', 'wallet')
                    DEFAULT 'cash'
                    COMMENT 'Payment method'
                ");
            }
        }

        // ========== 5. SALES TABLE - Add payment tracking fields ==========
        Schema::table('sales', function (Blueprint $table) {
            // Add payment_status if not exists
            if (!Schema::hasColumn('sales', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'emi'])
                      ->default('unpaid')
                      ->after('grand_total')
                      ->comment('Current payment status');
            }

            // Add paid_amount if not exists
            if (!Schema::hasColumn('sales', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)
                      ->default(0)
                      ->after('grand_total')
                      ->comment('Total amount paid so far');
            }

            // Add invoice_token if not exists (for duplicate prevention)
            if (!Schema::hasColumn('sales', 'invoice_token')) {
                $table->uuid('invoice_token')
                      ->nullable()
                      ->unique()
                      ->after('invoice_no')
                      ->comment('Unique token to prevent duplicate submissions');
            }
        });

        // ========== 6. EMI PLANS TABLE (if not exists) ==========
        if (!Schema::hasTable('emi_plans')) {
            Schema::create('emi_plans', function (Blueprint $table) {
                $table->id();

                $table->foreignId('sale_id')
                      ->constrained()
                      ->cascadeOnDelete();

                $table->decimal('total_amount', 10, 2);
                $table->decimal('down_payment', 10, 2)->default(0);
                $table->integer('months');
                $table->decimal('emi_amount', 10, 2);

                $table->enum('status', ['running', 'completed', 'defaulted'])
                      ->default('running');

                $table->timestamps();
                $table->softDeletes();

                $table->index('sale_id');
                $table->index('status');
            });
        }
    }

    /**
     * Get existing indexes for a table
     */
    private function getExistingIndexes($tableName)
    {
        $indexes = [];
        try {
            $results = DB::select("SHOW INDEX FROM {$tableName}");
            foreach ($results as $row) {
                $indexes[] = $row->Key_name;
            }
        } catch (\Exception $e) {
            // Table might not exist yet
        }
        return $indexes;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ========== 1. Remove columns from payments table ==========
        Schema::table('payments', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('payments', 'source_wallet_id')) {
                $table->dropForeign(['source_wallet_id']);
            }
            if (Schema::hasColumn('payments', 'wallet_id')) {
                $table->dropForeign(['wallet_id']);
            }
            if (Schema::hasColumn('payments', 'customer_id')) {
                $table->dropForeign(['customer_id']);
            }

            // Drop indexes
            $table->dropIndex(['customer_id']); // This will drop payments_customer_id_index
            $table->dropIndex(['wallet_id']);   // This will drop payments_wallet_id_index
            $table->dropIndex(['source_wallet_id']); // This will drop payments_source_wallet_id_index
            $table->dropIndex(['remarks']);     // This will drop payments_remarks_index

            // Drop columns
            $columns = ['customer_id', 'wallet_id', 'source_wallet_id', 'remarks'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // ========== 2. Revert method enum to original ==========
        DB::statement("
            ALTER TABLE payments
            MODIFY COLUMN method
            ENUM('cash', 'upi', 'card', 'net_banking')
            DEFAULT 'cash'
        ");

        // ========== 3. Remove columns from sales table ==========
        Schema::table('sales', function (Blueprint $table) {
            $columns = ['payment_status', 'paid_amount', 'invoice_token'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('sales', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // ========== 4. Remove columns from customers table ==========
        Schema::table('customers', function (Blueprint $table) {
            $columns = ['open_balance', 'wallet_balance'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('customers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // ========== 5. Drop tables (if created in this migration) ==========
        // Note: Only drop if you're sure they weren't created by other migrations
        // Schema::dropIfExists('emi_plans');
        // Schema::dropIfExists('customer_wallets');
    }
};
