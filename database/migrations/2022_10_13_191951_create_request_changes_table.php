<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('current_class')->constrained('classrooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('another_class')->constrained('classrooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_changes');
    }
};
