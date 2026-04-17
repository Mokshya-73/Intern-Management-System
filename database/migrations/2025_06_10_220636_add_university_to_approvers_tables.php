<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('approver_1s', function (Blueprint $table) {
            $table->string('university')->nullable()->after('description');
        });

        Schema::table('approver_2s', function (Blueprint $table) {
            $table->string('university')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('approver_1s', function (Blueprint $table) {
            $table->dropColumn('university');
        });

        Schema::table('approver_2s', function (Blueprint $table) {
            $table->dropColumn('university');
        });
    }
};
