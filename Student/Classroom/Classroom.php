<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:32
 */

namespace Student\Classroom;

use Student\Matter\Matter;
use Student\Schedule\Schedule;

class Classroom extends \Domain\Classroom\Classroom
{
    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at',
        'id', 'schedule_id', 'teacher_id',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function matters()
    {
        return $this->belongsToMany(Matter::class);
    }
}
