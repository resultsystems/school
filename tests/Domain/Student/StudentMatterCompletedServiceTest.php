<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-28 06:55:48
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:05
 */

namespace Domain\Student;

use App;
use Domain\Classroom\Classroom;
use Domain\Matter\Matter;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StudentMatterCompletedServiceTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_sync()
    {
        //set
        $classroom = factory(Classroom::class)->create();
        $matters = factory(Matter::class, 10)->create();
        $classroom2 = factory(Classroom::class)->create();

        $student = factory(Student::class)->create();
        $matters->random(4)->each(function ($matter) use ($classroom) {
            $classroom->matters()->attach($matter);
        });

        $matters->random(3)->each(function ($matter) use ($classroom2) {
            $classroom2->matters()->attach($matter);
        });
        $service = App::make(StudentMatterCompletedService::class);

        //expect
        $sync = [];
        foreach ($classroom->matters as $matter) {
            $sync[] = ['id' => $matter->pivot->id];
        }
        foreach ($classroom2->matters as $matter) {
            $sync[] = ['id' => $matter->pivot->id];
        }
        $response = $service->sync($student->id, $sync);

        //assert
        $this->assertTrue($response);
        foreach ($sync as $id) {
            $this->seeInDatabase('classroom_matter_student_completed',
                [
                    'classroom_matter_id' => $id,
                    'student_id' => $student->id,
                ]);
        }
    }

    public function test_sync_null()
    {
        //set
        $classroom = factory(Classroom::class)->create();
        $matters = factory(Matter::class, 10)->create();
        $classroom2 = factory(Classroom::class)->create();

        $student = factory(Student::class)->create();
        $matters->random(4)->each(function ($matter) use ($classroom) {
            $classroom->matters()->attach($matter);
        });

        $matters->random(3)->each(function ($matter) use ($classroom2) {
            $classroom2->matters()->attach($matter);
        });
        $service = App::make(StudentMatterCompletedService::class);

        //expect
        $sync = [];
        foreach ($classroom->matters as $matter) {
            $sync[] = ['id' => $matter->pivot->id];
        }
        foreach ($classroom2->matters as $matter) {
            $sync[] = ['id' => $matter->pivot->id];
        }
        $response = $service->sync($student->id, []);

        //assert
        $this->assertTrue($response);
        foreach ($sync as $id) {
            $this->notSeeInDatabase('classroom_matter_student_completed',
                [
                    'classroom_matter_id' => $id,
                    'student_id' => $student->id,
                ]);
        }
    }

    public function test_sync_student_dont_exists()
    {
        //set
        $service = App::make(StudentMatterCompletedService::class);

        //expect
        $response = $service->sync(0, []);

        //assert
        $this->assertFalse($response);
    }
}
