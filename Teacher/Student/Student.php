<?php

namespace Teacher\Student;

use Teacher\User\User;

class Student extends \Domain\Student\Student
{
    public function user()
    {
        return $this->morphOne(User::class, 'owner');
    }
}
