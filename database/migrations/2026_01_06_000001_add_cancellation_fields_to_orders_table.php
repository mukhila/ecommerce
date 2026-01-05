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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('payment_expires_at')->nullable()->after('payment_method');
            $table->text('cancellation_reason')->nullable()->after('notes');

            // Add composite index for efficient querying of expirable orders
            $table->index(['payment_status', 'status', 'payment_expires_at'], 'idx_expirable_orders');
        });

        // Backfill existing orders with payment expiration date (7 days from creation)
        DB::table('orders')
            ->whereNull('payment_expires_at')
            ->update([
                'payment_expires_at' => DB::raw('DATE_ADD(created_at, INTERVAL 7 DAY)')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_expirable_orders');
            $table->dropColumn(['payment_expires_at', 'cancellation_reason']);
        });
    }
};
