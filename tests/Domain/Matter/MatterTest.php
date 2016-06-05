<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 22:08:03
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:00
 */

namespace Domain\Matter;

use Carbon\Carbon;
use Domain\Classroom\Classroom;
use Domain\Lesson\Lesson;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MatterTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_create_matter()
    {
        $matter = factory(Matter::class)->create();

        $this->assertInstanceOf(Carbon::class, $matter->created_at);
        $this->assertInstanceOf(Carbon::class, $matter->updated_at);

        $this->assertInstanceOf(Matter::class, $matter);
        $this->seeInDatabase('matters', [
            'name' => $matter->name,
            'workload' => $matter->workload,
        ]);
    }

    public function test_create_matter_with_value()
    {
        $matter = factory(Matter::class)->create([
            'name' => 'Matéria x',
            'workload' => 60,
        ]);

        $this->assertInstanceOf(Matter::class, $matter);
        $this->seeInDatabase('matters', [
            'name' => 'Matéria x',
            'workload' => 60,
        ]);
    }

    public function test_create_matter_with_classroom()
    {
        $matter = factory(Matter::class)->create();
        $classroom = factory(Classroom::class)->create();

        $matter->classrooms()->attach($classroom);

        $this->assertInstanceOf(Matter::class, $matter);
        $this->assertInstanceOf(Classroom::class, $matter->classrooms->first());
    }

    public function test_create_matter_with_lessons()
    {
        $matter = factory(Matter::class)->create();
        $lesson = factory(Lesson::class)->create();

        $matter->lessons()->attach($lesson);

        $this->assertInstanceOf(Matter::class, $matter);
        $this->assertInstanceOf(Lesson::class, $matter->lessons->first());
    }
}
