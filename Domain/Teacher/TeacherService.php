<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:17
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:18
 */

namespace Domain\Teacher;

use Domain\Services\PersonAbstractService;
use Domain\Teacher\TeacherRepository;
use Domain\User\UserRepository;

class TeacherService extends PersonAbstractService
{
    /**
     * @var TeacherRepository
     */
    protected $repo;

    /**
     * @var  UserRepository
     */
    protected $user;

    /**
     * Construct.
     *
     * @param TeacherRepository $repo
     * @param UserRepository    $user
     */
    public function __construct(TeacherRepository $repo, UserRepository $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }
}
