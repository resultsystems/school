<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 22:09:15
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:57
 */

namespace Domain\Matter;

use Domain\BaseModel;
use Domain\Classroom\Classroom;
use Domain\Lesson\Lesson;
use Domain\Teacher\Teacher;

class Matter extends BaseModel
{
    protected $fillable = ['name', 'workload'];

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class)->withTimestamps();
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'matter_lesson');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_matter');
    }
}
