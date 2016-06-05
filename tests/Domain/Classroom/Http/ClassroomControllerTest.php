<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:00:53
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:59:03
 */

namespace Domain\Classroom\Http;

use App;
use App\Exceptions\RepositoryException;
use DB;
use Domain\Classroom\Classroom;
use Domain\Classroom\ClassroomRepository;
use Domain\Classroom\Http\Requests\StoreRequest;
use Domain\Classroom\Http\Requests\UpdateRequest;
use Domain\Matter\Matter;
use Domain\Schedule\Schedule;
use Domain\Student\Student;
use Domain\Teacher\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class ClassroomControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_index_returns_classrooms()
    {
        factory(Classroom::class)->create();

        $controller = App::make(ClassroomController::class);
        $classrooms = $controller->index();

        $this->assertInstanceOf(LengthAwarePaginator::class, $classrooms);
        $this->assertInstanceOf(Classroom::class, $classrooms->first());
    }

    public function test_index_returns_classrooms_by_get()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Classroom::where('id', '>=', 1)->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $classrooms = factory(Classroom::class, 3)->create();

        $this->get('api/v1/classroom');
        $this->seeStatusCode(200);

        foreach ($classrooms as $classroom) {
            $this->seeJson(['name' => $classroom->name]);
        }
    }

    public function test_show()
    {
        $model = m::mock(Classroom::class);

        $repo = m::mock(ClassroomRepository::class);
        $repo
            ->shouldReceive('get')
            ->once()
            ->andReturn($model);

        App::instance(ClassroomRepository::class, $repo);

        $controller = App::make(ClassroomController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(Classroom::class, $get);
    }

    public function test_show_real()
    {
        $classroom = factory(Classroom::class)->create();

        $controller = App::make(ClassroomController::class);
        $get = $controller->show($classroom->id);

        $this->assertInstanceOf(Classroom::class, $get);
        $this->assertEquals($get->id, $classroom->id);
    }

    public function test_show_by_get()
    {
        $classroom = factory(Classroom::class)->create();

        $this->get('api/v1/classroom/'.$classroom->id);
        $this->seeStatusCode(200);

        $this->seeJson(['name' => $classroom->name]);
    }

    public function test_store()
    {
        $model = m::mock(Classroom::class);
        $request = m::mock(StoreRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(ClassroomRepository::class);

        $repo
            ->shouldReceive('store')
            ->once()
            ->andReturn($model);

        App::instance(ClassroomRepository::class, $repo);
        App::instance(StoreRequest::class, $request);

        $controller = App::make(ClassroomController::class);

        $classroom = $controller->store();
        $this->assertInstanceOf(Classroom::class, $classroom);
    }

    public function test_store_by_post()
    {
        $classroom = [
            'name' => 'Classroom x',
            'teacher_id' => factory(Teacher::class)->create()->id,
            'schedule_id' => factory(Schedule::class)->create()->id,
        ];

        $this->post('api/v1/classroom/', $classroom);

        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Classroom x']);
        $this->assertInstanceOf(Classroom::class, $this->response->original);
        $this->seeInDatabase('classrooms', ['id' => $this->response->original->id]);
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

        $controller = App::make(ClassroomController::class);
        $controller->store();
    }

    public function test_update()
    {
        $model = m::mock(Classroom::class);
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(ClassroomRepository::class);
        $repo
            ->shouldReceive('update')
            ->once()
            ->andReturn($model);

        App::instance(UpdateRequest::class, $request);
        App::instance(ClassroomRepository::class, $repo);

        $controller = App::make(ClassroomController::class);
        $update = $controller->update($request, 1);

        $this->assertTrue($update instanceof Classroom);
    }

    public function test_update_by_put()
    {
        $classroom = factory(Classroom::class)->create();

        $data = [
            'name' => 'Classroom x',
            'schedule_id' => $classroom->schedule_id,
            'teacher_id' => $classroom->teacher_id,
        ];

        $this->put('api/v1/classroom/'.$classroom->id, $data);

        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Classroom x']);
        $this->assertInstanceOf(Classroom::class, $this->response->original);
        $this->seeInDatabase('classrooms', ['id' => $this->response->original->id]);
    }

    public function test_update_failed()
    {
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $this->setExpectedException(RepositoryException::class);

        App::instance(UpdateRequest::class, $request);
        $controller = App::make(ClassroomController::class);

        $controller->update($request, 1);
    }

    public function test_delete()
    {
        $repo = m::mock(ClassroomRepository::class);
        $repo
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        App::instance(ClassroomRepository::class, $repo);

        $controller = App::make(ClassroomController::class);
        $delete = $controller->destroy(1);

        $this->assertTrue($delete);
    }

    public function test_delete_real()
    {
        $classroom = factory(Classroom::class)->create();

        $controller = App::make(ClassroomController::class);
        $delete = $controller->destroy($classroom->id);

        $this->assertEquals(1, $delete);
    }

    public function test_delete_by_delete()
    {
        $classroom = factory(Classroom::class)->create();

        $this->delete('api/v1/classroom/'.$classroom->id);

        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->notSeeInDatabase('classrooms', ['id' => $classroom->id]);
    }

    public function test_delete_failed()
    {
        $controller = App::make(ClassroomController::class);
        $delete = $controller->destroy(0);

        $this->assertEquals(0, $delete);
    }

    /**
     * Test associate matters.
     */
    public function test_associate_matters()
    {
        $classroom = factory(Classroom::class)->create();
        $matters = factory(Matter::class, 30)->create();
        $data = [
            'id' => $classroom->id,
            'matters' => [],
        ];
        $random = $matters->random(3)->each(function ($matter) use (&$data) {
            $data['matters'][] = ['id' => $matter->id];
        });
        $this->put('api/v1/classroom/'.$classroom->id.'/matters', $data);
        $this->seeStatusCode(200);
        $this->seeJson(['status' => true]);
        foreach ($data['matters'] as $key => $value) {
            $this->seeInDatabase('classroom_matter', [
                'classroom_id' => $classroom->id,
                'matter_id' => $data['matters'][$key]['id'],
            ]);
        }
    }

    /**
     * Test associate students.
     */
    public function test_associate_students()
    {
        $classroom = factory(Classroom::class)->create();
        $students = factory(Student::class, 30)->create();
        $data = [
            'id' => $classroom->id,
            'students' => [],
        ];
        $random = $students->random(3)->each(function ($student) use (&$data) {
            $data['students'][] = ['id' => $student->id];
        });
        $this->put('api/v1/classroom/'.$classroom->id.'/students', $data);
        $this->seeStatusCode(200);
        $this->seeJson(['status' => true]);
        foreach ($data['students'] as $key => $value) {
            $this->seeInDatabase('classroom_student', [
                'classroom_id' => $classroom->id,
                'student_id' => $data['students'][$key]['id'],
            ]);
        }
    }

    /**
     * Test get students.
     */
    public function test_get_students()
    {
        $classroom = factory(Classroom::class)->create();
        $students = factory(Student::class, 30)->create()->random(5);
        $students->each(function ($student) use ($classroom) {
            $classroom->students()->attach($student);
        });

        $this->get('api/v1/classroom/'.$classroom->id.'/students');
        $this->seeStatusCode(200);

        $response = json_decode($this->response->getContent(), true);

        $this->assertTrue(isset($response['classroom']));
        $this->assertTrue(isset($response['students']));

        $this->seeJson([
            'id' => $classroom->id,
            'name' => $classroom->name,
        ]);

        $students->each(function ($student) use ($classroom) {
            $this->seeJson([
                'id' => $student->id,
                'name' => $student->name,
            ]);

            $this->seeInDatabase('classroom_student', [
                'classroom_id' => $classroom->id,
                'student_id' => $student->id,
            ]);
        });
    }

    public function test_attach_matter_completeds()
    {
        //sets
        $classroom = factory(Classroom::class)->create();
        $matters = factory(Matter::class, 10)->create();
        $students = factory(Student::class, 4)->create();
        $students->each(function ($student) use ($classroom) {
            $classroom->students()->attach($student);
        });

        $matters->each(function ($matter) use ($classroom) {
            $classroom->matters()->attach($matter);
        });

        //expects
        $sync = [];
        foreach ($classroom->matters as $matter) {
            $sync[] = ['id' => $matter->pivot->id];
        }
        $this->put('api/v1/classroom/matters/completeds', $sync);
        //asserts
        $this->seeStatusCode(200);
        $this->seeJsonEquals(['status' => true]);
    }
}
