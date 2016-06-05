<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:19
 */

namespace Domain\Teacher\Http;

use Domain\Http\Controllers\AbstractController;
use Domain\Teacher\Http\Requests\AssociateMatterRequest;
use Domain\Teacher\Http\Requests\StoreRequest;
use Domain\Teacher\Http\Requests\UpdateRequest;
use Domain\Teacher\TeacherRepository as Repository;
use Domain\Teacher\TeacherService;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class TeacherController extends AbstractController
{
    protected $with = ['user', 'classrooms.schedule', 'matters'];

    public function repo()
    {
        return Repository::class;
    }

    /**
     * Get all teachers.
     *
     * @return  \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->repo->all($this->columns, $this->with);
    }

    /**
     * Get teacher by id :id.
     * @param  int    $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->repo->get($id, $this->columns, $this->with);
    }

    /**
     * Store new teacher.
     *
     * @return mixed
     */
    public function store(StoreRequest $request, TeacherService $service)
    {
        $save = $service->store($request->all());

        if ($save) {
            return $save;
        }

        return response()->json('Internal error', 500);
    }

    /**
     * Update teacher.
     *
     * @return mixed
     */
    public function update(UpdateRequest $request, TeacherService $service, $id)
    {
        $save = $service->update($request->all(), $id);
        if ($save) {
            return $save;
        }

        return response()->json('Internal error', 500);
    }

    /**
     * Delete teacher.
     *
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->repo->delete($id);
    }

    /**
     * Restore teacher.
     *
     * @return mixed
     */
    public function restore($id)
    {
        return $this->repo->restore($id);
    }

    /**
     * Force Delete teacher.
     *
     * @return mixed
     */
    public function forceDelete($id)
    {
        return $this->repo->forceDelete($id);
    }

    /**
     * Associate matters.
     *
     * @param  AssociateMatterRequest $request
     * @param  int           $teacher_id
     * @return array
     */
    public function associateMatters(AssociateMatterRequest $request, $teacher_id)
    {
        $teacher = $this->repo->get($teacher_id);
        $this->repo->associateMatters($teacher, $request->only('matters'));

        return ['status' => 'ok'];
    }
}
