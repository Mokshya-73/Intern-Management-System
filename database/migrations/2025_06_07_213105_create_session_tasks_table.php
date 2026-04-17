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
        Schema::create('session_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intern_session_id'); // FK to intern_sessions
            $table->string('task_name');
            $table->tinyInteger('rating')->nullable(); // 1–5
            $table->text('description')->nullable(); // Description based on rating
            $table->timestamps();

            $table->foreign('intern_session_id')->references('id')->on('intern_sessions')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_tasks');
    }
};
