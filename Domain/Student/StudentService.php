<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:13
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:12
 */

namespace Domain\Student;

use Domain\Services\PersonAbstractService;
use Domain\Student\StudentRepository;
use Domain\User\UserRepository;

class StudentService extends PersonAbstractService
{
    /**
     * @var StudentRepository
     */
    protected $repo;

    /**
     * @var  UserRepository
     */
    protected $user;

    /**
     * Construct.
     *
     * @param StudentRepository $repo
     * @param UserRepository    $user
     */
    public function __construct(StudentRepository $repo, UserRepository $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }
}
