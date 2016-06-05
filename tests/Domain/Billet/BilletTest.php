<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:28
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:44
 */

namespace Domain\Billet;

use Carbon\Carbon;
use Domain\Student\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BilletTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_create_billet()
    {
        $billet = factory(Billet::class)->create();

        $this->assertInstanceOf(Billet::class, $billet);

        $this->assertInstanceOf(Student::class, $billet->student);

        $this->assertInstanceOf(Carbon::class, $billet->created_at);
        $this->assertInstanceOf(Carbon::class, $billet->updated_at);
        $this->assertInstanceOf(Carbon::class, $billet->due_date);
        $this->assertInstanceOf(Carbon::class, $billet->new_due_date);

        $recipient = BilletAssignor::first();

        $this->seeInDatabase('billets', [
            'id' => $billet->id,
            'payment_of_fine' => $recipient->payment_of_fine,
            'interest' => $recipient->interest,
            'is_interest' => $recipient->is_interest,
            'days_protest' => $recipient->days_protest,
        ]);
    }

    public function test_dischargeFrom()
    {
        $billet = factory(Billet::class)->create();

        $billet->dischargeFrom();

        $this->assertInstanceOf(Carbon::class, $billet->discharge_date);
        $this->seeInDatabase('billets', [
            'discharge_date' => $billet->discharge_date->toDateString(),
        ]);
    }

    public function test_cant_discharge_from_in_twice()
    {
        $billet = factory(Billet::class)->create();

        $dischargeDate = $billet->dischargeFrom();

        $this->assertInstanceOf(Carbon::class, $billet->discharge_date);

        $this->assertFalse($billet->dischargeFrom($dischargeDate->copy()->addSecond(5)));
    }
}
