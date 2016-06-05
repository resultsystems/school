<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:36
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:57
 */

namespace Domain\Lesson;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class LessonRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_store()
    {
        $repo = App::make(LessonRepository::class);

        $store = $repo->store(['name' => 'oi']);

        $this->assertInstanceOf(Lesson::class, $store);
        $this->assertInstanceOf(Carbon::class, $store->created_at);
    }

    public function test_update()
    {
        $repo = App::make(LessonRepository::class);

        $lesson = factory(Lesson::class)->create();

        $update = $repo->update(['name' => 'oi 2'], $lesson->id);

        $this->assertInstanceOf(Lesson::class, $update);
        $this->assertEquals($lesson->id, $update->id);
    }

    public function test_get()
    {
        $repo = App::make(LessonRepository::class);

        $lesson = factory(Lesson::class)->create();

        $get = $repo->get($lesson->id);

        $this->assertInstanceOf(Lesson::class, $get);
        $this->assertEquals($get->id, $lesson->id);
    }

    public function test_all()
    {
        $repo = App::make(LessonRepository::class);

        factory(Lesson::class)->create();

        $all = $repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $all);
        $this->assertInstanceOf(Lesson::class, $all->first());
    }

    public function test_delete()
    {
        $repo = App::make(LessonRepository::class);

        $lesson = factory(Lesson::class)->create();

        $delete = $repo->delete($lesson->id);

        $this->assertEquals(1, $delete);

        $this->setExpectedException(RepositoryException::class);
        $repo->withoutTrashed()->get($lesson->id);
    }

    public function test_force_delete()
    {
        $repo = App::make(LessonRepository::class);

        $lesson = factory(Lesson::class)->create();

        $repo->forceDelete($lesson->id);

        $this->setExpectedException(RepositoryException::class);
        $trashed = $repo->onlyTrashed()->get($lesson->id);
    }
}
