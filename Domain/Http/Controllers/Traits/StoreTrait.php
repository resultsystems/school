<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:58:03
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:48
 */

namespace Domain\Http\Controllers\Traits;

/**
 * Store Trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait StoreTrait
{
    /**
     * Store new :item.
     *
     * @return mixed
     */
    public function store()
    {
        $request = $this->app->make($this->storeRequest());

        $save = $this->repo->store($request->all());
        if ($save) {
            return $save;
        }

        return response()->json(['error' => 'Internal error'], 500);
    }

    abstract public function storeRequest();
}
