<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('intern_reg_no'); // To store intern's reg_no
            $table->foreign('intern_reg_no')->references('reg_no')->on('intern_profile'); // Foreign key to intern profile
            $table->text('complaint'); // Text of the complaint
            $table->string('supervisor_reg_no'); // Supervisor making the complaint
            $table->foreign('supervisor_reg_no')->references('reg_no')->on('supervisor'); // Foreign key to supervisor profile
            $table->text('reason_for_removal')->nullable(); // Reason when complaint is removed
            $table->enum('status', ['pending', 'resolved'])->default('pending'); // Complaint status (pending or resolved)
            $table->timestamps(); // Created and updated timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}
