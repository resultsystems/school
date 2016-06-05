<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-01 15:51:55
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:29
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 45);
            $table->enum('sex', ['male', 'female']);

            $table->string('cpf', 11);
            $table->string('rg', 10)->nullable();

            $table->string('postcode', 8)->nullable();
            $table->string('street', 60);
            $table->string('complement', 30)->nullable();
            $table->string('number', 10)->nullable();
            $table->string('district', 60)->nullable();
            $table->string('city', 60);
            $table->string('state', 2);
            $table->string('phone', 10)->nullable();
            $table->string('cellphone', 11)->nullable();

            $table->float('salary');
            $table->enum('type_salary', ['commission', 'class_time']);
            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('teachers');
    }
}
