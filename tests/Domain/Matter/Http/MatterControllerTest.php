<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:02:56
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:58:58
 */

namespace Domain\Matter\Http;

use App;
use App\Exceptions\RepositoryException;
use DB;
use Domain\Lesson\Lesson;
use Domain\Matter\Http\Requests\StoreRequest;
use Domain\Matter\Http\Requests\UpdateRequest;
use Domain\Matter\Matter;
use Domain\Matter\MatterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class MatterControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_index_returns_matters()
    {
        factory(Matter::class)->create();

        $controller = App::make(MatterController::class);
        $matters = $controller->index();

        $this->assertInstanceOf(LengthAwarePaginator::class, $matters);
        $this->assertInstanceOf(Matter::class, $matters->first());
    }

    public function test_index_returns_matters_by_get()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Matter::where('id', '>=', 1)->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $matters = factory(Matter::class, 3)->create();

        $this->get('api/v1/matter');
        $this->seeStatusCode(200);

        foreach ($matters as $matter) {
            $this->seeJson(['name' => $matter->name]);
        }
    }

    public function test_show()
    {
        $model = m::mock(Matter::class);

        $repo = m::mock(MatterRepository::class);
        $repo
            ->shouldReceive('get')
            ->once()
            ->andReturn($model);

        App::instance(MatterRepository::class, $repo);

        $controller = App::make(MatterController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(Matter::class, $get);
    }

    public function test_show_real()
    {
        $matter = factory(Matter::class)->create();

        $controller = App::make(MatterController::class);
        $get = $controller->show($matter->id);

        $this->assertInstanceOf(Matter::class, $get);
        $this->assertEquals($get->id, $matter->id);
    }

    public function test_show_by_get()
    {
        $matter = factory(Matter::class)->create();

        $this->get('api/v1/matter/'.$matter->id)
            ->seeStatusCode(200);

        $this->seeJson(['name' => $matter->name]);
    }

    public function test_store()
    {
        $model = m::mock(Matter::class);
        $request = m::mock(StoreRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(MatterRepository::class);

        $repo
            ->shouldReceive('store')
            ->once()
            ->andReturn($model);

        App::instance(MatterRepository::class, $repo);
        App::instance(StoreRequest::class, $request);

        $controller = App::make(MatterController::class);

        $matter = $controller->store();
        $this->assertInstanceOf(Matter::class, $matter);
    }

    public function test_store_by_post()
    {
        $matter = [
            'name' => 'Matter x',
            'workload' => 50,
        ];

        $this->post('api/v1/matter/', $matter);

        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Matter x']);
        $this->assertInstanceOf(Matter::class, $this->response->original);
        $this->seeInDatabase('matters', ['id' => $this->response->original->id]);
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

        $controller = App::make(MatterController::class);
        $controller->store();
    }

    public function test_update()
    {
        $model = m::mock(Matter::class);
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(MatterRepository::class);
        $repo
            ->shouldReceive('update')
            ->once()
            ->andReturn($model);

        App::instance(UpdateRequest::class, $request);
        App::instance(MatterRepository::class, $repo);

        $controller = App::make(MatterController::class);
        $update = $controller->update($request, 1);

        $this->assertTrue($update instanceof Matter);
    }

    public function test_update_by_put()
    {
        $matter = factory(Matter::class)->create();

        $data = [
            'name' => 'Matter x',
            'workload' => $matter->workload,
        ];

        $this->put('api/v1/matter/'.$matter->id, $data);

        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Matter x']);
        $this->assertInstanceOf(Matter::class, $this->response->original);
        $this->seeInDatabase('matters', ['id' => $this->response->original->id]);
    }

    public function test_update_failed()
    {
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $this->setExpectedException(RepositoryException::class);

        App::instance(UpdateRequest::class, $request);
        $controller = App::make(MatterController::class);

        $controller->update($request, 1);
    }

    public function test_delete()
    {
        $repo = m::mock(MatterRepository::class);
        $repo
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        App::instance(MatterRepository::class, $repo);

        $controller = App::make(MatterController::class);
        $delete = $controller->destroy(1);

        $this->assertTrue($delete);
    }

    public function test_delete_real()
    {
        $matter = factory(Matter::class)->create();

        $controller = App::make(MatterController::class);
        $delete = $controller->destroy($matter->id);

        $this->assertEquals(1, $delete);
    }

    public function test_delete_by_delete()
    {
        $matter = factory(Matter::class)->create();

        $this->delete('api/v1/matter/'.$matter->id);

        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->notSeeInDatabase('matters', ['id' => $matter->id]);
    }

    public function test_delete_failed()
    {
        $controller = App::make(MatterController::class);
        $delete = $controller->destroy(0);

        $this->assertEquals(0, $delete);
    }

    /**
     * Test associate lessons.
     */
    public function test_associate_lessons()
    {
        $matter = factory(Matter::class)->create();
        $lessons = factory(Lesson::class, 30)->create();
        $data = [
            'id' => $matter->id,
            'lessons' => [],
        ];
        $random = $lessons->random(3)->each(function ($matter) use (&$data) {
            $data['lessons'][] = ['id' => $matter->id];
        });
        $this->put('api/v1/matter/'.$matter->id.'/lessons', $data);
        $this->seeStatusCode(200);
        $this->seeJson(['status' => 'ok']);
        foreach ($data['lessons'] as $key => $value) {
            $this->seeInDatabase('matter_lesson', [
                'matter_id' => $matter->id,
                'lesson_id' => $data['lessons'][$key]['id'],
            ]);
        }
    }
}
