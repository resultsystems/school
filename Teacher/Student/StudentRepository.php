<?php

namespace Teacher\Student;

use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;

class StudentRepository extends \Domain\Student\StudentRepository
{
    public function model()
    {
        return Student::class;
    }

    /**
     * makeModel.
     *
     * @return Illuminate\Database\Eloquent\Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        $model = $model->whereHas('classrooms', function ($q) {
            $q->where('teacher_id', 21);
        });

        return $this->model = $model;
    }
}
