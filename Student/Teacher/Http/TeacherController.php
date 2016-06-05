<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:37
 */

namespace Student\Teacher\Http;

use Domain\Http\Controllers\AbstractController;
use Student\Teacher\TeacherRepository as Repository;

/**
 * It's don't work, because Requests and Repository don't exists.
 */
class TeacherController extends AbstractController
{
    protected $with = ['user'];

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
}
