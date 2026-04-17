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
        Schema::create('intern_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('intern_reg_no'); // from intern_profiles
            $table->unsignedBigInteger('intern_session_id'); // from intern_sessions
            $table->text('message');
            $table->enum('status', ['pending', 'resolved'])->default('pending');
            $table->timestamps();

            // Optional foreign keys
            // $table->foreign('intern_session_id')->references('id')->on('intern_sessions')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intern_complaints');
    }
};
