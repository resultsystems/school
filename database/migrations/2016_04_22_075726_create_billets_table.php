<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-04-25 06:13:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:36
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBilletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('student_id'); //codigoCliente // se possuir

            $table->date('due_date'); //dataVencimento //
            $table->date('discharge_date')->nullable(); //data de quitanção
            $table->date('new_due_date');
            $table->float('amount'); //valor //
            $table->float('discount')->default(0); //valor //
            $table->unsignedTinyInteger('payment_of_fine')->default(0); //multa //  porcento
            $table->unsignedTinyInteger('interest')->default(0); //juros // porcento ao mes
            $table->unsignedTinyInteger('is_interest')->default(1); //juros_apos //
            $table->unsignedTinyInteger('days_protest')->default(0); //diasProtesto // protestar após, se for necessário
            $table->unsignedBigInteger('number'); //numero //
            $table->unsignedBigInteger('document_number'); //numeroDocumento //

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
            $table->unsignedInteger('client_id')->nullable(); //Código cliente
            $table->string('statement01', 30)->nullable(); //descricaoDemonstrativo //  máximo de 5
            $table->string('statement02', 30)->nullable(); //descricaoDemonstrativo //  máximo de 5
            $table->string('statement03', 30)->nullable(); //descricaoDemonstrativo //  máximo de 5
            $table->string('statement04', 30)->nullable(); //descricaoDemonstrativo //  máximo de 5
            $table->string('statement05', 30)->nullable(); //descricaoDemonstrativo //  máximo de 5
            $table->string('instruction01', 30)->nullable(); //instrucoes // máximo de 5
            $table->string('instruction02', 30)->nullable(); //instrucoes // máximo de 5
            $table->string('instruction03', 30)->nullable(); //instrucoes // máximo de 5
            $table->string('instruction04', 30)->nullable(); //instrucoes // máximo de 5
            $table->string('instruction05', 30)->nullable(); //instrucoes // máximo de 5
            $table->unsignedTinyInteger('acceptance')->default(0); //aceite //
            $table->string('kind_document', 3)->nullable(); //especieDoc //

            $table->text('payer'); // 'pagador' => $pagador, // Objeto PessoaContract
            $table->text('recipient'); // 'beneficiario' => $beneficiario, // Objeto PessoaContract

            $table->unsignedMediumInteger('refer'); //year_month
            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('billets');
    }
}
