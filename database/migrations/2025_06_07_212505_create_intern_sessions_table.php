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
        Schema::create('intern_sessions', function (Blueprint $table) {
            $table->id();

            $table->string('reg_no'); // FK to intern_profile
            $table->unsignedBigInteger('session_id'); // FK to i_sessions
            $table->unsignedBigInteger('sup_id'); // FK to supervisor
            $table->unsignedBigInteger('uni_id'); // FK to universities
            $table->unsignedBigInteger('department_id'); // FK to departments

            $table->string('location'); // Physical location of session
            $table->text('supervisor_feedback')->nullable();
            $table->boolean('is_approved')->default(false); // 0 = not approved

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intern_sessions');
    }
};
