<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:40:45
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:27
 */

namespace Domain\Classroom;

use Domain\BaseModel;
use Domain\Matter\Matter;
use Domain\Student\Student;

class ClassroomMatter extends BaseModel
{
    protected $table = 'classroom_matter';

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function matter()
    {
        return $this->belongsTo(Matter::class);
    }

    public function studentCompletds()
    {
        return $this->belongsToMany(Student::class, 'classroom_matter_student_completed');
    }
}
