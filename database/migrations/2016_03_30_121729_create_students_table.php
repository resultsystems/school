<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-01 15:06:11
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:30
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 45)->index();
            $table->enum('sex', ['male', 'female']);

            $table->string('father', 45)->nullable();
            $table->string('mother', 45)->nullable();
            $table->string('responsible', 45);
            $table->string('cpf_responsible', 11);
            $table->string('phone_father', 11)->nullable();
            $table->string('phone_mother', 11)->nullable();
            $table->string('phone_responsible', 11);

            $table->string('postcode', 8)->nullable();
            $table->string('street', 60);
            $table->string('complement', 30)->nullable();
            $table->string('number', 10)->nullable();
            $table->string('district', 60)->nullable();
            $table->string('city', 60);
            $table->string('state', 2);
            $table->string('phone', 11)->nullable();
            $table->string('cellphone', 11)->nullable();

            $table->float('monthly_payment')->nullable();
            $table->unsignedTinyInteger('day_of_payment')->nullable();
            $table->unsignedTinyInteger('installments')->default(0);

            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            /*
        Matérias Concluídas: Checkbox com as matérias atribuídas na turma
        Exibir os meses pagos:
        Exibir mês Atual Pago ou Não: (Campo Checkbox para marcar se o aluno já quitou a mensalidade)
         */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('students');
    }
}
