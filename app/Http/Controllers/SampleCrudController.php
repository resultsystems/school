<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-04-24 19:33:47
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:22
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\CrudTrait;
use App\Http\Requests\Sample\DeleteRequest;
use App\Http\Requests\Sample\RestoreRequest;
use App\Http\Requests\Sample\StoreRequest;
use App\Http\Requests\Sample\UpdateRequest;
use App\Repositories\SampleRepository as Repository;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class SampleCrudController extends AbstractController
{
    use CrudTrait;

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
}
