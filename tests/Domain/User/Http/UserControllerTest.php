<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 07:58:42
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:58:49
 */

namespace Domain\User\Http;

use App;
use App\Exceptions\RepositoryException;
use Domain\User\Http\Requests\UpdateRequest;
use Domain\User\User;
use Domain\User\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class UserControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_index_returns_all_users()
    {
        factory(User::class)->create();

        $controller = App::make(UserController::class);
        $users = $controller->index();

        $this->assertInstanceOf(LengthAwarePaginator::class, $users);
        $this->assertInstanceOf(User::class, $users->first());
    }

    public function test_index_returns_all_users_by_get()
    {
        User::where('id', '>=', 1)->delete();

        $users = factory(User::class, 3)->create();
        $user = $users->first();

        //$this->actingAs($user);
        $this->get('api/v1/user');

        $this->seeStatusCode(200);

        $this->seeJson(['per_page' => 50]);

        foreach ($users as $user) {
            $this->seeJson(['username' => $user->username]);
        }
    }

    public function test_show()
    {
        $model = m::mock(User::class);

        $repo = m::mock(UserRepository::class);
        $repo
            ->shouldReceive('get')
            ->once()
            ->andReturn($model);

        App::instance(UserRepository::class, $repo);

        $controller = App::make(UserController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(User::class, $get);
    }

    public function test_show_real()
    {
        $user = factory(User::class)->create();

        $controller = App::make(UserController::class);
        $get = $controller->show($user->id);

        $this->assertInstanceOf(User::class, $get);
        $this->assertEquals($get->id, $user->id);
    }

    public function test_show_by_get()
    {
        $user = factory(User::class)->create();
        //$this->actingAs($user);

        $this->get('api/v1/user/'.$user->id)
            ->seeStatusCode(200);

        $this->seeJson(['username' => $user->username]);
    }

    public function test_update()
    {
        $model = m::mock(User::class);
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(UserRepository::class);
        $repo
            ->shouldReceive('update')
            ->once()
            ->andReturn($model);

        App::instance(UpdateRequest::class, $request);
        App::instance(UserRepository::class, $repo);

        $controller = App::make(UserController::class);
        $update = $controller->update($request, 1);

        $this->assertTrue($update instanceof User);
    }

    public function test_update_by_put()
    {
        $user = factory(User::class)->create();
        //$this->actingAs($user);

        $data = ['username' => 'User y',
            'email' => 'email@email.com',
        ];

        $this->put('api/v1/user/'.$user->id, $data);
        $this->seeStatusCode(200);

        $this->seeJson(['username' => 'User y']);
        $this->assertInstanceOf(User::class, $this->response->original);
        $this->seeInDatabase('users', ['id' => $this->response->original->id]);
    }

    public function test_update_failed()
    {
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $this->setExpectedException(RepositoryException::class);

        App::instance(UpdateRequest::class, $request);
        $controller = App::make(UserController::class);

        $controller->update($request, 1);
    }

    public function test_delete()
    {
        $repo = m::mock(UserRepository::class);
        $repo
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        App::instance(UserRepository::class, $repo);

        $controller = App::make(UserController::class);
        $delete = $controller->destroy(1);

        $this->assertTrue($delete);
    }

    public function test_delete_real()
    {
        $user = factory(User::class)->create();

        $controller = App::make(UserController::class);
        $delete = $controller->destroy($user->id);

        $this->assertEquals(1, $delete);
    }

    public function test_delete_by_delete()
    {
        $user = factory(User::class)->create();
        //$this->actingAs($user);

        $this->delete('api/v1/user/'.$user->id);
        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->seeInDatabase('users', ['id' => $user->id]);
        $this->notSeeInDatabase('users', ['id' => $user->id, 'deleted_at' => null]);
    }

    public function test_delete_failed()
    {
        $controller = App::make(UserController::class);
        $delete = $controller->destroy(0);

        $this->assertEquals(0, $delete);
    }

    public function test_force_delete()
    {
        $repo = m::mock(UserRepository::class);
        $repo
            ->shouldReceive('forceDelete')
            ->once()
            ->andReturn(true);

        App::instance(UserRepository::class, $repo);

        $controller = App::make(UserController::class);
        $forceDelete = $controller->forceDelete(1);

        $this->assertEquals(1, $forceDelete);
    }

    public function test_force_delete_by_delete()
    {
        $user = factory(User::class)->create();
        //$this->actingAs($user);

        $this->delete('api/v1/trashed/user/'.$user->id);
        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->notSeeInDatabase('users', ['id' => $user->id]);
    }

    public function test_force_delete_real()
    {
        $user = factory(User::class)->create();

        $controller = App::make(UserController::class);
        $forceDelete = $controller->forceDelete($user->id);

        $repo = App::make(UserRepository::class);

        $this->setExpectedException(RepositoryException::class);

        $get = $repo->withTrashed()->get($user->id);
    }

    public function test_force_delete_failed()
    {
        $controller = App::make(UserController::class);
        $forceDelete = $controller->forceDelete(0);

        $this->assertEquals(0, $forceDelete);
    }

    public function test_restore()
    {
        $repo = m::mock(UserRepository::class);
        $repo
            ->shouldReceive('restore')
            ->once()
            ->andReturn(true);

        App::instance(UserRepository::class, $repo);

        $controller = App::make(UserController::class);
        $restore = $controller->restore(1);

        $this->assertTrue($restore);
    }

    public function test_restore_by_put()
    {
        $assign = factory(User::class)->create();
        //$this->actingAs($assign);

        $user = factory(User::class)->create();
        $user->delete();

        $this->put('api/v1/restore/user/'.$user->id);
        $this->seeStatusCode(200);

        $this->assertEquals(1, $this->response->original);
        $this->seeInDatabase('users', ['id' => $user->id]);
        $this->notSeeInDatabase('users', ['id' => $user->id, 'deleted_at' => $user->deleted_at]);
    }

    public function test_restore_real()
    {
        $user = factory(User::class)->create();
        $user->delete();

        $controller = App::make(UserController::class);
        $restore = $controller->restore($user->id);

        $this->assertEquals(1, $restore);

        $repo = App::make(UserRepository::class);

        $get = $repo->get($user->id);
        $this->assertInstanceOf(User::class, $get);
        $this->assertNull($get->deleted_at);
    }

    public function test_restore_failed()
    {
        $controller = App::make(UserController::class);
        $restore = $controller->restore(1);

        $this->assertEquals(0, $restore);
    }
}
