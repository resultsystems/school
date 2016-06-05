<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:34:44
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:49
 */

namespace Domain\Http\Controllers\Traits;

/**
 * Update Trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait UpdateTrait
{
    /**
     * Update :item.
     *
     * @return mixed
     */
    public function update($id)
    {
        $request = $this->app->make($this->updateRequest());

        $save = $this->repo->update($request->all(), $id);
        if ($save) {
            return $save;
        }

        return response()->json(['error' => 'Internal error'], 500);
    }

    abstract public function updateRequest();
}
