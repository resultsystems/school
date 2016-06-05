<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:29
 */

namespace Student\Billet;

use App\Exceptions\RepositoryException;
use Auth;
use Exception;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Log;

class BilletRepository
{
    protected $model;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    public function model()
    {
        return Billet::class;
    }

    /**
     * Get all item of model.
     *
     * @param  array  $columns
     * @param  array  $with
     * @param  array  $orders
     * @param  int      $limit
     * @param  int      $page
     * @return Illuminate\Pagination\LengthAwarePaginator
     *
     * @throw Exception
     */
    public function all(array $columns = ['*'], array $with = [], $orders = [], $limit = 50, $page = 1)
    {
        $all = $this->model;

        $all = $all->where('student_id', Auth::user()->owner->id);
        if (!empty($with)) {
            $all = $all->with($with);
        }

        foreach ($orders as $order) {
            $order['order'] = isset($order['order']) ? $order['order'] : 'ASC';

            $all = $all->orderBy($order['column'], $order['order']);
        }

        $all = $all->paginate($limit, $columns, 'page', $page);

        return $all;
    }

    /**
     * Get item of model by id :id.
     *
     * @param  int    $id
     * @param  array  $columns
     * @param  array  $with
     * @param  array  $load
     * @return Domains\BaseModel
     *
     * @throw Exception
     */
    public function get($id, array $columns = ['*'], array $with = [], array $load = [])
    {
        $item = $this->model;

        $item = $item->where('student_id', Auth::user()->owner->id);

        if (!empty($with)) {
            $item = $item->with($with);
        }
        $item = $item->find($id, $columns);

        if (!empty($load) and !is_null($item)) {
            $item->load($load);
        }

        if ($item) {
            return $item;
        }

        throw new RepositoryException('Item not found');
    }

    /**
     * Update item of model.
     *
     * @param  array  $data
     * @param  int    $id
     * @return Domains\BaseModel
     *
     * @throw Exception
     */
    public function update(array $data, $id)
    {
        if (empty($data)) {
            throw new RepositoryException('Empty data');
        }

        $model = $this->model->where('student_id', Auth::user()->owner->id)->find($id);

        if (!$model) {
            throw new RepositoryException('Item not found');
        }

        try {
            $model->fill($data);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new RepositoryException('Empty fillable');
        }
        try {
            $model->save();

            return $model;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new RepositoryException('Error update');
        }
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
}
