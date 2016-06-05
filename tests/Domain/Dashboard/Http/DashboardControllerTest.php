<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:01:51
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:59:01
 */

namespace Domain\Dashboard\Http;

use DB;
use Domain\Employee\Employee;
use Domain\Student\Student;
use Domain\Teacher\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class DashboardControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_index()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::select('DELETE from teachers');
        DB::select('DELETE from employees');
        DB::select('DELETE from students');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $employees = factory(Employee::class, 10)->create();
        $students = factory(Student::class, 20)->create();
        $teachers = factory(Teacher::class, 45)->create();

        $employees->random(3)->each(function ($employee) {
            $employee->delete();
        });

        $students->random(8)->each(function ($student) {
            $student->delete();
        });

        $teachers->random(5)->each(function ($teacher) {
            $teacher->delete();
        });

        $this->get('api/v1/dashboard');
        $this->seeStatusCode(200);

        $this->seeJson([
            'employees' => [
                'registers' => 7,
                'deletes' => 3,
            ],
            'students' => [
                'registers' => 12,
                'deletes' => 8,
            ],
            'teachers' => [
                'registers' => 40,
                'deletes' => 5,
            ],
        ]);
    }
}
