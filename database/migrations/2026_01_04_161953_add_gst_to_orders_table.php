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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('gst_amount', 10, 2)->default(0)->after('subtotal')->comment('Total GST amount');
            $table->json('gst_breakdown')->nullable()->after('gst_amount')->comment('GST breakdown by rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['gst_amount', 'gst_breakdown']);
        });
    }
};
