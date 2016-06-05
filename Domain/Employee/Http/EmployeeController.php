<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:40
 */

namespace Domain\Employee\Http;

use Domain\Employee\EmployeeRepository as Repository;
use Domain\Employee\EmployeeService;
use Domain\Employee\Http\Requests\StoreRequest;
use Domain\Employee\Http\Requests\UpdateRequest;
use Domain\Http\Controllers\AbstractController;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class EmployeeController extends AbstractController
{
    protected $with = ['user'];

    public function repo()
    {
        return Repository::class;
    }

    /**
     * Get all employees.
     *
     * @return  \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->repo->all($this->columns, $this->with);
    }

    /**
     * Get employee by id :id.
     * @param  int    $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->repo->get($id, $this->columns, $this->with);
    }

    /**
     * Store new employee.
     *
     * @return mixed
     */
    public function store(StoreRequest $request, EmployeeService $service)
    {
        $save = $service->store($request->all());

        if ($save) {
            return $save;
        }

        return response()->json('Internal error', 500);
    }

    /**
     * Update employee.
     *
     * @return mixed
     */
    public function update(UpdateRequest $request, EmployeeService $service, $id)
    {
        $save = $service->update($request->all(), $id);
        if ($save) {
            return $save;
        }

        return response()->json('Internal error', 500);
    }

    /**
     * Delete employee.
     *
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->repo->delete($id);
    }

    /**
     * Restore employee.
     *
     * @return mixed
     */
    public function restore($id)
    {
        return $this->repo->restore($id);
    }

    /**
     * Force Delete employee.
     *
     * @return mixed
     */
    public function forceDelete($id)
    {
        return $this->repo->forceDelete($id);
    }
}
