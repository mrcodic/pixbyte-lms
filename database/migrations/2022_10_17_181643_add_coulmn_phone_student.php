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
        Schema::table('students', function (Blueprint $table) {
           $table->string('phone')->nullable();
            $table->string('total_rooms')->change()->nullable();
            $table->string('complete_rooms')->change()->nullable();
            $table->string('missed_rooms')->change()->nullable();
            $table->integer('full_mark')->change()->nullable();
            $table->integer('completed_lessons')->change()->nullable();
            $table->bigInteger('exp')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
