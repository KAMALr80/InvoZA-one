<?php
// database/migrations/2026_03_18_182514_add_coordinates_to_sales_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // ✅ Add destination coordinates (LATITUDE & LONGITUDE)
            if (!Schema::hasColumn('sales', 'destination_latitude')) {
                $table->decimal('destination_latitude', 10, 8)
                      ->nullable()
                      ->after('delivery_instructions')
                      ->comment('Latitude coordinate from map selection');
            }

            if (!Schema::hasColumn('sales', 'destination_longitude')) {
                $table->decimal('destination_longitude', 11, 8)
                      ->nullable()
                      ->after('destination_latitude')
                      ->comment('Longitude coordinate from map selection');
            }

            // ✅ Add geocoding status (optional - track if coordinates are valid)
            if (!Schema::hasColumn('sales', 'location_verified')) {
                $table->boolean('location_verified')
                      ->default(false)
                      ->after('destination_longitude')
                      ->comment('Whether location coordinates are verified');
            }

            // ✅ Add indexes for spatial queries (performance boost)
            try {
                $table->index('destination_latitude', 'sales_lat_idx');
                $table->index('destination_longitude', 'sales_lng_idx');

                // Composite index for both coordinates
                $table->index(['destination_latitude', 'destination_longitude'], 'sales_coordinates_idx');
            } catch (\Exception $e) {
                Log::warning('Indexes might already exist: ' . $e->getMessage());
            }
        });

        // ✅ Log migration success
        Log::info('Coordinates migration completed successfully');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Drop indexes first (safely)
            try {
                $table->dropIndex('sales_lat_idx');
            } catch (\Exception $e) {
                // Index doesn't exist
            }

            try {
                $table->dropIndex('sales_lng_idx');
            } catch (\Exception $e) {
                // Index doesn't exist
            }

            try {
                $table->dropIndex('sales_coordinates_idx');
            } catch (\Exception $e) {
                // Index doesn't exist
            }

            // Drop columns (only the ones we added in this migration)
            $columnsToDrop = [];

            if (Schema::hasColumn('sales', 'destination_latitude')) {
                $columnsToDrop[] = 'destination_latitude';
            }

            if (Schema::hasColumn('sales', 'destination_longitude')) {
                $columnsToDrop[] = 'destination_longitude';
            }

            if (Schema::hasColumn('sales', 'location_verified')) {
                $columnsToDrop[] = 'location_verified';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        Log::info('Coordinates migration rolled back successfully');
    }
};
