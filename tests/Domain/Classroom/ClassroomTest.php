<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 22:00:25
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:49
 */

namespace Domain\Classroom;

use Carbon\Carbon;
use Domain\Classroom\Classroom;
use Domain\Matter\Matter;
use Domain\Schedule\Schedule;
use Domain\Student\Student;
use Domain\Teacher\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClassroomTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_create_classroom()
    {
        $classroom = factory(Classroom::class)->create();

        $this->assertInstanceOf(Classroom::class, $classroom);

        $this->assertInstanceOf(Teacher::class, $classroom->teacher);

        $this->assertInstanceOf(Schedule::class, $classroom->schedule);

        $this->assertInstanceOf(Carbon::class, $classroom->created_at);
        $this->assertInstanceOf(Carbon::class, $classroom->updated_at);

        $this->seeInDatabase('classrooms', [
            'name' => $classroom->name,
        ]);
    }

    public function test_create_classroom_with_students()
    {
        $classroom = factory(Classroom::class)->create();

        $student = factory(Student::class)->create();

        $classroom->students()->attach($student);

        $this->assertInstanceOf(Student::class, $classroom->students->first());
    }

    public function test_create_classroom_with_matters()
    {
        $classroom = factory(Classroom::class)->create();

        $matter = factory(Matter::class)->create();

        $classroom->matters()->attach($matter);

        $this->assertInstanceOf(Matter::class, $classroom->matters->first());
    }
}
