<?php
// database/migrations/xxxx_xx_xx_xxxxxx_update_attendances_table_for_advanced_features.php

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
        Schema::table('attendances', function (Blueprint $table) {
            // 1. Add leave_id foreign key (if not exists)
            if (!Schema::hasColumn('attendances', 'leave_id')) {
                $table->foreignId('leave_id')
                    ->nullable()
                    ->after('employee_id')
                    ->constrained('leaves')
                    ->nullOnDelete();
            }

            // 2. Add marked_by (user who marked the attendance)
            if (!Schema::hasColumn('attendances', 'marked_by')) {
                $table->foreignId('marked_by')
                    ->nullable()
                    ->after('remarks')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            // 3. Add is_auto_marked flag
            if (!Schema::hasColumn('attendances', 'is_auto_marked')) {
                $table->boolean('is_auto_marked')
                    ->default(false)
                    ->after('marked_by');
            }

            // 4. Modify the status enum to include all needed values
            //    MySQL ENUM modification requires a raw statement
            DB::statement("ALTER TABLE `attendances` MODIFY `status` ENUM(
                'Present', 'Absent', 'Late', 'Half Day', 'Leave'
            ) NOT NULL DEFAULT 'Present'");
        });

        // 5. Add indexes for better performance
        Schema::table('attendances', function (Blueprint $table) {
            $table->index('attendance_date');
            $table->index('status');
            $table->index('marked_by');
            $table->index('is_auto_marked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['attendance_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['marked_by']);
            $table->dropIndex(['is_auto_marked']);

            // Drop foreign keys
            if (Schema::hasColumn('attendances', 'leave_id')) {
                $table->dropForeign(['leave_id']);
                $table->dropColumn('leave_id');
            }
            if (Schema::hasColumn('attendances', 'marked_by')) {
                $table->dropForeign(['marked_by']);
                $table->dropColumn('marked_by');
            }
            if (Schema::hasColumn('attendances', 'is_auto_marked')) {
                $table->dropColumn('is_auto_marked');
            }

            // Revert status enum to original (only Present, Absent, Leave)
            DB::statement("ALTER TABLE `attendances` MODIFY `status` ENUM('Present', 'Absent', 'Leave') NOT NULL DEFAULT 'Present'");
        });
    }
};
