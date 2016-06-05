<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 07:59:44
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:59:04
 */

namespace Domain\Billet\Http;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use DB;
use Domain\Billet\Billet;
use Domain\Billet\BilletAssignor;
use Domain\Billet\BilletRepository;
use Domain\Billet\Http\Requests\StoreRequest;
use Domain\Billet\Http\Requests\UpdateRequest;
use Domain\Student\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class BilletControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_index_returns_billets()
    {
        factory(Billet::class)->create();

        $controller = App::make(BilletController::class);
        $billets = $controller->index();

        $this->assertInstanceOf(LengthAwarePaginator::class, $billets);
        $this->assertInstanceOf(Billet::class, $billets->first());
    }

    public function test_index_returns_billets_by_get()
    {
        Billet::where('id', '>=', 1)->delete();

        $billets = factory(Billet::class, 3)->create();

        $this->get('api/v1/billet');
        $this->seeStatusCode(200);

        foreach ($billets as $billet) {
            $this->seeJson(['amount' => $billet->amount]);
        }
    }

    public function test_show()
    {
        $model = m::mock(Billet::class);

        $repo = m::mock(BilletRepository::class);
        $repo
            ->shouldReceive('get')
            ->once()
            ->andReturn($model);

        App::instance(BilletRepository::class, $repo);

        $controller = App::make(BilletController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(Billet::class, $get);
    }

    public function test_show_real()
    {
        $billet = factory(Billet::class)->create();

        $controller = App::make(BilletController::class);
        $get = $controller->show($billet->id);

        $this->assertInstanceOf(Billet::class, $get);
        $this->assertEquals($get->id, $billet->id);
    }

    public function test_show_by_get()
    {
        $billet = factory(Billet::class)->create();

        $this->get('api/v1/billet/'.$billet->id);
        $this->seeStatusCode(200);

        $this->seeJson(['amount' => $billet->amount]);
    }

    public function test_store()
    {
        $model = m::mock(Billet::class);
        $request = m::mock(StoreRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(BilletRepository::class);

        $repo
            ->shouldReceive('store')
            ->once()
            ->andReturn($model);

        App::instance(BilletRepository::class, $repo);
        App::instance(StoreRequest::class, $request);

        $controller = App::make(BilletController::class);

        $billet = $controller->store();
        $this->assertInstanceOf(Billet::class, $billet);
    }

    public function test_store_by_post()
    {
        $student = factory(Student::class)->create();
        $assignor = BilletAssignor::first();

        if (is_null($assignor)) {
            $assignor = factory(BilletAssignor::class)->create();
        }

        $billet = [
            'amount' => 50,
            'student_id' => $student->id,
            'refer' => 201615,
            'due_date' => date('Y-m-d'),
        ];

        $this->post('api/v1/billet/', $billet);

        $this->seeStatusCode(200);

        $this->seeJsonContains([
            'amount' => 50,
            'payer' => $student->getPayer(),
            'recipient' => $assignor->getRecipient(),
        ]);

        $actual = json_decode($this->response->getContent());
        $payer = json_decode($actual->payer);
        $recipient = json_decode($actual->recipient);

        $this->assertEquals($student->cpf_responsible, $payer->documento);
        $this->assertEquals($assignor->cnpj, $recipient->documento);

        $this->assertInstanceOf(Billet::class, $this->response->original);
        $this->seeInDatabase('billets', ['id' => $this->response->original->id]);
    }

    public function test_store_by_post_filed_dont_exists_assignor()
    {
        $student = factory(Student::class)->create();

        BilletAssignor::where('id', '>=', 1)->delete();

        $billet = [
            'amount' => 50,
            'student_id' => $student->id,
            'refer' => 201615,
        ];

        $return = $this->post('api/v1/billet/', $billet);

        $this->seeStatusCode(403);
        $this->see("There isn't a transferor, must create a transferor to be able to create billet.");
    }

    public function test_store_failed()
    {
        $request = m::mock(StoreRequest::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $error = m::mock(RepositoryException::class);
        $this->setExpectedException(RepositoryException::class);

        App::instance(StoreRequest::class, $request);

        $controller = App::make(BilletController::class);
        $controller->store();
    }

    public function test_update()
    {
        $model = m::mock(Billet::class);
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(BilletRepository::class);
        $repo
            ->shouldReceive('update')
            ->once()
            ->andReturn($model);

        App::instance(UpdateRequest::class, $request);
        App::instance(BilletRepository::class, $repo);

        $controller = App::make(BilletController::class);
        $update = $controller->update($request, 1);

        $this->assertTrue($update instanceof Billet);
    }

    public function test_update_by_put()
    {
        $billet = factory(Billet::class)->create();

        $data = [
            'amount' => 100,
            'refer' => $billet->refer,
            'student_id' => $billet->student_id,
        ];

        $return = $this->put('api/v1/billet/'.$billet->id, $data);

        $this->seeStatusCode(200);

        $this->seeJson(['amount' => 100]);
        $this->assertInstanceOf(Billet::class, $return->response->original);
        $this->seeInDatabase('billets', ['id' => $return->response->original->id]);
    }

    public function test_update_failed()
    {
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $this->setExpectedException(RepositoryException::class);

        App::instance(UpdateRequest::class, $request);
        $controller = App::make(BilletController::class);

        $controller->update($request, 1);
    }

    public function test_delete()
    {
        $repo = m::mock(BilletRepository::class);
        $repo
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        App::instance(BilletRepository::class, $repo);

        $controller = App::make(BilletController::class);
        $delete = $controller->destroy(1);

        $this->assertTrue($delete);
    }

    public function test_delete_real()
    {
        $billet = factory(Billet::class)->create();

        $controller = App::make(BilletController::class);
        $delete = $controller->destroy($billet->id);

        $this->assertEquals(1, $delete);
    }

    public function test_delete_by_delete()
    {
        $billet = factory(Billet::class)->create();

        $return = $this->delete('api/v1/billet/'.$billet->id);

        $this->seeStatusCode(200);

        $this->assertEquals(1, $return->response->original);
        $this->seeInDatabase('billets', ['id' => $billet->id]);
        $this->notSeeInDatabase('billets', ['id' => $billet->id, 'deleted_at' => null]);
    }

    public function test_delete_failed()
    {
        $controller = App::make(BilletController::class);

        $delete = $controller->destroy(0);

        $this->assertEquals(0, $delete);
    }

    public function test_force_delete()
    {
        $repo = m::mock(BilletRepository::class);
        $repo
            ->shouldReceive('forceDelete')
            ->once()
            ->andReturn(true);

        App::instance(BilletRepository::class, $repo);

        $controller = App::make(BilletController::class);
        $forceDelete = $controller->forceDelete(1);

        $this->assertEquals(1, $forceDelete);
    }

    public function test_force_delete_by_delete()
    {
        $billet = factory(Billet::class)->create();

        $return = $this->delete('api/v1/trashed/billet/'.$billet->id);

        $this->seeStatusCode(200);

        $this->assertEquals(1, $return->response->original);
        $this->notSeeInDatabase('billets', ['id' => $billet->id]);
    }

    public function test_force_delete_real()
    {
        $billet = factory(Billet::class)->create();

        $controller = App::make(BilletController::class);
        $forceDelete = $controller->forceDelete($billet->id);

        $repo = App::make(BilletRepository::class);

        $this->setExpectedException(RepositoryException::class);

        $get = $repo->withTrashed()->get($billet->id);
    }

    public function test_force_delete_failed()
    {
        $controller = App::make(BilletController::class);
        $forceDelete = $controller->forceDelete(0);

        $this->assertEquals(0, $forceDelete);
    }

    public function test_restore()
    {
        $repo = m::mock(BilletRepository::class);
        $repo
            ->shouldReceive('restore')
            ->once()
            ->andReturn(true);

        App::instance(BilletRepository::class, $repo);

        $controller = App::make(BilletController::class);
        $restore = $controller->restore(1);

        $this->assertTrue($restore);
    }

    public function test_restore_by_put()
    {
        $billet = factory(Billet::class)->create();
        $billet->delete();

        $return = $this->put('api/v1/restore/billet/'.$billet->id);

        $this->seeStatusCode(200);

        $this->assertEquals(1, $return->response->original);
        $this->seeInDatabase('billets', ['id' => $billet->id]);
        $this->notSeeInDatabase('billets', ['id' => $billet->id, 'deleted_at' => $billet->deleted_at]);
    }

    public function test_restore_real()
    {
        $billet = factory(Billet::class)->create();
        $billet->delete();

        $controller = App::make(BilletController::class);
        $restore = $controller->restore($billet->id);

        $this->assertEquals(1, $restore);

        $repo = App::make(BilletRepository::class);

        $get = $repo->get($billet->id);
        $this->assertInstanceOf(Billet::class, $get);
        $this->assertNull($get->deleted_at);
    }

    public function test_restore_failed()
    {
        $controller = App::make(BilletController::class);
        $restore = $controller->restore(1);

        $this->assertEquals(0, $restore);
    }

    public function test_defaulters()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::SELECT('DELETE FROM billets');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        factory(Billet::class, 5)->create([
            'due_date' => Carbon::yesterday(),
        ]);

        $this->get('api/v1/billet/defaulters');
        $this->seeStatusCode(200);

        $defaulters = json_decode($this->response->getContent(), true);

        foreach ($defaulters as $billet) {
            $this->assertTrue($billet['due_date'] < date('Y-m-d'));
            $this->assertTrue(isset($billet['student']));
        }
    }

    public function test_pay()
    {
        $today = Carbon::today()->toDateString();

        $billet = factory(Billet::class)->create([
            'discharge_date' => null,
        ]);

        $this->put('api/v1/billet/'.$billet->id.'/pay', [
            'discharge_date' => $today,
        ]);
        $this->seeStatusCode(200);

        $this->seeInDatabase('billets', [
            'id' => $billet->id,
            'discharge_date' => $today,
        ]);
    }
}
