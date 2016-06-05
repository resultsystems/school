<?php

namespace Teacher;

class Teacher extends \Domain\Teacher\Teacher
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
        'name', 'sex',
    ];
}
