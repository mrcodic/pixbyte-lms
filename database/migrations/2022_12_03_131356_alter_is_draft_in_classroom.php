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
        Schema::table('rooms', function (Blueprint $table) {

            if (Schema::hasColumn('rooms', 'is_draft'))
                $table->boolean('is_draft')->change()->default(0);
            else
                $table->boolean('is_draft')->default(0);




        });
        Schema::table('lessons', function (Blueprint $table) {
            if (Schema::hasColumn('lessons', 'is_draft'))
            $table->boolean('is_draft')->change()->default(0);
            else
                $table->boolean('is_draft')->default(0);

        });
        Schema::table('classrooms', function (Blueprint $table) {
            if (Schema::hasColumn('classrooms', 'is_draft'))
            $table->boolean('is_draft')->change()->default(0);
            else
                $table->boolean('is_draft')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classroom', function (Blueprint $table) {
            //
        });
    }
};
