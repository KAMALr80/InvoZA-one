<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, let's check what columns exist
        $columns = DB::select('DESCRIBE purchases');
        $existingColumns = array_column($columns, 'Field');

        Schema::table('purchases', function (Blueprint $table) use ($existingColumns) {
            
            // Add invoice_number if missing
            if (!in_array('invoice_number', $existingColumns)) {
                $table->string('invoice_number')->unique()->after('id');
            }

            // Add user_id if missing
            if (!in_array('user_id', $existingColumns)) {
                $table->foreignId('user_id')->nullable()->constrained()->after('product_id');
            }

            // Add discount if missing
            if (!in_array('discount', $existingColumns)) {
                $table->decimal('discount', 5, 2)->default(0)->after('price');
            }

            // Add tax if missing
            if (!in_array('tax', $existingColumns)) {
                $table->decimal('tax', 5, 2)->default(0)->after('discount');
            }

            // Add grand_total if missing
            if (!in_array('grand_total', $existingColumns)) {
                $table->decimal('grand_total', 10, 2)->after('total');
            }

            // Add payment_method if missing
            if (!in_array('payment_method', $existingColumns)) {
                $table->string('payment_method')->nullable()->after('purchase_date');
            }

            // Add payment_status if missing
            if (!in_array('payment_status', $existingColumns)) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }

            // Add supplier_name if missing
            if (!in_array('supplier_name', $existingColumns)) {
                $table->string('supplier_name')->nullable()->after('payment_status');
            }

            // Add supplier_phone if missing
            if (!in_array('supplier_phone', $existingColumns)) {
                $table->string('supplier_phone')->nullable()->after('supplier_name');
            }

            // Add supplier_email if missing
            if (!in_array('supplier_email', $existingColumns)) {
                $table->string('supplier_email')->nullable()->after('supplier_phone');
            }

            // Add notes if missing
            if (!in_array('notes', $existingColumns)) {
                $table->text('notes')->nullable()->after('supplier_email');
            }

            // Add status if missing
            if (!in_array('status', $existingColumns)) {
                $table->string('status')->default('completed')->after('notes');
            }

            // Add deleted_at for soft deletes if missing
            if (!in_array('deleted_at', $existingColumns)) {
                $table->softDeletes();
            }

            // Add timestamps if missing
            if (!in_array('created_at', $existingColumns)) {
                $table->timestamps();
            }
        });

        // Update existing records with default invoice numbers if the column was just added
        if (!in_array('invoice_number', $existingColumns)) {
            $purchases = DB::table('purchases')->whereNull('invoice_number')->get();
            foreach ($purchases as $purchase) {
                $invoiceNumber = 'INV-' . date('Y') . date('m') . '-' . str_pad($purchase->id, 4, '0', STR_PAD_LEFT);
                DB::table('purchases')->where('id', $purchase->id)->update(['invoice_number' => $invoiceNumber]);
            }
        }
    }

    public function down()
    {
        // We don't want to drop columns in down() as it might delete data
        // This migration is additive only
    }
};