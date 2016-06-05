<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:24
 */

namespace Domain\User;

use Domain\Repositories\BaseRepository;

/**
 * User Repository.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }
}
