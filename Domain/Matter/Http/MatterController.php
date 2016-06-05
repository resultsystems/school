<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:04
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:58
 */

namespace Domain\Matter\Http;

use Domain\Http\Controllers\AbstractController;
use Domain\Http\Controllers\Traits\CrudTrait;
use Domain\Matter\Http\Requests\AssociateLessonRequest;
use Domain\Matter\Http\Requests\DeleteRequest;
use Domain\Matter\Http\Requests\StoreRequest;
use Domain\Matter\Http\Requests\UpdateRequest;
use Domain\Matter\MatterRepository as Repository;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class MatterController extends AbstractController
{
    use CrudTrait;
    protected $with = ['lessons'];

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

    /**
     * Associate lessons.
     *
     * @param  AssociateLessonRequest $request
     * @param  int           $classroom_id
     * @return array
     */
    public function associateLessons(AssociateLessonRequest $request, $classroom_id)
    {
        $classroom = $this->repo->get($classroom_id);
        $this->repo->associateLessons($classroom, $request->only('lessons'));

        return ['status' => 'ok'];
    }
}
