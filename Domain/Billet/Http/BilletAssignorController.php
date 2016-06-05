<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:36:26
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:21
 */

namespace Domain\Billet\Http;

use Domain\Billet\BilletAssignorRepository as Repository;
use Domain\Billet\BilletAssignorService as Service;
use Domain\Billet\Http\Requests\BilletAssignor\StoreRequest;
use Domain\Http\Controllers\AbstractController;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class BilletAssignorController extends AbstractController
{
    public function repo()
    {
        return Repository::class;
    }

    public function show()
    {
        return $this->repo->first();
    }

    /**
     * Store/Update Assignor.
     *
     * @param  StoreRequest $request
     * @param  Service      $service
     * @return Domain\Billet\BilletAssignor|Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Service $service)
    {
        $assignor = $service->updateOrCreate($request);

        if (!$assignor) {
            return response()->json(['status' => false], 422);
        }

        return response()->json($assignor);
    }
}
