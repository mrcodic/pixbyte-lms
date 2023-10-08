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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('status')->default(0)->nullable()->comment('0=>absent , 1 => present ,2->absent with excuse , 3=> left/leave early with justification,4=> present but absent online');
            $table->longText('comment')->nullable();
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
        Schema::dropIfExists('attendances');
    }
};
