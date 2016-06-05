<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:03:24
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:58:56
 */

namespace Domain\Schedule\Http;

use App;
use App\Exceptions\RepositoryException;
use DB;
use Domain\Schedule\Http\Requests\StoreRequest;
use Domain\Schedule\Http\Requests\UpdateRequest;
use Domain\Schedule\Schedule;
use Domain\Schedule\ScheduleRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class ScheduleControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_index_returns_schedules()
    {
        factory(Schedule::class)->create();

        $controller = App::make(ScheduleController::class);
        $schedules = $controller->index();

        $this->assertInstanceOf(LengthAwarePaginator::class, $schedules);
        $this->assertInstanceOf(Schedule::class, $schedules->first());
    }

    public function test_index_returns_schedules_by_get()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schedule::where('id', '>=', 1)->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $schedules = factory(Schedule::class, 3)->create();

        $this->get('api/v1/schedule');
        $this->seeStatusCode(200);

        foreach ($schedules as $schedule) {
            $this->seeJson(['name' => $schedule->name]);
        }
    }

    public function test_show()
    {
        $model = m::mock(Schedule::class);

        $repo = m::mock(ScheduleRepository::class);
        $repo
            ->shouldReceive('get')
            ->once()
            ->andReturn($model);

        App::instance(ScheduleRepository::class, $repo);

        $controller = App::make(ScheduleController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(Schedule::class, $get);
    }

    public function test_show_real()
    {
        $schedule = factory(Schedule::class)->create();

        $controller = App::make(ScheduleController::class);
        $get = $controller->show($schedule->id);

        $this->assertInstanceOf(Schedule::class, $get);
        $this->assertEquals($get->id, $schedule->id);
    }

    public function test_show_by_get()
    {
        $schedule = factory(Schedule::class)->create();

        $this->get('api/v1/schedule/'.$schedule->id)
            ->seeStatusCode(200);

        $this->seeJson(['name' => $schedule->name]);
    }

    public function test_store()
    {
        $model = m::mock(Schedule::class);
        $request = m::mock(StoreRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(ScheduleRepository::class);

        $repo
            ->shouldReceive('store')
            ->once()
            ->andReturn($model);

        App::instance(ScheduleRepository::class, $repo);
        App::instance(StoreRequest::class, $request);

        $controller = App::make(ScheduleController::class);

        $schedule = $controller->store();
        $this->assertInstanceOf(Schedule::class, $schedule);
    }

    public function test_store_by_post()
    {
        $schedule = [
            'name' => 'Schedule x',
            'start' => '13:00',
            'end' => '17:00',
        ];

        $this->post('api/v1/schedule/', $schedule);

        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Schedule x']);
        $this->assertInstanceOf(Schedule::class, $this->response->original);
        $this->seeInDatabase('schedules', ['id' => $this->response->original->id]);
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

        $controller = App::make(ScheduleController::class);
        $controller->store();
    }

    public function test_update()
    {
        $model = m::mock(Schedule::class);
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(ScheduleRepository::class);
        $repo
            ->shouldReceive('update')
            ->once()
            ->andReturn($model);

        App::instance(UpdateRequest::class, $request);
        App::instance(ScheduleRepository::class, $repo);

        $controller = App::make(ScheduleController::class);
        $update = $controller->update($request, 1);

        $this->assertTrue($update instanceof Schedule);
    }

    public function test_update_by_put()
    {
        $schedule = factory(Schedule::class)->create();

        $data = [
            'name' => 'Schedule x',
            'start' => $schedule->start,
            'end' => $schedule->end,
        ];

        $this->put('api/v1/schedule/'.$schedule->id, $data);

        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Schedule x']);
        $this->assertInstanceOf(Schedule::class, $this->response->original);
        $this->seeInDatabase('schedules', ['id' => $this->response->original->id]);
    }

    public function test_update_failed()
    {
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $this->setExpectedException(RepositoryException::class);

        App::instance(UpdateRequest::class, $request);
        $controller = App::make(ScheduleController::class);

        $controller->update($request, 1);
    }

    public function test_delete()
    {
        $repo = m::mock(ScheduleRepository::class);
        $repo
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        App::instance(ScheduleRepository::class, $repo);

        $controller = App::make(ScheduleController::class);
        $delete = $controller->destroy(1);

        $this->assertTrue($delete);
    }

    public function test_delete_real()
    {
        $schedule = factory(Schedule::class)->create();

        $controller = App::make(ScheduleController::class);
        $delete = $controller->destroy($schedule->id);

        $this->assertEquals(1, $delete);
    }

    public function test_delete_by_delete()
    {
        $schedule = factory(Schedule::class)->create();

        $this->delete('api/v1/schedule/'.$schedule->id);

        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->notSeeInDatabase('schedules', ['id' => $schedule->id]);
    }

    public function test_delete_failed()
    {
        $controller = App::make(ScheduleController::class);
        $delete = $controller->destroy(0);

        $this->assertEquals(0, $delete);
    }
}
