<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:54:46
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:55
 */

namespace Domain\Employee;

use Carbon\Carbon;
use Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_create_employee()
    {
        $employee = factory(Employee::class)->create();

        $this->assertInstanceOf(Employee::class, $employee);

        $this->assertInstanceOf(Carbon::class, $employee->created_at);
        $this->assertInstanceOf(Carbon::class, $employee->updated_at);
        $this->assertNull($employee->deleted_at);

        $this->seeInDatabase('employees', [
            'name' => $employee->name,
        ]);
    }

    public function test_create_employee_with_deleted()
    {
        $employee = factory(Employee::class)->create();

        $this->assertInstanceOf(Employee::class, $employee);
        $employee->delete();

        $this->assertInstanceOf(Carbon::class, $employee->created_at);
        $this->assertInstanceOf(Carbon::class, $employee->updated_at);
        $this->assertInstanceOf(Carbon::class, $employee->deleted_at);
    }

    public function test_create_employee_with_value()
    {
        $employee = factory(Employee::class)->create([
            'name' => 'João',
        ]);

        $this->assertInstanceOf(Employee::class, $employee);

        $this->seeInDatabase('employees', [
            'name' => 'João',
        ]);
    }

    public function test_create_employee_without_user()
    {
        $employee = factory(Employee::class)->create();

        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertNull($employee->user);
    }

    public function test_create_employee_with_user()
    {
        $employee = factory(Employee::class)->create();

        $user = factory(User::class)->create([
            'owner_id' => $employee->id,
            'owner_type' => 'Domain\Employee\Employee',
        ]);

        $this->assertInstanceOf(Employee::class, $employee);

        $this->assertInstanceOf(User::class, $employee->user);
    }
}
