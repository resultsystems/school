<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-07 18:45:37
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:38
 */

namespace Domain\Employee;

use Domain\Repositories\BaseRepository;

class EmployeeRepository extends BaseRepository
{
    public function model()
    {
        return Employee::class;
    }
}
