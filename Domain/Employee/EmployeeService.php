<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:40:51
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:39
 */

namespace Domain\Employee;

use Domain\Employee\EmployeeRepository;
use Domain\Services\PersonAbstractService;
use Domain\User\UserRepository;

class EmployeeService extends PersonAbstractService
{
    /**
     * @var EmployeeRepository
     */
    protected $repo;

    /**
     * @var  UserRepository
     */
    protected $user;

    /**
     * Construct.
     *
     * @param EmployeeRepository $repo
     * @param UserRepository    $user
     */
    public function __construct(EmployeeRepository $repo, UserRepository $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }
}
