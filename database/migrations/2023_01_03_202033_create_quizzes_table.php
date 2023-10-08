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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('type')->nullable();
            $table->foreignId('grade')->constrained('grades')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('question_bank_id');
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
        Schema::dropIfExists('quizzes');
    }
};
