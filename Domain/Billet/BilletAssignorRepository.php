<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:36:36
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:19
 */

namespace Domain\Billet;

use Domain\Repositories\BaseRepository;

class BilletAssignorRepository extends BaseRepository
{
    public function model()
    {
        return BilletAssignor::class;
    }

    /**
     * Get first item.
     *
     * @return BilletAssignor
     */
    public function first()
    {
        $model = $this->model;

        return $model->first();
    }
}
