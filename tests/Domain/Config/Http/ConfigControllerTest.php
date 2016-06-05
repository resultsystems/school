<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:01:31
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:59:02
 */

namespace Domain\Config\Http;

use App;
use App\Exceptions\RepositoryException;
use Domain\Config\Config;
use Domain\Config\ConfigRepository;
use Domain\Config\Http\Requests\Config\StoreRequest;
use Domain\Config\Http\Requests\Config\UpdateRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class ConfigControllerTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_index_returns_configs()
    {
        factory(Config::class)->create();

        $controller = App::make(ConfigController::class);
        $configs = $controller->index();

        $this->assertInstanceOf(LengthAwarePaginator::class, $configs);
        $this->assertInstanceOf(Config::class, $configs->first());
    }

    public function test_index_returns_configs_by_get()
    {
        Config::whereNotNull('field')->delete();
        $configs = factory(Config::class, 3)->create();

        $this->get('api/v1/config');
        $this->seeStatusCode(200);

        foreach ($configs as $config) {
            $this->seeJson(['field' => $config->field]);
        }
    }

    public function test_get()
    {
        $model = m::mock(Config::class);

        $repo = m::mock(ConfigRepository::class);
        $repo
            ->shouldReceive('get')
            ->once()
            ->andReturn($model);

        App::instance(ConfigRepository::class, $repo);

        $controller = App::make(ConfigController::class);
        $get = $controller->show(1);

        $this->assertInstanceOf(Config::class, $get);
    }

    public function test_get_real()
    {
        $config = factory(Config::class)->create();

        $controller = App::make(ConfigController::class);
        $get = $controller->show($config->field);

        $this->assertInstanceOf(Config::class, $get);
        $this->assertEquals($get->field, $config->field);
    }

    public function test_store()
    {
        $model = m::mock(Config::class);
        $request = m::mock(StoreRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(ConfigRepository::class);

        $repo
            ->shouldReceive('store')
            ->once()
            ->andReturn($model);

        App::instance(ConfigRepository::class, $repo);
        App::instance(StoreRequest::class, $request);

        $controller = App::make(ConfigController::class);

        $config = $controller->store();
        $this->assertInstanceOf(Config::class, $config);
    }

    public function test_store_failed()
    {
        $request = m::mock(StoreRequest::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $error = m::mock(RepositoryException::class);
        $this->setExpectedException(RepositoryException::class);

        App::instance(StoreRequest::class, $request);

        $controller = App::make(ConfigController::class);
        $controller->store();
    }

    public function test_update()
    {
        $model = m::mock(Config::class);
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $repo = m::mock(ConfigRepository::class);
        $repo
            ->shouldReceive('update')
            ->once()
            ->andReturn($model);

        App::instance(UpdateRequest::class, $request);
        App::instance(ConfigRepository::class, $repo);

        $controller = App::make(ConfigController::class);
        $update = $controller->update($request, 1);

        $this->assertTrue($update instanceof Config);
    }

    public function test_update_failed()
    {
        $request = m::mock(UpdateRequest::class);

        $request->shouldReceive('all')
            ->once()
            ->andReturn([]);

        $this->setExpectedException(RepositoryException::class);

        App::instance(UpdateRequest::class, $request);
        $controller = App::make(ConfigController::class);

        $controller->update($request, 1);
    }

    public function test_delete()
    {
        $repo = m::mock(ConfigRepository::class);
        $repo
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        App::instance(ConfigRepository::class, $repo);

        $controller = App::make(ConfigController::class);
        $delete = $controller->destroy(1);

        $this->assertTrue($delete);
    }

    public function test_delete_real()
    {
        $config = factory(Config::class)->create();

        $controller = App::make(ConfigController::class);
        $delete = $controller->destroy($config->field);

        $this->assertEquals(1, $delete);
    }

    public function test_delete_failed()
    {
        $controller = App::make(ConfigController::class);
        $delete = $controller->destroy(1);

        $this->assertEquals(0, $delete);
    }
}
