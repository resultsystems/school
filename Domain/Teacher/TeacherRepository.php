<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:17
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:18
 */

namespace Domain\Teacher;

use Domain\Repositories\BaseRepository;

class TeacherRepository extends BaseRepository
{
    public function model()
    {
        return Teacher::class;
    }

    /**
     * Associate matters.
     * @param  Teacher $teacher
     * @param  array     $matters
     */
    public function associateMatters(Teacher $teacher, array $matters)
    {
        $ids = [];
        foreach ($matters['matters'] as $matter) {
            $ids[] = $matter['id'];
        }
        $teacher->matters()->sync($ids);
    }
}
