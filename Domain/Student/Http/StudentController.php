<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:13
 */

namespace Domain\Student\Http;

use Domain\Http\Controllers\AbstractController;
use Domain\Student\Http\Requests\StoreRequest;
use Domain\Student\Http\Requests\SyncMattersCompletedsRequest as SyncRequest;
use Domain\Student\Http\Requests\UpdateRequest;
use Domain\Student\StudentMatterCompletedService as Service;
use Domain\Student\StudentRepository as Repository;
use Domain\Student\StudentService;
use Illuminate\Http\Request;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class StudentController extends AbstractController
{
    protected $with = ['user'];

    public function repo()
    {
        return Repository::class;
    }

    /**
     * Get all students.
     *
     * @return  \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->repo->all($this->columns, $this->with);
    }

    /**
     * Get student by id :id.
     * @param  int    $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->repo->get($id, $this->columns, $this->with);
    }

    /**
     * Store new student.
     *
     * @return mixed
     */
    public function store(StoreRequest $request, StudentService $service)
    {
        $save = $service->store($request->all());

        if ($save) {
            return $save;
        }

        return response()->json('Internal error', 500);
    }

    /**
     * Update student.
     *
     * @return mixed
     */
    public function update(UpdateRequest $request, StudentService $service, $id)
    {
        $save = $service->update($request->all(), $id);

        if ($save) {
            return $save;
        }

        return response()->json('Internal error', 500);
    }

    /**
     * Delete student.
     *
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->repo->delete($id);
    }

    /**
     * Restore student.
     *
     * @return mixed
     */
    public function restore($id)
    {
        return $this->repo->restore($id);
    }

    /**
     * Force Delete student.
     *
     * @return mixed
     */
    public function forceDelete($id)
    {
        return $this->repo->forceDelete($id);
    }

    /**
     * Get billets by student id.
     *
     * @param  int $student_id
     * @return Domain\Student\Student
     */
    public function getBillets($student_id)
    {
        return $this->repo->getBillets($student_id);
    }

    /**
     * Get student with classrooms by student id.
     *
     * @param  int $student_id
     * @return Domain\Classrooms\Classrooms
     */
    public function getWithClassrooms($student_id)
    {
        return $this->repo->getWithClassrooms($student_id);
    }

    /**
     * Get student with classrooms and matters by student id.
     *
     * @param  int $student_id
     * @return Domain\Classrooms\Classrooms
     */
    public function getWithClassroomsAndMatters($student_id)
    {
        return $this->repo->getWithClassroomsAndMatters($student_id);
    }

    /**
     * Sync matters completeds.
     *
     * @param  SyncRequest $request
     * @param  Service     $service
     * @param  int      $student_id
     * @return Illuminate\Http\Response
     */
    public function syncMattersCompleteds(SyncRequest $request, Service $service, $student_id)
    {
        $response = $service->sync($student_id, $request->all());

        if ($response) {
            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false], 422);
    }
}
