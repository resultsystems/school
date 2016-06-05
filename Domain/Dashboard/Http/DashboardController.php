<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 19:07:12
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:37
 */

namespace Domain\Dashboard\Http;

use Domain\Employee\EmployeeRepository;
use Domain\Student\StudentRepository;
use Domain\Teacher\TeacherRepository;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    /**
     * Dashboard construct.
     *
     * @param EmployeeRepository $employee
     * @param StudentRepository  $student
     * @param TeacherRepository  $teacher
     */
    public function __construct(EmployeeRepository $employee, StudentRepository $student, TeacherRepository $teacher)
    {
        $this->employee = $employee;
        $this->student = $student;
        $this->teacher = $teacher;
    }

    public function index()
    {
        return response()->json([
            'employees' => [
                'registers' => $this->employee->count(),
                'deletes' => $this->employee->onlyTrashed()->count(),
            ],
            'students' => [
                'registers' => $this->student->count(),
                'deletes' => $this->student->onlyTrashed()->count(),
            ],
            'teachers' => [
                'registers' => $this->teacher->count(),
                'deletes' => $this->teacher->onlyTrashed()->count(),
            ],
        ]);
    }
}
