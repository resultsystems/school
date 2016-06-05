<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:09
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:05
 */

namespace Domain\Schedule;

use Domain\BaseModel;
use Domain\Classroom\Classroom;

class Schedule extends BaseModel
{
    protected $fillable = ['name', 'start', 'end'];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
