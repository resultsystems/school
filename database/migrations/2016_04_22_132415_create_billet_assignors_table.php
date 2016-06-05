<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-12 07:48:33
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:36
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBilletAssignorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billet_assignors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->string('cnpj', 14);

            $table->unsignedTinyInteger('payment_of_fine')->default(0); //multa //  porcento
            $table->unsignedTinyInteger('interest')->default(0); //juros // porcento ao mes
            $table->unsignedTinyInteger('is_interest')->default(1); //juros_apos //
            $table->unsignedTinyInteger('days_protest')->default(0); //diasProtesto // protestar após, se for

            $table->string('postcode', 8)->nullable();
            $table->string('street', 60);
            $table->string('complement', 30)->nullable();
            $table->string('number', 10)->nullable();
            $table->string('district', 60)->nullable();
            $table->string('city', 60);
            $table->string('state', 2);

            $table->enum('bank', ['bb', 'bradesco', 'caixa', 'hsbc', 'itau', 'santander']);
            $table->unsignedMediumInteger('agency'); //agencia //
            $table->unsignedTinyInteger('digit_agency')->nullable(); //agenciaDv // se possuir
            $table->unsignedInteger('account'); //conta //
            $table->unsignedTinyInteger('digit_account')->nullable(); //contaDv //
            $table->string('wallet', 3)->nullable(); //carteira //
            $table->unsignedInteger('agreement')->nullable(); //convenio // se possuir
            $table->unsignedMediumInteger('portfolio_change')->nullable(); //variacaoCarteira // se possuir
            $table->unsignedInteger('range')->nullable(); //range // se possuir
            $table->unsignedMediumInteger('ios')->nullable()->default(0); //ios //
            $table->string('statement01', 30)->nullable(); //descricaoDemonstrativo //  máximo de 5
            $table->string('statement02', 30)->nullable(); //descricaoDemonstrativo //  máximo de 5
            $table->string('statement03', 30)->nullable(); //descricaoDemonstrativo //  máximo de 5
            $table->string('statement04', 30)->nullable(); //descricaoDemonstrativo //  máximo de 5
            $table->string('statement05', 30)->nullable(); //descricaoDemonstrativo //  máximo de 5

            $table->string('instruction01', 30)->nullable(); //instrucoes // máximo de 5
            $table->string('instruction02', 30)->nullable(); //instrucoes // máximo de 5
            $table->string('instruction03', 30)->nullable(); //instrucoes // máximo de 5
            $table->string('instruction04', 30)->nullable(); //instrucoes // máximo de 5
            $table->string('instruction05', 30)->nullable(); //

            $table->unsignedTinyInteger('acceptance')->default(0); //aceite //
            $table->string('kind_document', 3)->nullable(); //especieDoc //
            $table->unsignedInteger('client_id')->nullable(); //Código cliente

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
        Schema::drop('billet_assignors');
    }
}
