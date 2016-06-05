<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:02
 */

namespace Domain\Repositories\Traits;

use App\Exceptions\RepositoryException;
use Exception;

/**
 * Delete trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait DeleteTrait
{
    /**
     * Destroy item of model by id :id.
     *
     * @param  int    $id
     * @return int
     */
    public function delete($id)
    {
        try {
            return $this->model->destroy($id);
        } catch (Exception $e) {
        }

        throw new RepositoryException('Could not delete the record. You must delete all relationships before proceeding.');
    }

    /**
     * Force destroy item of model by id :id.
     *
     * @param  int    $id
     * @return bool|null
     */
    public function forceDelete($id, $field = 'id')
    {
        try {
            return $this->model->where($field, $id)->forceDelete();
        } catch (Exception $e) {
        }

        throw new RepositoryException('Could not delete the record. You must delete all relationships before proceeding.');
    }
}
