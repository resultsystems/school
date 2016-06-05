<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:34:08
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:45
 */

namespace Domain\Http\Controllers\Traits;

/**
 * Delete Trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait DeleteTrait
{
    /**
     * Delete :item.
     *
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->repo->delete($id);
    }
}
