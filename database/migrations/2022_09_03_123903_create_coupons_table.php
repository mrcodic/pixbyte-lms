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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('price')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1=>expired,0=>available');
            $table->tinyInteger('type')->comment('1=>room,2=>multiRoom,3=>class,4=>grade');
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('room_id')->nullable();
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('grade_id')->nullable()->constrained('grades')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('coupons');
    }
};
