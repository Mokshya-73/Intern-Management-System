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
        Schema::table('user_core_data', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique();
            $table->string('google_email')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_core_data', function (Blueprint $table) {
            $table->dropUnique(['google_id']);
            $table->dropUnique(['google_email']);
            $table->dropColumn(['google_id', 'google_email']);
        });
    }
};
