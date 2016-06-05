<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:03
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:57
 */

namespace Domain\Matter;

use Domain\Repositories\BaseRepository;

class MatterRepository extends BaseRepository
{
    public function model()
    {
        return Matter::class;
    }

    /**
     * Associate lessons.
     * @param  Matter $matter
     * @param  array     $lessons
     */
    public function associateLessons(Matter $matter, array $lessons)
    {
        $ids = [];
        foreach ($lessons['lessons'] as $lesson) {
            $ids[] = $lesson['id'];
        }
        $matter->lessons()->sync($ids);
    }
}
