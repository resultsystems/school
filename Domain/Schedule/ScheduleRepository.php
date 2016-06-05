<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:09
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:06
 */

namespace Domain\Schedule;

use Domain\Repositories\BaseRepository;

class ScheduleRepository extends BaseRepository
{
    public function model()
    {
        return Schedule::class;
    }
}
