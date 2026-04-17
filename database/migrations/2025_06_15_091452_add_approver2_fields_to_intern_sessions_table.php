<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('intern_sessions', function (Blueprint $table) {
            $table->boolean('approver2_approved')->default(false)->after('approver1_approved');
            $table->timestamp('approver2_approved_at')->nullable()->after('approver2_approved');
        });
    }

    public function down()
    {
        Schema::table('intern_sessions', function (Blueprint $table) {
            $table->dropColumn(['approver2_approved', 'approver2_approved_at']);
        });
    }
};
