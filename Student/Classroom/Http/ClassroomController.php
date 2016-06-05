<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:34
 */

namespace Student\Classroom\Http;

use Domain\Http\Controllers\AbstractController;
use Student\Classroom\ClassroomRepository as Repository;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class ClassroomController extends AbstractController
{
    protected $with = ['teacher', 'schedule', 'matters'];

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
