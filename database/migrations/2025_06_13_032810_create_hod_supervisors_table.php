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
        Schema::create('hod_supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hod_id')->constrained('hod')->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained('supervisor')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hod_supervisors');
    }
};
