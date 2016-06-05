<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:39:27
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:30
 */

namespace Student\Billet\Http;

use Domain\Billet\GenerateBilletService;
use Domain\Http\Controllers\AbstractController;
use Domain\Http\Controllers\Traits\GetAllTrait;
use Domain\Http\Controllers\Traits\UpdateTrait;
use Student\Billet\BilletRepository as Repository;
use Student\Billet\Http\Requests\UpdateRequest;

/**
 * @author  Leandro Henrique <emtudo@gmail.com>
 */
class BilletController extends AbstractController
{
    use GetAllTrait, UpdateTrait;

    public function repo()
    {
        return Repository::class;
    }

    public function pdf(GenerateBilletService $service, $billet_id)
    {
        $billet = $this->repo->get($billet_id);

        return $service->pdf($billet);
    }

    public function updateRequest()
    {
        return UpdateRequest::class;
    }
}
