<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:02:16
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:59:00
 */

namespace Domain\Employee\Http;

use App;
use App\Exceptions\RepositoryException;
use Domain\Employee\Employee;
use Domain\Employee\EmployeeRepository;
use Domain\Employee\EmployeeService;
use Domain\Employee\Http\Requests\StoreRequest;
use Domain\Employee\Http\Requests\UpdateRequest;
use Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class EmployeeControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_index_returns_employees()
    {
        factory(Employee::class)->create();

        $controller = App::make(EmployeeController::class);
        $employees = $controller->index();

        $this->assertInstanceOf(LengthAwarePaginator::class, $employees);
        $this->assertInstanceOf(Employee::class, $employees->first());
    }

    public function test_index_returns_employees_by_get()
    {
        Employee::where('id', '>=', 1)->delete();
        $employees = factory(Employee::class, 3)->create();

        $this->get('api/v1/employee');
        $this->seeStatusCode(200);

        foreach ($employees as $employee) {
            $this->seeJson(['name' => $employee->name]);
        }
    }

    public function test_show()
    {
        $model = m::mock(Employee::class);

        $repo = m::mock(EmployeeRepository::class);
        $repo
            ->shouldReceive('get')
            ->once()
            ->andReturn($model);

        App::instance(EmployeeRepository::class, $repo);

        $controller = App::make(EmployeeController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(Employee::class, $get);
    }

    public function test_show_real()
    {
        $employee = factory(Employee::class)->create();

        $controller = App::make(EmployeeController::class);
        $get = $controller->show($employee->id);

        $this->assertInstanceOf(Employee::class, $get);
        $this->assertEquals($get->id, $employee->id);
    }

    public function test_show_by_get()
    {
        $employee = factory(Employee::class)->create();

        $this->get('api/v1/employee/'.$employee->id)
            ->seeStatusCode(200);

        $this->seeJson(['name' => $employee->name]);
    }

    public function test_store()
    {
        $model = m::mock(Employee::class);
        $request = m::mock(StoreRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $service = m::mock(EmployeeService::class);

        $service
            ->shouldReceive('store')
            ->once()
            ->andReturn($model);

        App::instance(StoreRequest::class, $request);

        $controller = App::make(EmployeeController::class);

        $employee = $controller->store($request, $service);
        $this->assertInstanceOf(Employee::class, $employee);
    }

    public function test_store_by_post()
    {
        $username = uniqid();

        $employee = [
            'name' => 'Funcionário x',
            'sex' => 'male',

            'user' => [
                'username' => $username,
                'password' => $username,
                'email' => $username.'@gmail.com',
            ],
        ];

        $this->post('api/v1/employee/', $employee);

        $this->seeStatusCode(200);

        $this->seeJson([
            'name' => 'Funcionário x',
            'sex' => 'male',
            'username' => $username,
        ]);

        $employee = $this->response->original;

        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertInstanceOf(User::class, $employee->user);
        $this->assertEquals($employee->user->owner_id, $employee->id);
        $this->assertEquals($employee->user->owner_type, get_class($employee));
        $this->seeInDatabase('employees', ['id' => $employee->id]);
        $this->seeInDatabase('users', ['id' => $employee->user->id]);
    }

    public function test_store_failed()
    {
        $request = m::mock(StoreRequest::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $error = m::mock(RepositoryException::class);
        $this->setExpectedException(RepositoryException::class);
        $service = App::make(EmployeeService::class);

        App::instance(StoreRequest::class, $request);

        $controller = App::make(EmployeeController::class);
        $controller->store($request, $service);
    }

    public function test_update_by_put()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->create([
            'owner_id' => $employee->id,
            'owner_type' => get_class($employee),
        ]);

        $data = [
            'name' => 'Funcionário x',
            'sex' => $employee->sex,
            'user' => [
                'username' => $employee->user->username,
                'email' => $employee->user->email,
            ],
        ];

        $this->put('api/v1/employee/'.$employee->id, $data);
        $this->seeStatusCode(200);

        $this->seeJson(['name' => 'Funcionário x']);
        $this->assertInstanceOf(Employee::class, $this->response->original);
        $this->seeInDatabase('employees', ['id' => $this->response->original->id]);
    }

    public function test_update_failed()
    {
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $this->setExpectedException(RepositoryException::class);

        App::instance(UpdateRequest::class, $request);
        $controller = App::make(EmployeeController::class);
        $service = App::make(EmployeeService::class);

        $controller->update($request, $service, 1);
    }

    public function test_delete()
    {
        $repo = m::mock(EmployeeRepository::class);
        $repo
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        App::instance(EmployeeRepository::class, $repo);

        $controller = App::make(EmployeeController::class);
        $delete = $controller->destroy(1);

        $this->assertTrue($delete);
    }

    public function test_delete_real()
    {
        $employee = factory(Employee::class)->create();

        $controller = App::make(EmployeeController::class);
        $delete = $controller->destroy($employee->id);

        $this->assertEquals(1, $delete);
    }

    public function test_delete_by_delete()
    {
        $employee = factory(Employee::class)->create();

        $this->delete('api/v1/employee/'.$employee->id);

        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->seeInDatabase('employees', ['id' => $employee->id]);
        $this->notSeeInDatabase('employees', ['id' => $employee->id, 'deleted_at' => null]);
    }

    public function test_delete_failed()
    {
        $controller = App::make(EmployeeController::class);
        $delete = $controller->destroy(0);

        $this->assertEquals(0, $delete);
    }

    public function test_force_delete()
    {
        $repo = m::mock(EmployeeRepository::class);
        $repo
            ->shouldReceive('forceDelete')
            ->once()
            ->andReturn(true);

        App::instance(EmployeeRepository::class, $repo);

        $controller = App::make(EmployeeController::class);
        $forceDelete = $controller->forceDelete(1);

        $this->assertEquals(1, $forceDelete);
    }

    public function test_force_delete_by_delete()
    {
        $employee = factory(Employee::class)->create();

        $this->delete('api/v1/trashed/employee/'.$employee->id);

        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->notSeeInDatabase('employees', ['id' => $employee->id]);
    }

    public function test_force_delete_real()
    {
        $employee = factory(Employee::class)->create();

        $controller = App::make(EmployeeController::class);
        $forceDelete = $controller->forceDelete($employee->id);

        $repo = App::make(EmployeeRepository::class);

        $this->setExpectedException(RepositoryException::class);

        $get = $repo->withTrashed()->get($employee->id);
    }

    public function test_force_delete_failed()
    {
        $controller = App::make(EmployeeController::class);
        $forceDelete = $controller->forceDelete(0);

        $this->assertEquals(0, $forceDelete);
    }

    public function test_restore()
    {
        $repo = m::mock(EmployeeRepository::class);
        $repo
            ->shouldReceive('restore')
            ->once()
            ->andReturn(true);

        App::instance(EmployeeRepository::class, $repo);

        $controller = App::make(EmployeeController::class);
        $restore = $controller->restore(1);

        $this->assertTrue($restore);
    }

    public function test_restore_by_put()
    {
        $employee = factory(Employee::class)->create();
        $employee->delete();

        $this->put('api/v1/restore/employee/'.$employee->id);
        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->seeInDatabase('employees', ['id' => $employee->id]);
        $this->notSeeInDatabase('employees', ['id' => $employee->id, 'deleted_at' => $employee->deleted_at]);
    }

    public function test_restore_real()
    {
        $employee = factory(Employee::class)->create();
        $employee->delete();

        $controller = App::make(EmployeeController::class);
        $restore = $controller->restore($employee->id);

        $this->assertEquals(1, $restore);

        $repo = App::make(EmployeeRepository::class);

        $get = $repo->get($employee->id);
        $this->assertInstanceOf(Employee::class, $get);
        $this->assertNull($get->deleted_at);
    }

    public function test_restore_failed()
    {
        $controller = App::make(EmployeeController::class);
        $restore = $controller->restore(1);

        $this->assertEquals(0, $restore);
    }
}
