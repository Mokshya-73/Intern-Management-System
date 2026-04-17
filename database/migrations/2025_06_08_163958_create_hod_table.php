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
        Schema::create('hod', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('reg_no', 20)->unique(); // Just make it unique, not primary
            $table->string('name', 100);
            $table->string('department', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hod');
    }
};
