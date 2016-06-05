<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:34:08
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:48
 */

namespace Domain\Http\Controllers\Traits;

/**
 * Restore Trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait RestoreTrait
{
    /**
     * Restore :item.
     *
     * @return mixed
     */
    public function restore($id)
    {
        return $this->repo->restore($id);
    }
}
