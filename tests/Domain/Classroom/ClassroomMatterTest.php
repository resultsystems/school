<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:30
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:48
 */

namespace Domain\Classroom;

use Carbon\Carbon;
use Domain\Matter\Matter;
use Domain\Student\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClassroomMatterTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_create_classroom_matter()
    {
        $classroomMatter = factory(ClassroomMatter::class)->create();

        $this->assertInstanceOf(ClassroomMatter::class, $classroomMatter);

        $this->assertInstanceOf(Classroom::class, $classroomMatter->classroom);
        $this->assertInstanceOf(Matter::class, $classroomMatter->matter);

        $this->assertInstanceOf(Carbon::class, $classroomMatter->created_at);
        $this->assertInstanceOf(Carbon::class, $classroomMatter->updated_at);

        $this->seeInDatabase('classroom_matter', [
            'id' => $classroomMatter->id,
        ]);
    }

    public function teste_create_classroom_matter_with_classroom()
    {
        $classroom = factory(Classroom::class)->create();
        $classroomMatter = factory(ClassroomMatter::class)->create([
            'classroom_id' => $classroom->id,
        ]);

        $this->seeInDatabase('classroom_matter', [
            'classroom_id' => $classroom->id,
        ]);
    }

    public function teste_create_classroom_matter_with_matter()
    {
        $matter = factory(Matter::class)->create();
        $classroomMatter = factory(ClassroomMatter::class)->create([
            'matter_id' => $matter->id,
        ]);

        $this->seeInDatabase('classroom_matter', [
            'matter_id' => $matter->id,
        ]);
    }

    public function test_create_classroom_matter_with_student_completed()
    {
        $classroomMatter = factory(ClassroomMatter::class)->create();
        $student = factory(Student::class)->create();
        $classroomMatter->studentCompletds()->attach($student);

        $this->assertInstanceOf(Student::class, $classroomMatter->studentCompletds()->first());

        $this->seeInDatabase('classroom_matter_student_completed', [
            'classroom_matter_id' => $classroomMatter->id,
            'student_id' => $student->id,
        ]);
    }
}
