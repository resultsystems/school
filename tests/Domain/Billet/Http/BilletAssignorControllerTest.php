<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 07:59:59
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:59:06
 */

namespace Domain\Billet\Http;

use App;
use Domain\Billet\BilletAssignor;
use Domain\Billet\BilletAssignorRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery as m;

class BilletAssignorControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_show()
    {
        $model = m::mock(BilletAssignor::class);

        $repo = m::mock(BilletAssignorRepository::class);
        $repo
            ->shouldReceive('first')
            ->once()
            ->andReturn($model);

        App::instance(BilletAssignorRepository::class, $repo);

        $controller = App::make(BilletAssignorController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(BilletAssignor::class, $get);
    }

    public function test_show_real()
    {
        $billetAssignor = BilletAssignor::first();

        if (is_null($billetAssignor)) {
            $billetAssignor = factory(BilletAssignor::class)->create();
        }

        $controller = App::make(BilletAssignorController::class);
        $get = $controller->show($billetAssignor->id);

        $this->assertInstanceOf(BilletAssignor::class, $get);
        $this->assertEquals($get->id, $billetAssignor->id);
    }

    public function test_show_by_get()
    {
        $billetAssignor = BilletAssignor::first();

        if (is_null($billetAssignor)) {
            $billetAssignor = factory(BilletAssignor::class)->create();
        }

        $this->get('api/v1/billet/assignor/first');

        $this->seeStatusCode(200);

        $this->seeJson(['name' => $billetAssignor->name]);
    }

    public function test_store_by_post()
    {
        $faker = factory(BilletAssignor::class)->make();

        $billetAssignor = [
            'name' => 'Cedente',
            'cnpj' => $faker->cnpj,

            'postcode' => $faker->postcode,
            'street' => $faker->street,
            'number' => $faker->number,
            'district' => $faker->district,
            'city' => $faker->city,
            'state' => $faker->state,

            'bank' => $faker->bank,
            'digit_agency' => $faker->digit_agency,
            'agency' => $faker->agency,
            'account' => $faker->account,
            'digit_account' => $faker->digit_account,
            'wallet' => $faker->wallet,
            'agreement' => $faker->agreement,
            'acceptance' => $faker->acceptance,
        ];

        $this->post('api/v1/billet/assignor/', $billetAssignor);
        $original = json_decode($this->response->getContent(), true);
        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Cedente']);
        $this->seeInDatabase('billet_assignors', ['id' => $original['id']]);
    }

    public function test_update_by_post()
    {
        //Sets
        $faker = BilletAssignor::first();

        if (is_null($faker)) {
            $faker = factory(BilletAssignor::class)->create();
        }

        $billetAssignor = [
            'name' => 'Cedente',
            'cnpj' => $faker->cnpj,

            'postcode' => $faker->postcode,
            'street' => $faker->street,
            'number' => $faker->number,
            'district' => $faker->district,
            'city' => $faker->city,
            'state' => $faker->state,

            'bank' => $faker->bank,
            'agency' => $faker->agency,
            'digit_agency' => $faker->digit_agency,
            'account' => $faker->account,
            'digit_account' => $faker->digit_account,
            'wallet' => $faker->wallet,
            'agreement' => $faker->agreement,
            'acceptance' => $faker->acceptance,
        ];

        $this->post('api/v1/billet/assignor/', $billetAssignor);
        $original = json_decode($this->response->getContent(), true);
        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Cedente']);
        $this->assertEquals($original['id'], $faker->id);
        $this->seeInDatabase('billet_assignors', ['id' => $original['id']]);
    }
}
