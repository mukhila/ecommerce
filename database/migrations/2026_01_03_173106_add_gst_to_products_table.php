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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('gst_percentage', 5, 2)->default(18.00)->after('price')->comment('GST percentage: 5, 12, 18, or 28');
            $table->string('fabric_type')->nullable()->after('gst_percentage')->comment('For fabric products: Cotton, Silk, Wool, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['gst_percentage', 'fabric_type']);
        });
    }
};
