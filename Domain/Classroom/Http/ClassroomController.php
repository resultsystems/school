<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-10 04:21:08
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:29
 */

namespace Domain\Classroom\Http;

use Domain\Classroom\ClassroomRepository as Repository;
use Domain\Classroom\Http\Requests\AssociateMatterRequest;
use Domain\Classroom\Http\Requests\AssociateStudentRequest;
use Domain\Classroom\Http\Requests\DeleteRequest;
use Domain\Classroom\Http\Requests\MattersCompletedsRequest as SyncRequest;
use Domain\Classroom\Http\Requests\StoreRequest;
use Domain\Classroom\Http\Requests\UpdateRequest;
use Domain\Classroom\MatterCompletedService as Service;
use Domain\Http\Controllers\AbstractController;
use Domain\Http\Controllers\Traits\CrudTrait;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class ClassroomController extends AbstractController
{
    use CrudTrait;

    protected $with = ['teacher', 'schedule', 'matters', 'students'];

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
     * Associate matters.
     *
     * @param  AssociateMatterRequest $request
     * @param  int           $classroom_id
     * @return array
     */
    public function associateMatters(AssociateMatterRequest $request, $classroom_id)
    {
        $classroom = $this->repo->get($classroom_id);
        $this->repo->associateMatters($classroom, $request->only('matters'));

        return ['status' => true];
    }

    /**
     * Associate students.
     *
     * @param  AssociateStudentRequest $request
     * @param  int           $classroom_id
     * @return array
     */
    public function associateStudents(AssociateStudentRequest $request, $classroom_id)
    {
        $classroom = $this->repo->get($classroom_id);
        $this->repo->associateStudents($classroom, $request->only('students'));

        return ['status' => true];
    }

    /**
     * Get students of classroom.
     *
     * @param  int $classroom_id
     * @return array
     */
    public function getStudents($classroom_id)
    {
        $classroom = $this->repo->get($classroom_id);

        $students = $classroom->students;
        $students->load('user');

        return [
            'classroom' => $classroom,
            'students' => $students,
        ];
    }

    public function attachMatterCompleteds(SyncRequest $request, Service $service)
    {
        $response = $service->attach($request->all());

        if ($response) {
            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false], 422);
    }
}
