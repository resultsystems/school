<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:36:23
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:22
 */

namespace Domain\Billet\Http;

use Carbon\Carbon;
use Domain\Billet\BilletRepository as Repository;
use Domain\Billet\GenerateBilletService;
use Domain\Billet\Http\Requests\DeleteRequest;
use Domain\Billet\Http\Requests\RestoreRequest;
use Domain\Billet\Http\Requests\StoreRequest;
use Domain\Billet\Http\Requests\UpdateRequest;
use Domain\Http\Controllers\AbstractController;
use Domain\Http\Controllers\Traits\CrudTrait;
use Domain\Http\Controllers\Traits\ForceDeleteTrait;
use Domain\Http\Controllers\Traits\RestoreTrait;
use Request;

class BilletController extends AbstractController
{
    use CrudTrait;
    use RestoreTrait;
    use ForceDeleteTrait;

    protected $with = ['student'];

    public function repo()
    {
        return Repository::class;
    }

    public function storeRequest()
    {
        return StoreRequest::class;
    }

    public function updateRequest()
    {
        return UpdateRequest::class;
    }

    public function deleteRequest()
    {
        return DeleteRequest::class;
    }

    public function restoreRequest()
    {
        return RestoreRequest::class;
    }

    public function defaulters()
    {
        return $this->repo->defaulters();
    }

    public function pay($billet_id)
    {
        $date = Request::only('discharge_date');

        if (is_null($date)) {
            $date = Carbon::now();
        } else {
            $date = Carbon::parse($date['discharge_date']);
        }

        return $this->repo->pay($billet_id, $date);
    }

    public function pdf(GenerateBilletService $service, $billet_id)
    {
        $billet = $this->repo->get($billet_id);

        return $service->pdf($billet);
    }
}
