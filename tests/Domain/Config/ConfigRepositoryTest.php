<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:32
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:52
 */

namespace Domain\Config;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class ConfigRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_store()
    {
        $repo = App::make(ConfigRepository::class);

        $store = $repo->store(['name' => 'oi']);

        $this->assertInstanceOf(Config::class, $store);
        $this->assertInstanceOf(Carbon::class, $store->created_at);
    }

    public function test_update()
    {
        $repo = App::make(ConfigRepository::class);

        $config = factory(Config::class)->create();

        $update = $repo->update(['name' => 'oi 2'], $config->field);

        $this->assertInstanceOf(Config::class, $update);
        $this->assertEquals($config->field, $update->field);
    }

    public function test_get()
    {
        $repo = App::make(ConfigRepository::class);

        $config = factory(Config::class)->create();

        $get = $repo->get($config->field);

        $this->assertInstanceOf(Config::class, $get);
        $this->assertEquals($get->field, $config->field);
    }

    public function test_all()
    {
        $repo = App::make(ConfigRepository::class);

        factory(Config::class)->create();

        $all = $repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $all);
        $this->assertInstanceOf(Config::class, $all->first());
    }

    public function test_delete()
    {
        $repo = App::make(ConfigRepository::class);

        $config = factory(Config::class)->create();

        $delete = $repo->delete($config->field);

        $this->assertEquals(1, $delete);

        $this->setExpectedException(RepositoryException::class);
        $repo->withoutTrashed()->get($config->field);
    }

    public function test_force_delete()
    {
        $repo = App::make(ConfigRepository::class);

        $config = factory(Config::class)->create();

        $repo->forceDelete($config->field);

        $this->setExpectedException(RepositoryException::class);
        $trashed = $repo->onlyTrashed()->get($config->field);
    }
}
