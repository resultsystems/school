<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 22:00:54
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:26
 */

namespace Domain\Classroom;

use Domain\BaseModel;
use Domain\Matter\Matter;
use Domain\Schedule\Schedule;
use Domain\Student\Student;
use Domain\Teacher\Teacher;

class Classroom extends BaseModel
{
    protected $fillable = ['name', 'teacher_id', 'schedule_id'];

    public function matters()
    {
        return $this->belongsToMany(Matter::class)->withTimestamps()->withPivot('id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
