<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:35:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:44
 */

namespace Domain\Http\Controllers\Traits;

/**
 * All Trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait AllTrait
{
    /**
     * Get all :item.
     *
     * @return  \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        return $this->repo->all($this->columns, $this->with, $this->load);
    }
}
