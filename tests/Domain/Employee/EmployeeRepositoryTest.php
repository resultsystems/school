<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:34
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:55
 */

namespace Domain\Employee;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_store()
    {
        $repo = App::make(EmployeeRepository::class);

        $store = $repo->store(['name' => 'oi']);

        $this->assertInstanceOf(Employee::class, $store);
        $this->assertInstanceOf(Carbon::class, $store->created_at);
    }

    public function test_update()
    {
        $repo = App::make(EmployeeRepository::class);

        $employee = factory(Employee::class)->create();

        $update = $repo->update(['name' => 'oi 2'], $employee->id);

        $this->assertInstanceOf(Employee::class, $update);
        $this->assertEquals($employee->id, $update->id);
    }

    public function test_get()
    {
        $repo = App::make(EmployeeRepository::class);

        $employee = factory(Employee::class)->create();

        $get = $repo->get($employee->id);

        $this->assertInstanceOf(Employee::class, $get);
        $this->assertEquals($get->id, $employee->id);
    }

    public function test_all()
    {
        $repo = App::make(EmployeeRepository::class);

        factory(Employee::class)->create();

        $all = $repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $all);
        $this->assertInstanceOf(Employee::class, $all->first());
    }

    public function test_delete()
    {
        $repo = App::make(EmployeeRepository::class);

        $employee = factory(Employee::class)->create();

        $delete = $repo->delete($employee->id);

        $trashed = $repo->onlyTrashed()->get($employee->id);

        $this->assertEquals(1, $delete);
        $this->assertInstanceOf(Carbon::class, $trashed->deleted_at);
        $this->assertInstanceOf(Employee::class, $trashed);

        $this->setExpectedException(RepositoryException::class);
        $repo->withoutTrashed()->get($employee->id);
    }

    public function test_force_delete()
    {
        $repo = App::make(EmployeeRepository::class);

        $employee = factory(Employee::class)->create();

        $repo->forceDelete($employee->id);

        $this->setExpectedException(RepositoryException::class);
        $trashed = $repo->onlyTrashed()->get($employee->id);
    }

    public function test_restore()
    {
        $repo = App::make(EmployeeRepository::class);

        $employee = factory(Employee::class)->create();

        $repo->delete($employee->id);

        $restore = $repo->restore($employee->id);

        $get = $repo->get($employee->id);

        $this->assertEquals(1, $restore);
        $this->assertNull($get->deleted_at);
        $this->assertInstanceOf(Employee::class, $get);
    }
}
