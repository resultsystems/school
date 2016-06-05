<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:04
 */

namespace Domain\Repositories\Traits;

use App\Exceptions\RepositoryException;
use Exception;
use Log;

/**
 * Store trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait StoreTrait
{
    /**
     * Store new item of model.
     *
     * @param  array  $data
     * @return Model
     *
     * @throw Exception
     */
    public function store(array $data)
    {
        if (empty($data)) {
            throw new RepositoryException('Empty data');
        }

        $model = $this->model;
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
            throw new RepositoryException('Error store');
        }
    }
}
