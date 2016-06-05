<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:21
 */

/*
Event::listen('illuminate.query', function ($sql, $p) {
print_r($sql);
echo '<br>';
echo '<br>';
print_r($p);
echo '<br>';
echo '<br>';
});
//*/

Route::get('/', function () {
    return view('index');
});

/*
 * Without Auth
 */
Route::group(['prefix' => 'api/v1/'], function () {
    Route::post('auth/login', '\Domain\Auth\AuthController@login');
});

/*
 * Auth
 */
Route::group(['prefix' => 'api/student/v1/', 'middleware' => ['jwt.auth', 'student']], function () {
    Route::get('billet', '\Student\Billet\Http\BilletController@index');

    Route::get('billet/{billet_id}', '\Student\Billet\Http\BilletController@show')
        ->where('billet_id', '[0-9]+');

    Route::get('billet/{billet_id}/pdf', '\Student\Billet\Http\BilletController@pdf')
        ->where('billet_id', '[0-9]+');

    Route::put('billet/{billet_id}', '\Student\Billet\Http\BilletController@update')
        ->where('billet_id', '[0-9]+');

    //Route::get('classroom', '\Student\Classroom\Http\ClassroomController@index');

    Route::get('teacher', '\Student\Teacher\Http\TeacherController@index');

    //Route::put('student/me', '\Student\Http\StudentController@updateMe');
});

Route::group(['prefix' => 'api/teacher/v1/', 'middleware' => ['jwt.auth', 'teacher']], function () {
    Route::get('classroom', '\Teacher\Classroom\Http\ClassroomController@index');

    Route::get('student', '\Teacher\Student\Http\StudentController@index');
});

Route::group(['prefix' => 'api/v1/', 'middleware' => ['jwt.auth']], function () {
    Route::get('auth/logout', '\Domain\Auth\AuthController@logout');

    Route::put('user/me', '\Domain\User\Http\UserController@updateMe');

    /*
     * Employee
     */
    Route::group(['middleware' => 'employee'], function () {
        Route::get('dashboard', '\Domain\Dashboard\Http\DashboardController@index');

        Route::get('billet/{billet_id}/pdf', '\Domain\Billet\Http\BilletController@pdf')
            ->where('billet_id', '[0-9]+');

        Route::get('billet/defaulters', '\Domain\Billet\Http\BilletController@defaulters');

        Route::get('billet/assignor/first', '\Domain\Billet\Http\BilletAssignorController@show');

        Route::post('billet/assignor', '\Domain\Billet\Http\BilletAssignorController@store');

        Route::put('billet/{billet_id}/pay', '\Domain\Billet\Http\BilletController@pay')
            ->where('billet_id', '[0-9]+');

        Route::resource('billet', '\Domain\Billet\Http\BilletController', ['except' => ['create', 'edit']]);

        Route::delete('trashed/billet/{billet_id}', '\Domain\Billet\Http\BilletController@forceDelete')
            ->where('billet_id', '[0-9]+');

        Route::put('restore/billet/{billet_id}', '\Domain\Billet\Http\BilletController@restore')
            ->where('billet_id', '[0-9]+');

        Route::resource('classroom', '\Domain\Classroom\Http\ClassroomController', ['except' => ['create', 'edit']]);

        Route::put('classroom/matters/completeds', '\Domain\Classroom\Http\ClassroomController@attachMatterCompleteds');

        Route::get('classroom/{classroom_id}/students', '\Domain\Classroom\Http\ClassroomController@getStudents')
            ->where('classroom_id', '[0-9]+');

        Route::put('classroom/{classroom_id}/matters', '\Domain\Classroom\Http\ClassroomController@associateMatters')
            ->where('classroom_id', '[0-9]+');

        Route::put('classroom/{classroom_id}/students', '\Domain\Classroom\Http\ClassroomController@associateStudents')
            ->where('classroom_id', '[0-9]+');

        Route::resource('config', '\Domain\Config\Http\ConfigController', ['except' => ['create', 'edit', 'update']]);

        Route::resource('employee', '\Domain\Employee\Http\EmployeeController', ['except' => ['create', 'edit']]);

        Route::delete('trashed/employee/{employee_id}', '\Domain\Employee\Http\EmployeeController@forceDelete')
            ->where('employee_id', '[0-9]+');

        Route::put('restore/employee/{employee_id}', '\Domain\Employee\Http\EmployeeController@restore')
            ->where('employee_id', '[0-9]+');

        Route::resource('lesson', '\Domain\Lesson\Http\LessonController', ['except' => ['create', 'edit']]);

        Route::resource('matter', '\Domain\Matter\Http\MatterController', ['except' => ['create', 'edit']]);

        Route::put('matter/{matter_id}/lessons', '\Domain\Matter\Http\MatterController@associateLessons')
            ->where('matter_id', '[0-9]+');

        Route::resource('schedule', '\Domain\Schedule\Http\ScheduleController', ['except' => ['create', 'edit']]);

        Route::get('student/{student_id}/billets', '\Domain\Student\Http\StudentController@getBillets')
            ->where('student_id', '[0-9]+');

        Route::get('student/{student_id}/classrooms', '\Domain\Student\Http\StudentController@getWithClassrooms')
            ->where('student_id', '[0-9]+');

        Route::get('student/{student_id}/classrooms/matters', '\Domain\Student\Http\StudentController@getWithClassroomsAndMatters')
            ->where('student_id', '[0-9]+');

        Route::put('student/{student_id}/matters/completeds/sync', '\Domain\Student\Http\StudentController@syncMattersCompleteds')
            ->where('student_id', '[0-9]+');

        Route::resource('student', '\Domain\Student\Http\StudentController', ['except' => ['create', 'edit']]);

        Route::delete('trashed/student/{student_id}', '\Domain\Student\Http\StudentController@forceDelete')
            ->where('student_id', '[0-9]+');

        Route::put('restore/student/{student_id}', '\Domain\Student\Http\StudentController@restore')
            ->where('student_id', '[0-9]+');

        Route::resource('teacher', '\Domain\Teacher\Http\TeacherController', ['except' => ['create', 'edit']]);

        Route::put('teacher/{teacher_id}/matters', '\Domain\Teacher\Http\TeacherController@associateMatters')
            ->where('teacher_id', '[0-9]+');

        Route::delete('trashed/teacher/{teacher_id}', '\Domain\Teacher\Http\TeacherController@forceDelete')
            ->where('teacher_id', '[0-9]+');

        Route::put('restore/teacher/{teacher_id}', '\Domain\Teacher\Http\TeacherController@restore')
            ->where('teacher_id', '[0-9]+');

        Route::resource('user', '\Domain\User\Http\UserController', ['except' => ['create', 'edit']]);

        Route::delete('trashed/user/{user_id}', '\Domain\User\Http\UserController@forceDelete')
            ->where('user_id', '[0-9]+');

        Route::put('restore/user/{user_id}', '\Domain\User\Http\UserController@restore')
            ->where('user_id', '[0-9]+');
    });
});
