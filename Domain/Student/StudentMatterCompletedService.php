<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-28 06:55:48
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:11
 */

namespace Domain\Student;

class StudentMatterCompletedService
{
    /**
     * Sync Matters completeds.
     *
     * @param  int $student_id
     * @param  array  $data
     * @return bool
     */
    public function sync($student_id, array $data = [])
    {
        $student = Student::find($student_id);
        $sync = [];
        foreach ($data as $value) {
            $sync[] = $value['id'];
        }

        if (is_null($student)) {
            return false;
        }
        $student->matterCompleteds()->sync($sync);

        return true;
    }
}
