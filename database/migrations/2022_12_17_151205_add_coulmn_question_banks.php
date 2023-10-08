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
        Schema::table('question_banks', function (Blueprint $table) {
            $table->integer('grade')->nullable();
            $table->integer('cat_id')->nullable();
            $table->renameColumn('subcatIds', 'sub_cat_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_banks', function (Blueprint $table) {
            //
        });
    }
};
