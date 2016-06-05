<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:08:24
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:45
 */

namespace Domain\Billet;

use App;
use Eduardokum\LaravelBoleto\Boleto\Pessoa;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenerateBilletServiceTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_generate()
    {
        $billet = factory(Billet::class)->create();

        $billetArray = [
            'logo' => public_path().'/logo.jpg',
            'dataVencimento' => $billet->new_due_date,
            'valor' => $billet->amount,
            'multa' => $billet->payment_of_fine, // porcento
            'juros' => $billet->interest, // porcento ao mes
            'juros_apos' => $billet->is_interest, // juros e multa após
            'diasProtesto' => $billet->days_protest, // protestar após, se for necessário
            'numero' => $billet->number,
            'numeroDocumento' => $billet->document_number,
            'pagador' => new Pessoa(json_decode($billet->payer, true)), // Objeto PessoaContract
            'beneficiario' => new Pessoa(json_decode($billet->recipient, true)), // Objeto PessoaContract
            'agencia' => $billet->agency,
            'agenciaDv' => $billet->digit_agency, // se possuir
            'conta' => $billet->account,
            'contaDv' => $billet->digit_account, // se possuir
            'carteira' => $billet->wallet,
            'convenio' => $billet->agreement, // se possuir
            'variacaoCarteira' => $billet->portfolio_change, // se possuir
            'range' => $billet->range, // se possuir
            'codigoCliente' => $billet->client_id, // se possuir
            'ios' => $billet->ios,
            'descricaoDemonstrativo' => $billet->geStatements(), // máximo de 5
            'instrucoes' => $billet->getInstructions(), // máximo de 5
            'aceite' => $billet->acceptance,
            'especieDoc' => $billet->kind_document,
        ];

        $service = App::make(GenerateBilletService::class);
        $response = $service->generate($billet);
        $this->assertEquals($response, $billetArray);
        $this->assertInstanceOf(Pessoa::class, $response['pagador']);
        $this->assertInstanceOf(Pessoa::class, $response['beneficiario']);
    }

    public function test_run()
    {
        $service = App::make(GenerateBilletService::class);
        $response = $service->run(30);
        $this->assertTrue($response);
    }

    public function test_pdf()
    {
        $billet = factory(Billet::class)->create();
        $service = App::make(GenerateBilletService::class);
        $response = $service->pdf($billet);
    }
}
