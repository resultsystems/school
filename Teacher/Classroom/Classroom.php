<?php

namespace Teacher\Classroom;

use Teacher\Matter\Matter;
use Teacher\Schedule\Schedule;
use Teacher\Student\Student;
use Teacher\Teacher;

class Classroom extends \Domain\Classroom\Classroom
{
    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at',
        'id', 'schedule_id', 'teacher_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function matters()
    {
        return $this->belongsToMany(Matter::class);
    }
}
