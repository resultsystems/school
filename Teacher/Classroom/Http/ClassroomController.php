<?php

namespace Teacher\Classroom\Http;

use Domain\Http\Controllers\AbstractController;
use Domain\Http\Controllers\Traits\AllTrait;
use Teacher\Classroom\ClassroomRepository as Repository;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class ClassroomController extends AbstractController
{
    use AllTrait;

    protected $with = ['schedule', 'matters', 'students', 'teacher'];

    public function repo()
    {
        return Repository::class;
    }

    /**
     * Get all :item.
     *
     * @return  \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->repo->all($this->columns, $this->with, $this->load);
    }
}
