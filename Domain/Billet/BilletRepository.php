<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:36:39
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:20
 */

namespace Domain\Billet;

use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Domain\Repositories\BaseRepository;

class BilletRepository extends BaseRepository
{
    public function model()
    {
        return Billet::class;
    }

    public function defaulters()
    {
        $model = $this->model;

        return $model->with('student')
            ->where('due_date', '<', date('Y-m-d'))
            ->whereNull('discharge_date')
            ->get();
    }

    /**
     * Pay billet by billet id.
     *
     * @param  int $billet_id
     * @param  Carbon $date
     *
     * @return Billet
     */
    public function pay($billet_id, Carbon $date)
    {
        $model = $this->model;
        $billet = $model->find($billet_id);

        if (!is_null($billet->discharge_date)) {
            throw new RepositoryException("You can't pay this billet");
        }

        $billet->discharge_date = $date->toDateString();
        $billet->save();

        return $billet;
    }
}
