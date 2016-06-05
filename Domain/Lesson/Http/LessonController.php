<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:01
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:54
 */

namespace Domain\Lesson\Http;

use Domain\Http\Controllers\AbstractController;
use Domain\Http\Controllers\Traits\CrudTrait;
use Domain\Lesson\Http\Requests\DeleteRequest;
use Domain\Lesson\Http\Requests\StoreRequest;
use Domain\Lesson\Http\Requests\UpdateRequest;
use Domain\Lesson\LessonRepository as Repository;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class LessonController extends AbstractController
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
