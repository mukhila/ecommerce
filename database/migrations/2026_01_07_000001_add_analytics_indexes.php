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
        // Orders table - composite indexes for analytics queries
        Schema::table('orders', function (Blueprint $table) {
            // For date-based revenue queries
            $table->index(['payment_status', 'created_at'], 'orders_payment_created_idx');

            // For status analytics
            $table->index(['status', 'created_at'], 'orders_status_created_idx');

            // For payment method analysis
            $table->index(['payment_method', 'payment_status'], 'orders_payment_method_idx');

            // For date range queries
            $table->index('created_at', 'orders_created_at_idx');
        });

        // Products table - for sales analytics
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'stock'], 'products_active_stock_idx');
            $table->index('category_id', 'products_category_idx');
        });

        // Order items - for best sellers
        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['product_id', 'created_at'], 'order_items_product_created_idx');
        });

        // Users table - for customer analytics
        Schema::table('users', function (Blueprint $table) {
            $table->index('created_at', 'users_created_at_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_payment_created_idx');
            $table->dropIndex('orders_status_created_idx');
            $table->dropIndex('orders_payment_method_idx');
            $table->dropIndex('orders_created_at_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_stock_idx');
            $table->dropIndex('products_category_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_product_created_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_created_at_idx');
        });
    }
};
