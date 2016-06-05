<?php

namespace Teacher\User;

class User extends \Domain\User\User
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];
}
