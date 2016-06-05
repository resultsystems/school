<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:05
 */

namespace Domain\Repositories\Traits;

use App\Exceptions\RepositoryException;
use Exception;
use Log;

/**
 * Update trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait UpdateTrait
{
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

        $model = $this->model->find($id);
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
