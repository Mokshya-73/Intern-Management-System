<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('department_hods', function (Blueprint $table) {
            $table->boolean('is_active')->default(1);
            $table->text('removal_reason')->nullable();
        });

        Schema::table('hod_supervisors', function (Blueprint $table) {
            $table->boolean('is_active')->default(1);
            $table->text('removal_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('department_hods', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'removal_reason']);
        });

        Schema::table('hod_supervisors', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'removal_reason']);
        });
    }
};
