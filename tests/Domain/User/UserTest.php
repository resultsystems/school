<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 19:17:53
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:13
 */

namespace Domain\User;

use Carbon\Carbon;
use Domain\Employee\Employee;
use Domain\Student\Student;
use Domain\Teacher\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_create_user()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(User::class, $user);

        $this->assertInstanceOf(Carbon::class, $user->created_at);
        $this->assertInstanceOf(Carbon::class, $user->updated_at);

        $this->seeInDatabase('users', [
            'username' => $user->username,
        ]);
    }

    public function test_create_user_with_value()
    {
        $user = factory(User::class)->create([
            'username' => 'user1',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->seeInDatabase('users', [
            'username' => 'user1',
        ]);
    }

    public function test_create_user_with_deleted()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(User::class, $user);
        $user->delete();

        $this->assertInstanceOf(Carbon::class, $user->created_at);
        $this->assertInstanceOf(Carbon::class, $user->updated_at);
        $this->assertInstanceOf(Carbon::class, $user->deleted_at);
    }

    public function test_create_user_type_employe()
    {
        $empoloye = factory(Employee::class)->create();

        $user = factory(User::class)->create([
            'owner_id' => $empoloye->id,
            'owner_type' => Employee::class,
        ]);

        $this->assertInstanceOf(Employee::class, $user->owner);
    }

    public function test_create_user_type_student()
    {
        $student = factory(Student::class)->create();

        $user = factory(User::class)->create([
            'owner_id' => $student->id,
            'owner_type' => Student::class,
        ]);

        $this->assertInstanceOf(Student::class, $user->owner);
    }

    public function test_create_user_type_teacher()
    {
        $teacher = factory(Teacher::class)->create();

        $user = factory(User::class)->create([
            'owner_id' => $teacher->id,
            'owner_type' => Teacher::class,
        ]);

        $this->assertInstanceOf(Teacher::class, $user->owner);
    }
}
