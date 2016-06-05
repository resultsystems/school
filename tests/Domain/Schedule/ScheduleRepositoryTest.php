<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:40
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:03
 */

namespace Domain\Schedule;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class ScheduleRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_store()
    {
        $repo = App::make(ScheduleRepository::class);

        $store = $repo->store(['name' => 'oi']);

        $this->assertInstanceOf(Schedule::class, $store);
        $this->assertInstanceOf(Carbon::class, $store->created_at);
    }

    public function test_update()
    {
        $repo = App::make(ScheduleRepository::class);

        $schedule = factory(Schedule::class)->create();

        $update = $repo->update(['name' => 'oi 2'], $schedule->id);

        $this->assertInstanceOf(Schedule::class, $update);
        $this->assertEquals($schedule->id, $update->id);
    }

    public function test_get()
    {
        $repo = App::make(ScheduleRepository::class);

        $schedule = factory(Schedule::class)->create();

        $get = $repo->get($schedule->id);

        $this->assertInstanceOf(Schedule::class, $get);
        $this->assertEquals($get->id, $schedule->id);
    }

    public function test_all()
    {
        $repo = App::make(ScheduleRepository::class);

        factory(Schedule::class)->create();

        $all = $repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $all);
        $this->assertInstanceOf(Schedule::class, $all->first());
    }

    public function test_delete()
    {
        $repo = App::make(ScheduleRepository::class);

        $schedule = factory(Schedule::class)->create();

        $delete = $repo->delete($schedule->id);

        $this->assertEquals(1, $delete);

        $this->setExpectedException(RepositoryException::class);
        $repo->withoutTrashed()->get($schedule->id);
    }

    public function test_force_delete()
    {
        $repo = App::make(ScheduleRepository::class);

        $schedule = factory(Schedule::class)->create();

        $repo->forceDelete($schedule->id);

        $this->setExpectedException(RepositoryException::class);
        $trashed = $repo->onlyTrashed()->get($schedule->id);
    }
}
