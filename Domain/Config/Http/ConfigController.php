<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:40:49
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:35
 */

namespace Domain\Config\Http;

use Domain\Config\ConfigRepository as Repository;
use Domain\Config\Http\Requests\Config\DeleteRequest;
use Domain\Config\Http\Requests\Config\StoreRequest;
use Domain\Config\Http\Requests\Config\UpdateRequest;
use Domain\Http\Controllers\AbstractController;
use Domain\Http\Controllers\Traits\CrudTrait;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class ConfigController extends AbstractController
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
}
