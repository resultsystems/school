<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 22:02:58
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:58
 */

namespace Domain\Lesson;

use Carbon\Carbon;
use Domain\Matter\Matter;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LessonTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_create_lesson()
    {
        $lesson = factory(Lesson::class)->create();

        $this->assertInstanceOf(Lesson::class, $lesson);

        $this->assertInstanceOf(Carbon::class, $lesson->created_at);
        $this->assertInstanceOf(Carbon::class, $lesson->updated_at);

        $this->seeInDatabase('lessons', [
            'name' => $lesson->name,
            'description' => $lesson->description,
        ]);
    }

    public function test_create_lesson_with_value()
    {
        $lesson = factory(Lesson::class)->create([
            'name' => 'lição 1',
            'description' => 'descrição',
        ]);

        $this->assertInstanceOf(Lesson::class, $lesson);
        $this->seeInDatabase('lessons', [
            'name' => 'lição 1',
            'description' => 'descrição',
        ]);
    }

    public function test_create_lesson_with_matter()
    {
        $lesson = factory(Lesson::class)->create();
        $matter = factory(Matter::class)->create();

        $lesson->matters()->attach($matter);

        $this->assertInstanceOf(Lesson::class, $lesson);
        $this->assertInstanceOf(Matter::class, $lesson->matters->first());
    }
}
