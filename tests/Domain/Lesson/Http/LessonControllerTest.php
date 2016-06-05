<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:02:39
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:58:59
 */

namespace Domain\Lesson\Http;

use App;
use App\Exceptions\RepositoryException;
use Domain\Lesson\Http\Requests\StoreRequest;
use Domain\Lesson\Http\Requests\UpdateRequest;
use Domain\Lesson\Lesson;
use Domain\Lesson\LessonRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class LessonControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_index_returns_lessons()
    {
        factory(Lesson::class)->create();

        $controller = App::make(LessonController::class);
        $lessons = $controller->index();

        $this->assertInstanceOf(LengthAwarePaginator::class, $lessons);
        $this->assertInstanceOf(Lesson::class, $lessons->first());
    }

    public function test_index_returns_lessons_by_get()
    {
        Lesson::where('id', '>=', 1)->delete();
        $lessons = factory(Lesson::class, 3)->create();

        $this->get('api/v1/lesson');
        $this->seeStatusCode(200);

        foreach ($lessons as $lesson) {
            $this->seeJson(['name' => $lesson->name]);
        }
    }

    public function test_show()
    {
        $model = m::mock(Lesson::class);

        $repo = m::mock(LessonRepository::class);
        $repo
            ->shouldReceive('get')
            ->once()
            ->andReturn($model);

        App::instance(LessonRepository::class, $repo);

        $controller = App::make(LessonController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(Lesson::class, $get);
    }

    public function test_show_real()
    {
        $lesson = factory(Lesson::class)->create();

        $controller = App::make(LessonController::class);
        $get = $controller->show($lesson->id);

        $this->assertInstanceOf(Lesson::class, $get);
        $this->assertEquals($get->id, $lesson->id);
    }

    public function test_show_by_get()
    {
        $lesson = factory(Lesson::class)->create();

        $this->get('api/v1/lesson/'.$lesson->id)
            ->seeStatusCode(200);

        $this->seeJson(['name' => $lesson->name]);
    }

    public function test_store()
    {
        $model = m::mock(Lesson::class);
        $request = m::mock(StoreRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(LessonRepository::class);

        $repo
            ->shouldReceive('store')
            ->once()
            ->andReturn($model);

        App::instance(LessonRepository::class, $repo);
        App::instance(StoreRequest::class, $request);

        $controller = App::make(LessonController::class);

        $lesson = $controller->store();
        $this->assertInstanceOf(Lesson::class, $lesson);
    }

    public function test_store_by_post()
    {
        $lesson = [
            'name' => 'Lesson x',
        ];

        $this->post('api/v1/lesson/', $lesson);

        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Lesson x']);
        $this->assertInstanceOf(Lesson::class, $this->response->original);
        $this->seeInDatabase('lessons', ['id' => $this->response->original->id]);
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

        $controller = App::make(LessonController::class);
        $controller->store();
    }

    public function test_update()
    {
        $model = m::mock(Lesson::class);
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(LessonRepository::class);
        $repo
            ->shouldReceive('update')
            ->once()
            ->andReturn($model);

        App::instance(UpdateRequest::class, $request);
        App::instance(LessonRepository::class, $repo);

        $controller = App::make(LessonController::class);
        $update = $controller->update($request, 1);

        $this->assertTrue($update instanceof Lesson);
    }

    public function test_update_by_put()
    {
        $lesson = factory(Lesson::class)->create();

        $data = [
            'name' => 'Lesson x',
        ];

        $this->put('api/v1/lesson/'.$lesson->id, $data);

        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Lesson x']);
        $this->assertInstanceOf(Lesson::class, $this->response->original);
        $this->seeInDatabase('lessons', ['id' => $this->response->original->id]);
    }

    public function test_update_failed()
    {
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $this->setExpectedException(RepositoryException::class);

        App::instance(UpdateRequest::class, $request);
        $controller = App::make(LessonController::class);

        $controller->update($request, 1);
    }

    public function test_delete()
    {
        $repo = m::mock(LessonRepository::class);
        $repo
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        App::instance(LessonRepository::class, $repo);

        $controller = App::make(LessonController::class);
        $delete = $controller->destroy(1);

        $this->assertTrue($delete);
    }

    public function test_delete_real()
    {
        $lesson = factory(Lesson::class)->create();

        $controller = App::make(LessonController::class);
        $delete = $controller->destroy($lesson->id);

        $this->assertEquals(1, $delete);
    }

    public function test_delete_by_delete()
    {
        $lesson = factory(Lesson::class)->create();

        $this->delete('api/v1/lesson/'.$lesson->id);

        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->notSeeInDatabase('lessons', ['id' => $lesson->id]);
    }

    public function test_delete_failed()
    {
        $controller = App::make(LessonController::class);
        $delete = $controller->destroy(0);

        $this->assertEquals(0, $delete);
    }
}
