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
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'view_financial_reports'
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->string('category'); // e.g., 'analytics', 'products', 'orders'
            $table->timestamps();
        });

        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['admin', 'staff']);
            $table->foreignId('permission_id')->constrained('admin_permissions')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['role', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_role_permissions');
        Schema::dropIfExists('admin_permissions');
    }
};
