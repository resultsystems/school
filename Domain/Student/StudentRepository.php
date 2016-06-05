<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:14
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:12
 */

namespace Domain\Student;

use Domain\Classroom\Classroom;
use Domain\Matter\Matter;
use Domain\Repositories\BaseRepository;

class StudentRepository extends BaseRepository
{
    public function model()
    {
        return Student::class;
    }

    /**
     * Get student when day of payment has
     * between :start and :end.
     *
     * @param  int $start
     * @param  int $end
     */
    public function betweenDays($start, $end)
    {
        $model = $this->model;

        return $model->whereBetween('day_of_payment', [$start, $end])
            ->get();
    }

    public function getBillets($student_id)
    {
        $model = $this->model;

        return $model->with('billets')
            ->find($student_id);
    }

    /**
     * Get classrooms by student id.
     *
     * @param  int $student_id
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getWithClassrooms($student_id)
    {
        $model = $this->model;

        $student = $model->with('classrooms')->find($student_id);

        return $student->classrooms;
    }

    /**
     * Get student with classrooms and matters by student id.
     *
     * @param  int $student_id
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getWithClassroomsAndMatters($student_id)
    {
        $model = $this->model;

        $student = $model->with(['classrooms.matters', 'matterCompleteds'])->find($student_id);

        return $student;
    }
}
