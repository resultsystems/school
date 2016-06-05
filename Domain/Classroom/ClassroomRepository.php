<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:40:45
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:27
 */

namespace Domain\Classroom;

use Domain\Repositories\BaseRepository;

class ClassroomRepository extends BaseRepository
{
    public function model()
    {
        return Classroom::class;
    }

    /**
     * Associate matters.
     * @param  Classroom $classroom
     * @param  array     $matters
     */
    public function associateMatters(Classroom $classroom, array $matters)
    {
        $ids = [];
        foreach ($matters['matters'] as $matter) {
            $ids[] = $matter['id'];
        }
        $classroom->matters()->sync($ids);
    }

    /**
     * Associate students.
     * @param  Classroom $classroom
     * @param  array     $students
     */
    public function associateStudents(Classroom $classroom, array $students)
    {
        $ids = [];
        foreach ($students['students'] as $student) {
            $ids[] = $student['id'];
        }
        $classroom->students()->sync($ids);
    }
}
