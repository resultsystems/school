<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:37
 */
use Domain\Billet\Billet;
use Domain\Billet\BilletAssignor;
use Domain\Classroom\Classroom;
use Domain\Classroom\ClassroomMatter;
use Domain\Config\Config;
use Domain\Employee\Employee;
use Domain\Lesson\Lesson;
use Domain\Matter\Matter;
use Domain\Schedule\Schedule;
use Domain\Student\Student;
use Domain\Teacher\Teacher;
use Domain\User\User;
use Illuminate\Database\Seeder;

class Fakers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schedule::truncate();

        Billet::truncate();
        BilletAssignor::truncate();
        Classroom::truncate();
        ClassroomMatter::truncate();
        Config::truncate();
        Employee::truncate();
        Lesson::truncate();
        Matter::truncate();
        Schedule::truncate();
        Student::truncate();
        Teacher::truncate();

        factory(BilletAssignor::class, 1)->create();
        factory(Billet::class, 5)->create();
        $allClassrooms = factory(Classroom::class, 10)->create();
        factory(ClassroomMatter::class, 10)->create();
        factory(Config::class, 10)->create();
        $employee = factory(Employee::class)->create();
        factory(Lesson::class, 40)->create();
        $matters = factory(Matter::class, 10)->create();
        factory(Schedule::class, 3)->create();
        $student = factory(Student::class)->create();
        $teacher = factory(Teacher::class)->create();

        factory(User::class, 1)->create([
            'username' => 'funcionario',
            'password' => bcrypt('funcionario123'),
            'owner_type' => Employee::class,
            'owner_id' => $employee->id,
        ]);

        factory(User::class, 1)->create([
            'username' => 'aluno',
            'password' => bcrypt('aluno123'),
            'owner_type' => Student::class,
            'owner_id' => $student->id,
        ]);

        factory(User::class, 1)->create([
            'username' => 'professor',
            'password' => bcrypt('professor123'),
            'owner_type' => Teacher::class,
            'owner_id' => $teacher->id,
        ]);

        $classrooms = factory(Classroom::class, 3)->create([
            'teacher_id' => $teacher->id,
        ]);

        $classrooms->each(function ($classroom) use ($student, $matters) {
            $classroom->students()->attach($student);
            $matters->random(3)->each(function ($matter) use ($classroom) {
                $classroom->matters()->attach($matter);
            });
        });

        $allClassrooms->random(3)->each(function ($classroom) use ($student, $matters, $teacher) {
            $classroom->teacher()->associate($teacher);
            $classroom->students()->attach($student);
            $matters->random(3)->each(function ($matter) use ($classroom) {
                $classroom->matters()->attach($matter);
            });
        });

        factory(User::class, 30)->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
