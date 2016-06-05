<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:04:34
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:58:53
 */

namespace Domain\Student\Http;

use App;
use App\Exceptions\RepositoryException;
use Domain\Billet\Billet;
use Domain\Classroom\Classroom;
use Domain\Matter\Matter;
use Domain\Student\Http\Requests\StoreRequest;
use Domain\Student\Http\Requests\UpdateRequest;
use Domain\Student\Student;
use Domain\Student\StudentRepository;
use Domain\Student\StudentService;
use Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class StudentControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_index_returns_all_students()
    {
        factory(Student::class)->create();

        $controller = App::make(StudentController::class);
        $students = $controller->index();

        $this->assertInstanceOf(LengthAwarePaginator::class, $students);
        $this->assertInstanceOf(Student::class, $students->first());
    }

    public function test_index_returns_all_students_by_get()
    {
        Student::where('id', '>=', 1)->delete();
        $students = factory(Student::class, 3)->create();

        $this->get('api/v1/student');
        $this->seeStatusCode(200);

        foreach ($students as $student) {
            $this->seeJson(['name' => $student->name]);
        }
    }

    public function test_show()
    {
        $model = m::mock(Student::class);

        $repo = m::mock(StudentRepository::class);
        $repo
            ->shouldReceive('get')
            ->once()
            ->andReturn($model);

        App::instance(StudentRepository::class, $repo);

        $controller = App::make(StudentController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(Student::class, $get);
    }

    public function test_show_real()
    {
        $student = factory(Student::class)->create();

        $controller = App::make(StudentController::class);
        $get = $controller->show($student->id);

        $this->assertInstanceOf(Student::class, $get);
        $this->assertEquals($get->id, $student->id);
    }

    public function test_show_by_get()
    {
        $student = factory(Student::class)->create();

        $this->get('api/v1/student/'.$student->id)
            ->seeStatusCode(200);

        $this->seeJson(['name' => $student->name]);
    }

    public function test_store()
    {
        $model = m::mock(Student::class);
        $request = m::mock(StoreRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $service = m::mock(StudentService::class);

        $service
            ->shouldReceive('store')
            ->once()
            ->andReturn($model);

        App::instance(StudentService::class, $service);

        $controller = App::make(StudentController::class);

        $student = $controller->store($request, $service);
        $this->assertInstanceOf(Student::class, $student);
    }

    public function test_store_by_post()
    {
        $username = uniqid();

        $student = [
            'name' => 'Aluno x',
            'sex' => 'male',
            'responsible' => 'Responsável',
            'phone_responsible' => '4199999999',

            'postcode' => '12345678',
            'street' => 'Rua x',
            'number' => '1',
            'district' => 'Bairro',
            'city' => 'Cidade',
            'state' => 'PR',
            'phone' => '4133333333',
            'cellphone' => '4199999999',

            'monthly_payment' => 10,
            'day_of_payment' => 15,
            'installments' => 5,

            'user' => [
                'username' => $username,
                'password' => $username,
                'email' => $username.'@gmail.com',
            ],

            'status' => true, ];

        $this->post('api/v1/student/', $student);

        $this->seeStatusCode(200);

        $this->seeJson([
            'name' => 'Aluno x',
            'sex' => 'male',
            'username' => $username,
        ]);

        $student = $this->response->original;

        $this->assertInstanceOf(Student::class, $student);
        $this->assertInstanceOf(User::class, $student->user);
        $this->assertEquals($student->user->owner_id, $student->id);
        $this->assertEquals($student->user->owner_type, get_class($student));
        $this->seeInDatabase('students', ['id' => $student->id]);
        $this->seeInDatabase('users', ['id' => $student->user->id]);
    }

    public function test_store_failed()
    {
        $request = m::mock(StoreRequest::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);
        $service = App::make(StudentService::class);

        $error = m::mock(RepositoryException::class);
        $this->setExpectedException(RepositoryException::class);

        $controller = App::make(StudentController::class);
        $controller->store($request, $service);
    }

    public function test_update_by_put()
    {
        $student = factory(Student::class)->create();
        $user = factory(User::class)->create([
            'owner_id' => $student->id,
            'owner_type' => get_class($student),
        ]);

        $data = [
            'name' => 'Aluno x',
            'responsible' => 'Responsável',
            'phone_responsible' => '4199999999',
            'sex' => $student->sex,

            'postcode' => '12345678',
            'street' => 'Rua x',
            'number' => '1',
            'district' => 'Bairro',
            'city' => 'Cidade',
            'state' => 'PR',
            'phone' => '4133333333',
            'cellphone' => '4199999999',

            'monthly_payment' => 10,
            'day_of_payment' => 15,
            'installments' => 5,

            'status' => true,
            'user' => [
                'username' => $student->user->username,
                'email' => $student->user->email,
            ],
        ];
        $this->put('api/v1/student/'.$student->id, $data);
        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Aluno x']);
        $this->assertInstanceOf(Student::class, $this->response->original);
        $this->seeInDatabase('students', ['id' => $this->response->original->id]);
    }

    public function test_update_failed()
    {
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $this->setExpectedException(RepositoryException::class);

        App::instance(UpdateRequest::class, $request);
        $controller = App::make(StudentController::class);
        $service = App::make(StudentService::class);

        $controller->update($request, $service, 1);
    }

    public function test_delete()
    {
        $repo = m::mock(StudentRepository::class);
        $repo
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        App::instance(StudentRepository::class, $repo);

        $controller = App::make(StudentController::class);
        $delete = $controller->destroy(1);

        $this->assertTrue($delete);
    }

    public function test_delete_real()
    {
        $student = factory(Student::class)->create();

        $controller = App::make(StudentController::class);
        $delete = $controller->destroy($student->id);

        $this->assertEquals(1, $delete);
    }

    public function test_delete_by_delete()
    {
        $student = factory(Student::class)->create();

        $this->delete('api/v1/student/'.$student->id)
            ->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->seeInDatabase('students', ['id' => $student->id]);
        $this->notSeeInDatabase('students', ['id' => $student->id, 'deleted_at' => null]);
    }

    public function test_delete_failed()
    {
        $controller = App::make(StudentController::class);
        $delete = $controller->destroy(0);

        $this->assertEquals(0, $delete);
    }

    public function test_force_delete()
    {
        $repo = m::mock(StudentRepository::class);
        $repo
            ->shouldReceive('forceDelete')
            ->once()
            ->andReturn(true);

        App::instance(StudentRepository::class, $repo);

        $controller = App::make(StudentController::class);
        $forceDelete = $controller->forceDelete(1);

        $this->assertEquals(1, $forceDelete);
    }

    public function test_force_delete_by_delete()
    {
        $student = factory(Student::class)->create();

        $this->delete('api/v1/trashed/student/'.$student->id);
        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->notSeeInDatabase('students', ['id' => $student->id]);
    }

    public function test_force_delete_real()
    {
        $student = factory(Student::class)->create();

        $controller = App::make(StudentController::class);
        $forceDelete = $controller->forceDelete($student->id);

        $repo = App::make(StudentRepository::class);

        $this->setExpectedException(RepositoryException::class);

        $get = $repo->withTrashed()->get($student->id);
    }

    public function test_force_delete_failed()
    {
        $controller = App::make(StudentController::class);
        $forceDelete = $controller->forceDelete(0);

        $this->assertEquals(0, $forceDelete);
    }

    public function test_restore()
    {
        $repo = m::mock(StudentRepository::class);
        $repo
            ->shouldReceive('restore')
            ->once()
            ->andReturn(true);

        App::instance(StudentRepository::class, $repo);

        $controller = App::make(StudentController::class);
        $restore = $controller->restore(1);

        $this->assertTrue($restore);
    }

    public function test_restore_by_put()
    {
        $student = factory(Student::class)->create();
        $student->delete();

        $this->put('api/v1/restore/student/'.$student->id);

        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->seeInDatabase('students', ['id' => $student->id]);
        $this->notSeeInDatabase('students', ['id' => $student->id, 'deleted_at' => $student->deleted_at]);
    }

    public function test_restore_real()
    {
        $student = factory(Student::class)->create();
        $student->delete();

        $controller = App::make(StudentController::class);
        $restore = $controller->restore($student->id);

        $this->assertEquals(1, $restore);

        $repo = App::make(StudentRepository::class);

        $get = $repo->get($student->id);
        $this->assertInstanceOf(Student::class, $get);
        $this->assertNull($get->deleted_at);
    }

    public function test_restore_failed()
    {
        $controller = App::make(StudentController::class);
        $restore = $controller->restore(1);

        $this->assertEquals(0, $restore);
    }

    public function test_get_billets()
    {
        $student = factory(Student::class)->create();
        $billets = factory(Billet::class, 5)->create([
            'student_id' => $student->id,
        ]);

        $this->get('api/v1/student/'.$student->id.'/billets');
        $response = json_decode($this->response->getContent(), true);
        $this->seeStatusCode(200);

        foreach ($billets as $billet) {
            $this->seeJson(['due_date' => $billet->due_date->toDateTimeString()]);
            $this->seeInDatabase('billets', [
                'student_id' => $student->id,
                'id' => $billet->id,
            ]);
        }
    }

    public function test_get_classrooms()
    {
        $student = factory(Student::class)->create();

        $classrooms = factory(Classroom::class, 10)->create();

        $classrooms->each(function ($classroom) use ($student) {
            $student->classrooms()->attach($classroom);
        });

        $this->get('api/v1/student/'.$student->id.'/classrooms');
        $response = json_decode($this->response->getContent(), true);
        $this->seeStatusCode(200);

        foreach ($classrooms as $classroom) {
            $this->seeJson([
                'id' => $classroom->id,
                'name' => $classroom->name,
            ]);
        }
        foreach ($response as $classroom) {
            $this->assertFalse(isset($classroom['matters']));
        }
    }

    public function test_get_student_classrooms_and_matters()
    {
        $student = factory(Student::class)->create();
        $matters = factory(Matter::class, 40)->create();

        $classrooms = factory(Classroom::class, 10)->create();

        $classrooms->each(function ($classroom) use ($student, $matters) {
            $student->classrooms()->attach($classroom);
            $matters->random(rand(3, 5))->each(function ($matter) use ($classroom) {
                $classroom->matters()->attach($matter);
            });
        });

        $this->get('api/v1/student/'.$student->id.'/classrooms/matters');
        $response = json_decode($this->response->getContent(), true);
        $this->seeStatusCode(200);

        foreach ($student['classrooms'] as $classroom) {
            $this->seeJson([
                'id' => $classroom->id,
                'name' => $classroom->name,
            ]);
        }

        foreach ($response['classrooms'] as $classroom) {
            $this->assertTrue(isset($classroom['matters']));
        }
        $this->assertTrue(isset($response['matter_completeds']));
    }

    public function test_sync_matters_completeds()
    {
        //set
        $classroom = factory(Classroom::class)->create();
        $matters = factory(Matter::class, 10)->create();
        $classroom2 = factory(Classroom::class)->create();

        $student = factory(Student::class)->create();
        $matters->random(4)->each(function ($matter) use ($classroom) {
            $classroom->matters()->attach($matter);
        });

        $matters->random(3)->each(function ($matter) use ($classroom2) {
            $classroom2->matters()->attach($matter);
        });

        //expect
        $sync = [];
        foreach ($classroom->matters as $matter) {
            $sync[] = ['id' => $matter->pivot->id];
        }
        foreach ($classroom2->matters as $matter) {
            $sync[] = ['id' => $matter->pivot->id];
        }
        $path = 'api/v1/student/'.$student->id.'/matters/completeds/sync';
        $this->put($path, $sync);
        $this->seeStatusCode(200);

        $this->seeJsonEquals(['status' => true]);
    }
}
