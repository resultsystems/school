<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:55:40
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:10
 */

namespace Domain\Teacher;

use Carbon\Carbon;
use Domain\Classroom\Classroom;
use Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeacherTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_create_teacher()
    {
        $teacher = factory(Teacher::class)->create();

        $this->assertInstanceOf(Teacher::class, $teacher);

        $this->assertInstanceOf(Carbon::class, $teacher->created_at);
        $this->assertInstanceOf(Carbon::class, $teacher->updated_at);

        $this->seeInDatabase('teachers', [
            'name' => $teacher->name,
        ]);
    }

    public function test_create_teacher_with_value()
    {
        $teacher = factory(Teacher::class)->create([
            'name' => 'Professor x',
        ]);

        $this->assertInstanceOf(Teacher::class, $teacher);
        $this->seeInDatabase('teachers', [
            'name' => 'Professor x',
        ]);
    }

    public function test_create_teacher_with_deleted()
    {
        $teacher = factory(Teacher::class)->create();

        $this->assertInstanceOf(Teacher::class, $teacher);
        $teacher->delete();

        $this->assertInstanceOf(Carbon::class, $teacher->created_at);
        $this->assertInstanceOf(Carbon::class, $teacher->updated_at);
        $this->assertInstanceOf(Carbon::class, $teacher->deleted_at);
    }

    public function test_create_teacher_without_user()
    {
        $teacher = factory(Teacher::class)->create();

        $this->assertInstanceOf(Teacher::class, $teacher);
        $this->assertNull($teacher->user);
    }

    public function test_create_teacher_with_user()
    {
        $teacher = factory(Teacher::class)->create();

        $user = factory(User::class)->create([
            'owner_id' => $teacher->id,
            'owner_type' => 'Domain\Teacher\Teacher',
        ]);

        $this->assertInstanceOf(Teacher::class, $teacher);

        $this->assertInstanceOf(User::class, $teacher->user);
    }

    public function test_create_teacher_with_classroom()
    {
        $teacher = factory(Teacher::class)->create();

        $classroom = factory(Classroom::class)->create([
            'teacher_id' => $teacher->id,
        ]);

        $this->assertInstanceOf(Classroom::class, $teacher->classrooms->first());
    }
}
