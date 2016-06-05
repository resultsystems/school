<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:03:43
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:06
 */

namespace Domain\Student;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Domain\Billet\Billet;
use Domain\Classroom\Classroom;
use Domain\Matter\Matter;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentRepositoryTest extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    public function test_store()
    {
        $repo = App::make(StudentRepository::class);

        $store = $repo->store(['name' => 'oi']);

        $this->assertInstanceOf(Student::class, $store);
        $this->assertInstanceOf(Carbon::class, $store->created_at);
    }

    public function test_update()
    {
        $repo = App::make(StudentRepository::class);

        $student = factory(Student::class)->create();

        $update = $repo->update(['name' => 'oi 2'], $student->id);

        $this->assertInstanceOf(Student::class, $update);
        $this->assertEquals($student->id, $update->id);
    }

    public function test_get()
    {
        $repo = App::make(StudentRepository::class);

        $student = factory(Student::class)->create();

        $get = $repo->get($student->id);

        $this->assertInstanceOf(Student::class, $get);
        $this->assertEquals($get->id, $student->id);
    }

    public function test_all()
    {
        $repo = App::make(StudentRepository::class);

        factory(Student::class)->create();

        $all = $repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $all);
        $this->assertInstanceOf(Student::class, $all->first());
    }

    public function test_delete()
    {
        $repo = App::make(StudentRepository::class);

        $student = factory(Student::class)->create();

        $delete = $repo->delete($student->id);

        $trashed = $repo->onlyTrashed()->get($student->id);

        $this->assertEquals(1, $delete);
        $this->assertInstanceOf(Carbon::class, $trashed->deleted_at);
        $this->assertInstanceOf(Student::class, $trashed);

        $this->setExpectedException(RepositoryException::class);
        $repo->withoutTrashed()->get($student->id);
    }

    public function test_force_delete()
    {
        $repo = App::make(StudentRepository::class);

        $student = factory(Student::class)->create();

        $repo->forceDelete($student->id);

        $this->setExpectedException(RepositoryException::class);
        $trashed = $repo->onlyTrashed()->get($student->id);
    }

    public function test_restore()
    {
        $repo = App::make(StudentRepository::class);

        $student = factory(Student::class)->create();

        $repo->delete($student->id);

        $restore = $repo->restore($student->id);

        $get = $repo->get($student->id);

        $this->assertEquals(1, $restore);
        $this->assertNull($get->deleted_at);
        $this->assertInstanceOf(Student::class, $get);
    }

    public function test_get_billets()
    {
        $student = factory(Student::class)->create();
        $billets = factory(Billet::class, 5)->create([
            'student_id' => $student->id,
        ]);

        $repo = App::make(StudentRepository::class);
        $student = $repo->getBillets($student->id);

        $this->assertInstanceOf(Student::class, $student);
        $this->assertInstanceOf(Billet::class, $student->billets->first());

        foreach ($billets as $billet) {
            $this->seeInDatabase('billets', [
                'id' => $billet->id,
                'student_id' => $student->id,
            ]);
        }
    }

    public function test_get_with_classrooms()
    {
        $student = factory(Student::class)->create();
        factory(Classroom::class, 10)->create()->each(function ($c) use ($student) {
            $student->classrooms()->attach($c);
        });

        $repo = App::make(StudentRepository::class);

        $classrooms = $repo->getWithClassrooms($student->id);

        foreach ($classrooms as $classroom) {
            $this->assertInstanceOf(Classroom::class, $classroom);
        }
    }

    public function test_get_with_classrooms_with_matters()
    {
        $student = factory(Student::class)->create();
        $matters = factory(Matter::class, 40)->create();

        $classrooms = factory(Classroom::class, 10)->create();

        $classrooms->each(function ($classroom) use ($student, $matters) {
            $student->classrooms()->attach($classroom);
            $matters->random(rand(3, 5))->each(function ($matter) use ($classroom) {
                $classroom->matters()->attach($matter);
            });
        });

        $repo = App::make(StudentRepository::class);

        $student = $repo->getWithClassroomsAndMatters($student->id);

        foreach ($student->classrooms as $classroom) {
            $this->assertInstanceOf(Classroom::class, $classroom);
            foreach ($classroom->matters as $matter) {
                $this->assertInstanceOf(Matter::class, $matter);
            }
        }
    }
}
