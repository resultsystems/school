<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-07 08:50:42
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:59:07
 */

namespace Domain\Auth;

use Domain\Employee\Employee;
use Domain\Student\Student;
use Domain\Teacher\Teacher;
use Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_cant_login()
    {
        $data = [
            'username' => uniqid(),
            'type' => 'Teacher',
            'password' => uniqid(),
        ];

        $this->post('api/v1/auth/login', $data);
        $this->seeStatusCode(403);
    }

    public function test_login_teacher()
    {
        $teacher = factory(Teacher::class)->create();
        $user = factory(User::class)->create([
            'owner_type' => Teacher::class,
            'owner_id' => $teacher->id,
            'password' => bcrypt('teste123'),
        ]);
        $data = [
            'username' => $user->username,
            'type' => 'Teacher',
            'password' => 'teste123',
        ];

        $this->post('api/v1/auth/login', $data);
        $this->seeStatusCode(200);

        $this->seeJson(['username' => $user->username]);
    }

    public function test_login_employee()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->create([
            'owner_type' => Employee::class,
            'owner_id' => $employee->id,
            'password' => bcrypt('teste123'),
        ]);
        $data = [
            'username' => $user->username,
            'type' => 'Employee',
            'password' => 'teste123',
        ];

        $this->post('api/v1/auth/login', $data);
        $this->seeStatusCode(200);

        $this->seeJson(['username' => $user->username]);
    }

    public function test_login_student()
    {
        $student = factory(Student::class)->create();
        $user = factory(User::class)->create([
            'owner_type' => Student::class,
            'owner_id' => $student->id,
            'password' => bcrypt('teste123'),
        ]);
        $data = [
            'username' => $user->username,
            'type' => 'Student',
            'password' => 'teste123',
        ];

        $this->post('api/v1/auth/login', $data);
        $this->seeStatusCode(200);

        $this->seeJson(['username' => $user->username]);
    }

    public function test_logout()
    {
        $this->test_login_student();

        $token = json_decode($this->response->getContent())->token;

        $this->get('api/v1/auth/logout', ['HTTP_Authorization' => 'Bearer: '.$token]);

        //$this->call('GET', '/api/v1/auth/logout', [], [], [], ['HTTP_Authorization' => 'Bearer: '.$token], []);

        $this->seeStatusCode(200);

        $this->seeJson(['logout' => true]);
    }
}
