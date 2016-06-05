<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-28 08:56:53
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:28
 */

namespace Domain\Classroom;

class MatterCompletedService
{
    /**
     * Attach Matters completeds for all students.
     *
     * @param  array  $data
     * @return bool
     */
    public function attach(array $data = [])
    {
        $sync = [];
        foreach ($data as $value) {
            $sync[] = $value['id'];
        }

        $classroomsMatter = ClassroomMatter::find($sync);
        $classroomsMatter->each(function ($c) use ($sync) {
            $c->classroom->students->each(function ($student) use ($sync) {
                $student->matterCompleteds()->detach($sync);
                $student->matterCompleteds()->attach($sync);
            });
        });

        return true;
    }
}
