<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:36
 */

namespace Student\Teacher;

use Auth;

class TeacherRepository extends \Domain\Teacher\TeacherRepository
{
    public function model()
    {
        return Teacher::class;
    }

    /**
     * Get all item of model.
     *
     * @param  array  $columns
     * @param  array  $with
     * @param  array  $orders
     * @param  int      $limit
     * @param  int      $page
     * @return Illuminate\Pagination\LengthAwarePaginator
     *
     * @throw Exception
     */
    public function all(array $columns = ['*'], array $with = [], $orders = [], $limit = 50, $page = 1)
    {
        $all = $this->model;

        $all = $all->whereHas('classrooms.students', function ($q) {
            $q->where('student_id', Auth::user()->owner_id);
        })
            ->with(['classrooms.students' => function ($q) {
                $q->where('student_id', Auth::user()->owner_id);
            }]);

        if (!empty($with)) {
            $all = $all->with($with);
        }

        foreach ($orders as $order) {
            $order['order'] = isset($order['order']) ? $order['order'] : 'ASC';

            $all = $all->orderBy($order['column'], $order['order']);
        }

        $all = $all->paginate($limit, $columns, 'page', $page);

        return $all;
    }
}
