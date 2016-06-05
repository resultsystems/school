<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:46
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:12
 */

namespace Domain\User;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_store()
    {
        $repo = App::make(UserRepository::class);

        $store = $repo->store(['name' => 'oi']);

        $this->assertInstanceOf(User::class, $store);
        $this->assertInstanceOf(Carbon::class, $store->created_at);
    }

    public function test_update()
    {
        $repo = App::make(UserRepository::class);

        $user = factory(User::class)->create();

        $update = $repo->update(['name' => 'oi 2'], $user->id);

        $this->assertInstanceOf(User::class, $update);
        $this->assertEquals($user->id, $update->id);
    }

    public function test_get()
    {
        $repo = App::make(UserRepository::class);

        $user = factory(User::class)->create();

        $get = $repo->get($user->id);

        $this->assertInstanceOf(User::class, $get);
        $this->assertEquals($get->id, $user->id);
    }

    public function test_all()
    {
        $repo = App::make(UserRepository::class);

        factory(User::class)->create();

        $all = $repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $all);
        $this->assertInstanceOf(User::class, $all->first());
    }

    public function test_delete()
    {
        $repo = App::make(UserRepository::class);

        $user = factory(User::class)->create();

        $delete = $repo->delete($user->id);

        $trashed = $repo->onlyTrashed()->get($user->id);

        $this->assertEquals(1, $delete);
        $this->assertInstanceOf(Carbon::class, $trashed->deleted_at);
        $this->assertInstanceOf(User::class, $trashed);

        $this->setExpectedException(RepositoryException::class);
        $repo->withoutTrashed()->get($user->id);
    }

    public function test_force_delete()
    {
        $repo = App::make(UserRepository::class);

        $user = factory(User::class)->create();

        $repo->forceDelete($user->id);

        $this->setExpectedException(RepositoryException::class);
        $trashed = $repo->onlyTrashed()->get($user->id);
    }

    public function test_restore()
    {
        $repo = App::make(UserRepository::class);

        $user = factory(User::class)->create();

        $repo->delete($user->id);

        $restore = $repo->restore($user->id);

        $get = $repo->get($user->id);

        $this->assertEquals(1, $restore);
        $this->assertNull($get->deleted_at);
        $this->assertInstanceOf(User::class, $get);
    }
}
