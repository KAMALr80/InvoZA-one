<?php

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
        Schema::table('leaves', function (Blueprint $table) {
            // ========== 1. ADD MISSING COLUMNS ==========

            // Leave number (if not exists)
            if (!Schema::hasColumn('leaves', 'leave_number')) {
                $table->string('leave_number')->unique()->nullable()->after('id');
            }

            // Leave type (if not exists)
            if (!Schema::hasColumn('leaves', 'leave_type')) {
                $table->enum('leave_type', [
                    'annual', 'sick', 'casual', 'unpaid', 'maternity',
                    'paternity', 'bereavement', 'study', 'half_day', 'short_leave'
                ])->default('annual')->after('employee_id');
            }

            // Duration type
            if (!Schema::hasColumn('leaves', 'duration_type')) {
                $table->enum('duration_type', ['full_day', 'half_day', 'short_leave'])
                      ->default('full_day')
                      ->after('leave_type');
            }

            // Session for half day
            if (!Schema::hasColumn('leaves', 'session')) {
                $table->enum('session', ['first_half', 'second_half'])
                      ->nullable()
                      ->after('to_date');
            }

            // Time fields for short leave
            if (!Schema::hasColumn('leaves', 'start_time')) {
                $table->time('start_time')->nullable()->after('session');
            }

            if (!Schema::hasColumn('leaves', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }

            // Contact fields
            if (!Schema::hasColumn('leaves', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('reason');
            }

            if (!Schema::hasColumn('leaves', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable()->after('contact_number');
            }

            // Handover fields
            if (!Schema::hasColumn('leaves', 'handover_notes')) {
                $table->text('handover_notes')->nullable()->after('emergency_contact');
            }

            if (!Schema::hasColumn('leaves', 'handover_person')) {
                $table->string('handover_person')->nullable()->after('handover_notes');
            }

            if (!Schema::hasColumn('leaves', 'alternate_arrangements')) {
                $table->text('alternate_arrangements')->nullable()->after('handover_person');
            }

            // Document field
            if (!Schema::hasColumn('leaves', 'document_path')) {
                $table->string('document_path')->nullable()->after('alternate_arrangements');
            }

            // ========== 2. APPROVAL/REJECTION TRACKING FIELDS ==========

            // Approved by
            if (!Schema::hasColumn('leaves', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('status');
                $table->foreign('approved_by')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }

            // Approved at
            if (!Schema::hasColumn('leaves', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }

            // Approval remarks
            if (!Schema::hasColumn('leaves', 'approval_remarks')) {
                $table->text('approval_remarks')->nullable()->after('approved_at');
            }

            // Rejected by
            if (!Schema::hasColumn('leaves', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('approval_remarks');
                $table->foreign('rejected_by')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }

            // Rejected at
            if (!Schema::hasColumn('leaves', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }

            // Rejection reason
            if (!Schema::hasColumn('leaves', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }

            // Cancelled by
            if (!Schema::hasColumn('leaves', 'cancelled_by')) {
                $table->unsignedBigInteger('cancelled_by')->nullable()->after('rejection_reason');
                $table->foreign('cancelled_by')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }

            // Cancelled at
            if (!Schema::hasColumn('leaves', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('cancelled_by');
            }

            // Cancellation reason
            if (!Schema::hasColumn('leaves', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('cancelled_at');
            }

            // ========== 3. LEAVE BALANCE TRACKING ==========

            if (!Schema::hasColumn('leaves', 'leave_balance_before')) {
                $table->decimal('leave_balance_before', 5, 2)->nullable()->after('cancellation_reason');
            }

            if (!Schema::hasColumn('leaves', 'leave_balance_after')) {
                $table->decimal('leave_balance_after', 5, 2)->nullable()->after('leave_balance_before');
            }

            // ========== 4. TIMESTAMP FIELDS ==========

            // Applied on
            if (!Schema::hasColumn('leaves', 'applied_on')) {
                $table->timestamp('applied_on')->useCurrent()->after('leave_balance_after');
            }

            // IP Address and User Agent (for tracking)
            if (!Schema::hasColumn('leaves', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('applied_on');
            }

            if (!Schema::hasColumn('leaves', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }

            // Soft deletes
            if (!Schema::hasColumn('leaves', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ========== 5. MODIFY EXISTING COLUMNS ==========

        // Modify total_days column
        Schema::table('leaves', function (Blueprint $table) {
            if (Schema::hasColumn('leaves', 'total_days')) {
                $table->decimal('total_days', 5, 2)->default(1)->change();
            } else {
                $table->decimal('total_days', 5, 2)->default(1)->after('to_date');
            }
        });

        // ========== 6. CONVERT OLD 'type' COLUMN TO NEW 'leave_type' ==========

        // Check if old 'type' column exists and new 'leave_type' exists
        if (Schema::hasColumn('leaves', 'type') && Schema::hasColumn('leaves', 'leave_type')) {
            // Convert old values to new format
            DB::statement("
                UPDATE leaves
                SET leave_type = CASE
                    WHEN type = 'Paid' THEN 'annual'
                    WHEN type = 'Unpaid' THEN 'unpaid'
                    WHEN type = 'Sick' THEN 'sick'
                    WHEN type = 'Half Day' THEN 'half_day'
                    ELSE 'annual'
                END
                WHERE leave_type IS NULL OR leave_type = 'annual'
            ");
        }

        // ========== 7. ADD INDEXES WITH DUPLICATE CHECK ==========

        Schema::table('leaves', function (Blueprint $table) {
            $indexes = $this->getExistingIndexes('leaves');

            // Check and add employee_id + status index
            if (!in_array('leaves_employee_id_status_index', $indexes)) {
                $table->index(['employee_id', 'status']);
            }

            // Check and add from_date + to_date index
            if (!in_array('leaves_from_date_to_date_index', $indexes)) {
                $table->index(['from_date', 'to_date']);
            }

            // Check and add status index
            if (!in_array('leaves_status_index', $indexes)) {
                $table->index('status');
            }

            // Check and add leave_type index
            if (!in_array('leaves_leave_type_index', $indexes)) {
                $table->index('leave_type');
            }

            // Check and add applied_on index
            if (!in_array('leaves_applied_on_index', $indexes)) {
                $table->index('applied_on');
            }

            // Check and add employee_id + from_date index
            if (!in_array('leaves_employee_id_from_date_index', $indexes)) {
                $table->index(['employee_id', 'from_date']);
            }
        });
    }

    /**
     * Get existing indexes for a table
     */
    private function getExistingIndexes($tableName)
    {
        $indexes = [];
        $result = DB::select("SHOW INDEX FROM {$tableName}");
        foreach ($result as $row) {
            $indexes[] = $row->Key_name;
        }
        return array_unique($indexes);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Drop indexes (only if they exist)
            $indexes = $this->getExistingIndexes('leaves');

            $indexesToDrop = [
                'leaves_employee_id_status_index',
                'leaves_from_date_to_date_index',
                'leaves_status_index',
                'leaves_leave_type_index',
                'leaves_applied_on_index',
                'leaves_employee_id_from_date_index'
            ];

            foreach ($indexesToDrop as $index) {
                if (in_array($index, $indexes)) {
                    $table->dropIndex($index);
                }
            }

            // Drop foreign keys (if they exist)
            $foreignKeys = ['approved_by', 'rejected_by', 'cancelled_by'];
            foreach ($foreignKeys as $key) {
                if (Schema::hasColumn('leaves', $key)) {
                    try {
                        $table->dropForeign([$key]);
                    } catch (\Exception $e) {
                        // Foreign key might not exist, continue
                    }
                }
            }

            // Drop columns
            $columns = [
                'leave_number', 'leave_type', 'duration_type', 'session',
                'start_time', 'end_time', 'contact_number', 'emergency_contact',
                'handover_notes', 'handover_person', 'alternate_arrangements',
                'document_path', 'approved_by', 'approved_at', 'approval_remarks',
                'rejected_by', 'rejected_at', 'rejection_reason', 'cancelled_by',
                'cancelled_at', 'cancellation_reason', 'leave_balance_before',
                'leave_balance_after', 'applied_on', 'ip_address', 'user_agent',
                'deleted_at'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('leaves', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
