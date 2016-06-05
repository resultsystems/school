<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:10
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:07
 */

namespace Domain\Schedule\Http;

use Domain\Http\Controllers\AbstractController;
use Domain\Http\Controllers\Traits\CrudTrait;
use Domain\Schedule\Http\Requests\DeleteRequest;
use Domain\Schedule\Http\Requests\StoreRequest;
use Domain\Schedule\Http\Requests\UpdateRequest;
use Domain\Schedule\ScheduleRepository as Repository;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class ScheduleController extends AbstractController
{
    use CrudTrait;

    protected $with = ['classrooms'];

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
}
