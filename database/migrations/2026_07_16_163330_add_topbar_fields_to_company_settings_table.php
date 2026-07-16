<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('topbar_message')->nullable()->after('social_links');
            $table->string('topbar_link')->nullable()->after('topbar_message');
            $table->boolean('topbar_enabled')->default(true)->after('topbar_link');
        });
    }

    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['topbar_message', 'topbar_link', 'topbar_enabled']);
        });
    }
};
