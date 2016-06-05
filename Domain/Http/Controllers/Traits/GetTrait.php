<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:35:56
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:47
 */

namespace Domain\Http\Controllers\Traits;

/**
 * Get Trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait GetTrait
{
    /**
     * Get :item by id :id.
     * @param  int    $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->repo->get($id, $this->columns, $this->with, $this->load);
    }
}
