<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('supervisor', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no')->unique();
            $table->string('name', 100);
            $table->string('university', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('designation', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisor');
    }
};

