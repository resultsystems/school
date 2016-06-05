<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-28 08:46:53
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:50
 */

namespace Domain\Classroom;

use App;
use Domain\Matter\Matter;
use Domain\Student\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MatterCompletedServiceTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_attach()
    {
        //set
        $classroom = factory(Classroom::class)->create();
        $matters = factory(Matter::class, 10)->create();
        $students = factory(Student::class, 4)->create();
        $students->each(function ($student) use ($classroom) {
            $classroom->students()->attach($student);
        });

        $matters->each(function ($matter) use ($classroom) {
            $classroom->matters()->attach($matter);
        });

        $service = App::make(MatterCompletedService::class);

        //expect
        $attach = [];
        foreach ($classroom->matters as $matter) {
            $attach[] = ['id' => $matter->pivot->id];
        }
        $response = $service->attach($attach);

        //assert
        $this->assertTrue($response);
        foreach ($attach as $id) {
            foreach ($students as $student) {
                $this->seeInDatabase('classroom_matter_student_completed',
                    [
                        'classroom_matter_id' => $id,
                        'student_id' => $student->id,
                    ]);
            }
        }
    }
}
