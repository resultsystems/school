<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:39
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:02
 */

namespace Domain\Repositories;

use App;
use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery as m;

class BaseModel extends Model
{
}

class Repo extends BaseRepository
{
    public function model()
    {
        return BaseModel::class;
    }
}

class BaseRepositoryTest extends \TestCase
{
    public function test_repo_instance_base_repository()
    {
        $repository = App::make(Repo::class);

        $this->assertInstanceOf(BaseRepository::class, $repository);
    }

    public function test_make_model()
    {
        $repository = App::make(Repo::class);

        $this->assertInstanceOf(BaseRepository::class, $repository);
        $this->assertInstanceOf(Model::class, $repository->makeModel());
        $this->assertTrue($repository->getModel() instanceof Model);
    }

    public function test_store()
    {
        $baseModel = m::mock(BaseModel::class);
        $baseModel->shouldReceive('fill')
            ->once()
            ->shouldReceive('save')
            ->once()
            ->andReturn($baseModel);

        App::instance(BaseModel::class, $baseModel);
        $repository = App::make(Repo::class);

        $data = [
            'field' => 'teste',
        ];

        $model = $repository->store($data);

        $this->assertInstanceOf(Model::class, $model);
    }

    public function test_store_with_empty_fillable()
    {
        $repository = App::make(Repo::class);

        $data = ['field' => 'teste'];

        $error = m::mock(RepositoryException::class);

        $this->setExpectedException(RepositoryException::class);

        $repository->store($data);
    }

    public function test_store_with_empty_data()
    {
        $repository = App::make(Repo::class);

        $data = [];

        $error = m::mock(RepositoryException::class);

        $this->setExpectedException(RepositoryException::class);

        $repository->store($data);
    }

    public function test_store_failed()
    {
        $repository = App::make(Repo::class);

        $data = [
            'field' => 'oi',
        ];

        $error = m::mock(RepositoryException::class);

        $this->setExpectedException(RepositoryException::class);

        $repository->store($data);
    }

    public function test_update()
    {
        $baseModel = m::mock(BaseModel::class);
        $baseModel->shouldReceive('fill')
            ->once()
            ->shouldReceive('find')
            ->once()
            ->andReturn($baseModel)
            ->shouldReceive('save')
            ->once()
            ->andReturn($baseModel);

        App::instance(BaseModel::class, $baseModel);
        $repository = App::make(Repo::class);

        $data = [
            'field' => 'teste',
        ];

        $model = $repository->update($data, 1);

        $this->assertInstanceOf(Model::class, $model);
    }

    public function test_update_with_empty_fillable()
    {
        $baseModel = m::mock(BaseModel::class);
        $baseModel->shouldReceive('fill')
            ->once()
            ->shouldReceive('find')
            ->once()
            ->andReturn($baseModel);

        App::instance(BaseModel::class, $baseModel);

        $repository = App::make(Repo::class);

        $data = ['field' => 'teste'];

        $error = m::mock(RepositoryException::class);

        $this->setExpectedException(RepositoryException::class);
        $repository->update($data, 1);
    }

    public function test_update_with_empty_data()
    {
        $repository = App::make(Repo::class);

        $data = [];

        $error = m::mock(RepositoryException::class);

        $this->setExpectedException(RepositoryException::class);

        $repository->update($data, 1);
    }

    public function test_update_failed()
    {
        $baseModel = m::mock(BaseModel::class);
        $baseModel->shouldReceive('fill')
            ->once()
            ->shouldReceive('find')
            ->once()
            ->andReturn($baseModel);

        App::instance(BaseModel::class, $baseModel);
        $repository = App::make(Repo::class);

        $data = [
            'field' => 'oi',
        ];

        $error = m::mock(RepositoryException::class);

        $this->setExpectedException(RepositoryException::class);

        $repository->update($data, 1);
    }

    public function test_get()
    {
        $baseModel = m::mock(BaseModel::class);
        $baseModel
            ->shouldReceive('find')
            ->once()
            ->andReturn($baseModel);

        App::instance(BaseModel::class, $baseModel);
        $repository = App::make(Repo::class);
        $model = $repository->get(1);

        $this->assertInstanceOf(Model::class, $model);
    }

    public function test_all()
    {
        $baseModel = m::mock(BaseModel::class);
        $paginator = m::mock(LengthAwarePaginator::class);
        $baseModel
            ->shouldReceive('paginate')
            ->once()
            ->andReturn($paginator);

        App::instance(BaseModel::class, $baseModel);
        $repository = App::make(Repo::class);
        $model = $repository->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $model);
    }

    public function test_delete()
    {
        $baseModel = m::mock(BaseModel::class);
        $baseModel
            ->shouldReceive('destroy')
            ->once()
            ->andReturn(1);

        App::instance(BaseModel::class, $baseModel);
        $repository = App::make(Repo::class);
        $delete = $repository->delete(1);

        $this->assertEquals(1, $delete);
    }

    public function test_restore()
    {
        $baseModel = m::mock(BaseModel::class);
        $baseModel
            ->shouldReceive('onlyTrashed->where->restore')
            ->once()
            ->andReturn(1);

        App::instance(BaseModel::class, $baseModel);
        $repository = App::make(Repo::class);
        $restore = $repository->restore(1);

        $this->assertEquals(1, $restore);
    }
}
