<?php

namespace Teacher\Student\Http;

use Domain\Http\Controllers\AbstractController;
use Teacher\Student\StudentRepository as Repository;

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
}
