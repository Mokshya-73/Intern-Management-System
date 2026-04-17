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
        Schema::table('intern_sessions', function (Blueprint $table) {
            $table->boolean('approver1_approved')->default(false)->after('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intern_sessions', function (Blueprint $table) {
            $table->dropColumn('approver1_approved');
        });
    }
};
