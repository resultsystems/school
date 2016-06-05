<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:25
 */

namespace Domain\User\Http;

use Domain\Http\Controllers\AbstractController;
use Domain\Http\Controllers\Traits\DeleteTrait;
use Domain\Http\Controllers\Traits\ForceDeleteTrait;
use Domain\Http\Controllers\Traits\GetAllTrait;
use Domain\Http\Controllers\Traits\RestoreTrait;
use Domain\Http\Controllers\Traits\UpdateTrait;
use Domain\User\Http\Requests\DeleteRequest;
use Domain\User\Http\Requests\RestoreRequest;
use Domain\User\Http\Requests\UpdateRequest;
use Domain\User\UserRepository as Repository;

/**
 * User Controller.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
class UserController extends AbstractController
{
    use GetAllTrait;
    use DeleteTrait;
    use ForceDeleteTrait;
    use RestoreTrait;
    use UpdateTrait;

    protected $with = ['owner'];

    public function repo()
    {
        return Repository::class;
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
