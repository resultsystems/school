<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:01
 */

namespace Domain\Repositories;

use App\Exceptions\RepositoryException;
use Auth;
use Exception;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Abstract Base Repository.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
abstract class BaseRepository
{
    use Traits\DeleteTrait;
    use Traits\GetsTrait;
    use Traits\RestoreTrait;
    use Traits\StoreTrait;
    use Traits\UpdateTrait;

    abstract public function model();

    protected $model;

    /**
     * Get model.
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * makeModel.
     *
     * @return Illuminate\Database\Eloquent\Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Add trashed.
     */
    public function withTrashed()
    {
        if (!in_array(SoftDeletes::class, class_uses($this->model))) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\SoftDeletes");
        }
        $this->model = $this->makeModel()->withTrashed();

        return $this;
    }

    /**
     * Without trashed.
     */
    public function withoutTrashed()
    {
        $this->model = $this->makeModel();

        return $this;
    }

    /**
     * Only trashed.
     */
    public function onlyTrashed()
    {
        if (!in_array(SoftDeletes::class, class_uses($this->model))) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\SoftDeletes");
        }
        $this->model = $this->makeModel()->onlyTrashed();

        return $this;
    }

    /**
     * me.
     */
    public function onlyMe($field = 'user_id', $owner_id = null)
    {
        if (is_null($owner_id)) {
            $owner_id = Auth::user()->id;
        }
        $this->model = $this->model->where($field, $owner_id);

        return $this;
    }

    /**
     * Count.
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Max.
     * @param  string $field
     * @return int
     */
    public function max($field = 'id')
    {
        return $this->model->max($field);
    }

    /**
     * Sum.
     * @param  string $field
     * @return int
     */
    public function sum($field = 'id')
    {
        return $this->model->sum($field);
    }

    /**
     * Min.
     * @param  string $field
     * @return int
     */
    public function min($field = 'id')
    {
        return $this->model->min($field);
    }
}
