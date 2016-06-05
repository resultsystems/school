<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:03
 */

namespace Domain\Repositories\Traits;

use App\Exceptions\RepositoryException;

/**
 * Gets trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait GetsTrait
{
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
     * Get items of model by ids :ids.
     *
     * @param  array    $ids
     * @param  array  $columns
     * @param  array  $with
     * @param  array  $load
     * @return Illuminate\Database\Eloquent\{Collection, Model}
     *
     * @throw Exception
     */
    public function getByIds(array $ids, array $columns = ['*'], array $with = [], array $load = [])
    {
        $items = $this->model;
        if (!empty($with)) {
            $items = $items->with($with);
        }
        $items = $items->find($id, $columns);

        if (!empty($load) and !is_null($items)) {
            $items->load($load);

            return $items;
        }

        if ($items) {
            return $items;
        }

        throw new RepositoryException('Items not found');
    }
}
