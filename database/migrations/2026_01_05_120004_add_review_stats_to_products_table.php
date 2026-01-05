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
            $table->decimal('average_rating', 3, 2)->default(0)->after('gst_percentage')->comment('Average rating from approved reviews');
            $table->integer('review_count')->default(0)->after('average_rating')->comment('Count of approved reviews');

            $table->index('average_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['average_rating']);
            $table->dropColumn(['average_rating', 'review_count']);
        });
    }
};
