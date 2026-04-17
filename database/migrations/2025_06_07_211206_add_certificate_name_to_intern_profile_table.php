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
        Schema::table('intern_profile', function (Blueprint $table) {
            $table->string('certificate_name')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('intern_profile', function (Blueprint $table) {
            $table->dropColumn('certificate_name');
        });
    }

};
