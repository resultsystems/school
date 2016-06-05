<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:37:08
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:20
 */

namespace Domain\Billet;

use Domain\Student\StudentRepository;
use Eduardokum\LaravelBoleto\Boleto\Banco\Bb;
use Eduardokum\LaravelBoleto\Boleto\Banco\Bradesco;
use Eduardokum\LaravelBoleto\Boleto\Banco\Caixa;
use Eduardokum\LaravelBoleto\Boleto\Banco\Hsbc;
use Eduardokum\LaravelBoleto\Boleto\Banco\Itau;
use Eduardokum\LaravelBoleto\Boleto\Banco\Santander;
use Eduardokum\LaravelBoleto\Boleto\Pessoa;

class GenerateBilletService
{
    /**
     * @var StudentRepository
     */
    protected $student;

    /**
     * @param StudentRepository $student
     */
    public function __construct(StudentRepository $student)
    {
        $this->student = $student;
    }

    public function generate(Billet $billet)
    {
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

        return $billetArray;
    }

    public function run($days)
    {
        $day = (int) date('d');
        $month = date('m');
        $year = date('Y');
        $lastDay = date('t');

        $twice = false;
        if ($day + $days > $lastDay) {
            $twice = true;
        }

        $students = $this->student->betweenDays($day, ($day + $days));
        $this->createBillets($students, (int) "${year}${month}");
        if ($twice) {
            $day = date('d', strtotime('+'.$days.' days'));
            $month = date('m', strtotime('+'.$days.' days'));
            $year = date('Y', strtotime('+'.$days.' days'));

            $students = $this->student->betweenDays($day, ($day + $days));

            $this->createBillets($students, (int) "${year}${month}");
        }

        return true;
    }

    /**
     * Create billets.
     *
     * @param  Illuminate\Database\Eloquent\Collection $students
     * @param  string $refer YYYYMM
     *
     * @return void
     */
    public function createBillets($students, $refer)
    {
        $students->each(function ($student) use ($refer) {
            $exists = $student->whereHas('billets', function ($q) use ($refer, $student) {
                $q->where('refer', $refer)
                    ->where('student_id', $student->id)
                    ->select('id');
            })->exists();
            if (!$exists) {
                $this->createBillet($student, $refer);
            }
        });
    }

    public function createBillet($student, $refer)
    {
        $due_date = substr($refer, 0, 4).'-'.substr($refer, -2).'-';

        $recipient = BilletAssignor::first();
        $data = [
            'student_id' => $student->id,
            'due_date' => $due_date.$student->day_of_payment,
            'amount' => $student->monthly_payment,
            //'number'
            //'documentNumber'
            'agency' => $recipient->agency,
            'digit_agency' => $recipient->digit_agency,
            'account' => $recipient->account,
            'digit_account' => $recipient->digit_account,
            'wallet' => $recipient->wallet,
            'agreement' => $recipient->agreement,
            'portfolio_change' => $recipient->portfolio_change,
            'instruction01' => $recipient->instruction01,
            'instruction02' => $recipient->instruction02,
            'instruction03' => $recipient->instruction03,
            'instruction04' => $recipient->instruction04,
            'instruction05' => $recipient->instruction05,
            'refer' => $refer,
            'note' => 'Fatura gerada automaticamente',
        ];
        $billet = new Billet();
        $billet->fill($data);
        $billet->save();

        return $billet;
    }

    /**
     * Generate pdf.
     * @param  Billet $billet
     * @return base64_encode
     */
    public function pdf(Billet $billet)
    {
        $generate = $this->generate($billet);
        try {
            switch ($billet->bank) {
                case 'bb':
                    $pdf = new Bb($generate);
                    break;
                case 'bradesco':
                    $pdf = new Bradesco($generate);
                    break;
                case 'caixa':
                    $pdf = new Caixa($generate);
                    break;
                case 'hsbc':
                    $pdf = new Hsbc($generate);
                    break;
                case 'itau':
                    $pdf = new Itau($generate);
                    break;
                case 'santander':
                    $pdf = new Santander($generate);
                    break;

                default:
                    # code...
                    break;
            }
        } catch (\Exception $e) {
            dd($billet->toArray(), $e->getMessage());
        }

        try {
            return base64_encode($pdf->renderPDF());
        } catch (\Exception $e) {
            dd($billet->toArray(), $e->getMessage(), 'render');
        }
    }
}
