<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('intern_profile', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no')->unique();
            $table->string('name', 100);
            $table->string('mobile', 15);
            $table->string('email', 100);
            $table->string('city', 50);
            $table->string('nic', 15);
            $table->date('training_start_date');
            $table->date('training_end_date');
            $table->text('description')->nullable();
            $table->string('password'); // Hashed password
            $table->unsignedTinyInteger('role_id')->default(1); // 1 = Intern
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intern_profile');
    }
};
