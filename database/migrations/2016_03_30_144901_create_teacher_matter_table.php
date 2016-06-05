<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-04-14 07:45:26
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:34
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTeacherMatterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_matter', function (Blueprint $table) {
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('matter_id');

            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('matter_id')->references('id')->on('matters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('teacher_matter');
    }
}
