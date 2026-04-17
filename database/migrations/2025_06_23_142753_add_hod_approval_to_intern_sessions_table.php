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
            $table->unsignedBigInteger('hod_id')->nullable()->after('sup_id');
            $table->boolean('hod_approved')->default(0)->after('is_approved');

            $table->foreign('hod_id')->references('id')->on('hod')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('intern_sessions', function (Blueprint $table) {
            $table->dropForeign(['hod_id']);
            $table->dropColumn(['hod_id', 'hod_approved']);
        });
    }

};
