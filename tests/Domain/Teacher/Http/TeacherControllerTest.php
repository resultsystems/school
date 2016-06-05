<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:03:58
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:58:51
 */

namespace Domain\Teacher\Http;

use App;
use App\Exceptions\RepositoryException;
use Domain\Classroom\Classroom;
use Domain\Matter\Matter;
use Domain\Teacher\Http\Requests\StoreRequest;
use Domain\Teacher\Http\Requests\UpdateRequest;
use Domain\Teacher\Teacher;
use Domain\Teacher\TeacherRepository;
use Domain\Teacher\TeacherService;
use Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class TeacherControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_index_returns_teachers()
    {
        Teacher::where('id', '>=', 1)->delete();
        $teacher = factory(Teacher::class)->create();
        $classroom = factory(Classroom::class)->create([
            'teacher_id' => $teacher->id,
        ]);

        $controller = App::make(TeacherController::class);
        $teachers = $controller->index();

        $this->assertInstanceOf(LengthAwarePaginator::class, $teachers);
        $this->assertInstanceOf(Teacher::class, $teachers->first());
        $this->assertInstanceOf(Classroom::class, $teachers->first()->classrooms->first());
    }

    public function test_index_returns_teachers_by_get()
    {
        Teacher::where('id', '>=', 1)->delete();
        $teachers = factory(Teacher::class, 3)->create();

        $this->get('api/v1/teacher');
        $this->seeStatusCode(200);

        foreach ($teachers as $teacher) {
            $this->seeJson(['name' => $teacher->name]);
        }
    }

    public function test_show()
    {
        $model = m::mock(Teacher::class);

        $repo = m::mock(TeacherRepository::class);
        $repo
            ->shouldReceive('get')
            ->once()
            ->andReturn($model);

        App::instance(TeacherRepository::class, $repo);

        $controller = App::make(TeacherController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(Teacher::class, $get);
    }

    public function test_show_real()
    {
        $teacher = factory(Teacher::class)->create();

        $controller = App::make(TeacherController::class);
        $get = $controller->show($teacher->id);

        $this->assertInstanceOf(Teacher::class, $get);
        $this->assertEquals($get->id, $teacher->id);
    }

    public function test_show_by_get()
    {
        $teacher = factory(Teacher::class)->create();

        $this->get('api/v1/teacher/'.$teacher->id)
            ->seeStatusCode(200);

        $this->seeJson(['name' => $teacher->name]);
    }

    public function test_store()
    {
        $model = m::mock(Teacher::class);
        $request = m::mock(StoreRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $service = m::mock(TeacherService::class);

        $service
            ->shouldReceive('store')
            ->once()
            ->andReturn($model);

        App::instance(TeacherService::class, $service);

        $controller = App::make(TeacherController::class);

        $teacher = $controller->store($request, $service);
        $this->assertInstanceOf(Teacher::class, $teacher);
    }

    public function test_store_by_post()
    {
        $username = uniqid();

        $faker = factory(Teacher::class)->make();

        $teacher = [
            'name' => 'Professor x',
            'sex' => 'male',
            'cpf' => $faker->cpf,

            'postcode' => $faker->postcode,
            'street' => $faker->street,
            'number' => $faker->number,
            'district' => $faker->district,
            'city' => $faker->city,
            'state' => $faker->state,
            'phone' => $faker->phone,
            'cellphone' => $faker->cellphone,

            'salary' => $faker->salary,
            'type_salary' => $faker->type_salary,
            'status' => $faker->status,

            'user' => [
                'username' => $username,
                'password' => $username,
                'email' => $username.'@gmail.com',
            ],
        ];

        $this->post('api/v1/teacher/', $teacher);

        $this->seeStatusCode(200);

        $this->seeJson([
            'name' => 'Professor x',
            'sex' => 'male',
            'username' => $username,
        ]);

        $teacher = $this->response->original;

        $this->assertInstanceOf(Teacher::class, $teacher);
        $this->assertInstanceOf(User::class, $teacher->user);
        $this->assertEquals($teacher->user->owner_id, $teacher->id);
        $this->assertEquals($teacher->user->owner_type, get_class($teacher));
        $this->seeInDatabase('teachers', ['id' => $teacher->id]);
        $this->seeInDatabase('users', ['id' => $teacher->user->id]);
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
        $service = App::make(TeacherService::class);

        $controller = App::make(TeacherController::class);
        $controller->store($request, $service);
    }

    public function test_update_by_put()
    {
        $teacher = factory(Teacher::class)->create();
        $user = factory(User::class)->create([
            'owner_id' => $teacher->id,
            'owner_type' => get_class($teacher),
        ]);

        $data = [
            'name' => 'Professor x',
            'cpf' => $teacher->cpf,
            'sex' => $teacher->sex,

            'postcode' => $teacher->postcode,
            'street' => $teacher->street,
            'number' => $teacher->number,
            'district' => $teacher->district,
            'city' => $teacher->city,
            'state' => $teacher->state,
            'phone' => $teacher->phone,
            'cellphone' => $teacher->cellphone,

            'salary' => $teacher->salary,
            'type_salary' => $teacher->type_salary,
            'status' => $teacher->status,
            'user' => [
                'username' => $teacher->user->username,
                'email' => $teacher->user->email,
            ],
        ];

        $this->put('api/v1/teacher/'.$teacher->id, $data);

        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Professor x']);
        $this->assertInstanceOf(Teacher::class, $this->response->original);
        $this->seeInDatabase('teachers', ['id' => $this->response->original->id]);
    }

    public function test_update_failed()
    {
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $this->setExpectedException(RepositoryException::class);

        App::instance(UpdateRequest::class, $request);
        $controller = App::make(TeacherController::class);
        $service = App::make(TeacherService::class);

        $controller->update($request, $service, 1);
    }

    public function test_delete()
    {
        $repo = m::mock(TeacherRepository::class);
        $repo
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        App::instance(TeacherRepository::class, $repo);

        $controller = App::make(TeacherController::class);
        $delete = $controller->destroy(1);

        $this->assertTrue($delete);
    }

    public function test_delete_real()
    {
        $teacher = factory(Teacher::class)->create();

        $controller = App::make(TeacherController::class);
        $delete = $controller->destroy($teacher->id);

        $this->assertEquals(1, $delete);
    }

    public function test_delete_by_delete()
    {
        $teacher = factory(Teacher::class)->create();

        $this->delete('api/v1/teacher/'.$teacher->id)
            ->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->seeInDatabase('teachers', ['id' => $teacher->id]);
        $this->notSeeInDatabase('teachers', ['id' => $teacher->id, 'deleted_at' => null]);
    }

    public function test_delete_failed()
    {
        $controller = App::make(TeacherController::class);
        $delete = $controller->destroy(0);

        $this->assertEquals(0, $delete);
    }

    public function test_force_delete()
    {
        $repo = m::mock(TeacherRepository::class);
        $repo
            ->shouldReceive('forceDelete')
            ->once()
            ->andReturn(true);

        App::instance(TeacherRepository::class, $repo);

        $controller = App::make(TeacherController::class);
        $forceDelete = $controller->forceDelete(1);

        $this->assertEquals(1, $forceDelete);
    }

    public function test_force_delete_by_delete()
    {
        $teacher = factory(Teacher::class)->create();

        $this->delete('api/v1/trashed/teacher/'.$teacher->id);
        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->notSeeInDatabase('teachers', ['id' => $teacher->id]);
    }

    public function test_force_delete_real()
    {
        $teacher = factory(Teacher::class)->create();

        $controller = App::make(TeacherController::class);
        $forceDelete = $controller->forceDelete($teacher->id);

        $repo = App::make(TeacherRepository::class);

        $this->setExpectedException(RepositoryException::class);

        $get = $repo->withTrashed()->get($teacher->id);
    }

    public function test_force_delete_failed()
    {
        $controller = App::make(TeacherController::class);
        $forceDelete = $controller->forceDelete(0);

        $this->assertEquals(0, $forceDelete);
    }

    public function test_restore()
    {
        $repo = m::mock(TeacherRepository::class);
        $repo
            ->shouldReceive('restore')
            ->once()
            ->andReturn(true);

        App::instance(TeacherRepository::class, $repo);

        $controller = App::make(TeacherController::class);
        $restore = $controller->restore(1);

        $this->assertTrue($restore);
    }

    public function test_restore_by_put()
    {
        $teacher = factory(Teacher::class)->create();
        $teacher->delete();

        $this->put('api/v1/restore/teacher/'.$teacher->id);
        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->seeInDatabase('teachers', ['id' => $teacher->id]);
        $this->notSeeInDatabase('teachers', ['id' => $teacher->id, 'deleted_at' => $teacher->deleted_at]);
    }

    public function test_restore_real()
    {
        $teacher = factory(Teacher::class)->create();
        $teacher->delete();

        $controller = App::make(TeacherController::class);
        $restore = $controller->restore($teacher->id);

        $this->assertEquals(1, $restore);

        $repo = App::make(TeacherRepository::class);

        $get = $repo->get($teacher->id);
        $this->assertInstanceOf(Teacher::class, $get);
        $this->assertNull($get->deleted_at);
    }

    public function test_restore_failed()
    {
        $controller = App::make(TeacherController::class);
        $restore = $controller->restore(1);

        $this->assertEquals(0, $restore);
    }

    /**
     * Test associate matters.
     */
    public function test_associate_matters()
    {
        $teacher = factory(Teacher::class)->create();
        $matters = factory(Matter::class, 30)->create();
        $data = [
            'id' => $teacher->id,
            'matters' => [],
        ];
        $random = $matters->random(3)->each(function ($matter) use (&$data) {
            $data['matters'][] = ['id' => $matter->id];
        });
        $this->put('api/v1/teacher/'.$teacher->id.'/matters', $data);
        $this->seeStatusCode(200);
        $this->seeJson(['status' => 'ok']);
        foreach ($data['matters'] as $key => $value) {
            $this->seeInDatabase('teacher_matter', [
                'teacher_id' => $teacher->id,
                'matter_id' => $data['matters'][$key]['id'],
            ]);
        }
    }
}
