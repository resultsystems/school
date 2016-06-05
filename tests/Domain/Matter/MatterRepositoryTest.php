<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:38
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:00
 */

namespace Domain\Matter;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Domain\Lesson\Lesson;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class MatterRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_store()
    {
        $repo = App::make(MatterRepository::class);

        $store = $repo->store(['name' => 'oi']);

        $this->assertInstanceOf(Matter::class, $store);
        $this->assertInstanceOf(Carbon::class, $store->created_at);
    }

    public function test_update()
    {
        $repo = App::make(MatterRepository::class);

        $matter = factory(Matter::class)->create();

        $update = $repo->update(['name' => 'oi 2'], $matter->id);

        $this->assertInstanceOf(Matter::class, $update);
        $this->assertEquals($matter->id, $update->id);
    }

    public function test_get()
    {
        $repo = App::make(MatterRepository::class);

        $matter = factory(Matter::class)->create();

        $get = $repo->get($matter->id);

        $this->assertInstanceOf(Matter::class, $get);
        $this->assertEquals($get->id, $matter->id);
    }

    public function test_all()
    {
        $repo = App::make(MatterRepository::class);

        factory(Matter::class)->create();

        $all = $repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $all);
        $this->assertInstanceOf(Matter::class, $all->first());
    }

    public function test_delete()
    {
        $repo = App::make(MatterRepository::class);

        $matter = factory(Matter::class)->create();

        $delete = $repo->delete($matter->id);

        $this->assertEquals(1, $delete);

        $this->setExpectedException(RepositoryException::class);
        $repo->withoutTrashed()->get($matter->id);
    }

    public function test_force_delete()
    {
        $repo = App::make(MatterRepository::class);

        $matter = factory(Matter::class)->create();

        $repo->forceDelete($matter->id);

        $this->setExpectedException(RepositoryException::class);
        $trashed = $repo->onlyTrashed()->get($matter->id);
    }

    /**
     * Test associate lessons.
     */
    public function test_associate_lessons()
    {
        $matter = factory(Matter::class)->create();
        $lessons = factory(Lesson::class, 30)->create();
        $data = [
            'id' => $matter->id,
            'lessons' => [],
        ];
        $random = $lessons->random(3)->each(function ($matter) use (&$data) {
            $data['lessons'][] = ['id' => $matter->id];
        });

        $repo = App::make(MatterRepository::class);
        $repo->associateLessons($matter, $data);

        foreach ($data['lessons'] as $key => $value) {
            $this->seeInDatabase('matter_lesson', [
                'matter_id' => $matter->id,
                'lesson_id' => $data['lessons'][$key]['id'],
            ]);
        }
    }
}
