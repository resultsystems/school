<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:48:50
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:26
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 30)->nullable();
            $table->string('email');
            $table->string('password');
            $table->enum('owner_type', ['Domain\\\\Student\\\\Student', 'Domain\\\\Teacher\\\\Teacher', 'Domain\\\\Employee\\\\Employee']);
            $table->unsignedInteger('owner_id');
            $table->rememberToken();

            $table->unique(['username', 'owner_type']);
            $table->unique(['email', 'owner_type']);
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
        Schema::drop('users');
    }
}
