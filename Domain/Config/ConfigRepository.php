<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:40:48
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:34
 */

namespace Domain\Config;

use App\Exceptions\RepositoryException;
use Domain\Repositories\BaseRepository;

class ConfigRepository extends BaseRepository
{
    public function model()
    {
        return Config::class;
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

        $item = $item->where('field', $id)
            ->get($columns);

        if (!empty($load) and !is_null($item)) {
            $item->load($load);
        }

        if ($item->count() > 0) {
            return $item->first();
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
        $items = $items
            ->whereIn('field', $ids)
            ->get($columns);

        if (!empty($load) and $items->count() > 0) {
            $items->load($load);

            return $items;
        }

        if ($items->count() > 0) {
            return $items;
        }

        throw new RepositoryException('Items not found');
    }

    /**
     * Destroy item of model by id :id.
     *
     * @param  int    $id
     * @return int
     */
    public function delete($id)
    {
        return $this->model
            ->where('field', $id)
            ->delete();
    }

    /**
     * Force destroy item of model by id :id.
     *
     * @param  int    $id
     * @return bool|null
     */
    public function forceDelete($id, $field = 'field')
    {
        return $this->model->where($field, $id)->forceDelete();
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

        $model = $this->model
            ->where('field', $id)
            ->first();

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
}
