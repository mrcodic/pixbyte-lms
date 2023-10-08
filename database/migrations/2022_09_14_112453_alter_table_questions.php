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
        Schema::table('questions', function (Blueprint $table) {
           $table->foreignId('grade_id')->nullable()->after('user_id')->constrained('grades')->onDelete('cascade')->onUpdate('cascade');
           $table->foreignId('category_id')->nullable()->after('grade_id')->constrained('categories')->onDelete('cascade')->onUpdate('cascade');
           $table->tinyInteger('question_status')->nullable()->comment('1=>text,2=>ckeditor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            //
        });
    }
};
