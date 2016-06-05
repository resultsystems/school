<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:04
 */

namespace Domain\Repositories\Traits;

/**
 * Restore trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait RestoreTrait
{
    /**
     * Restore item of model by id :id.
     *
     * @param  int    $id
     * @param  string    $field
     * @return bool
     */
    public function restore($id, $field = 'id')
    {
        return $this->model->onlyTrashed()->where($field, $id)->restore();
    }
}
