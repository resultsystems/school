<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:30
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:48
 */

namespace Domain\Classroom;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Domain\Matter\Matter;
use Domain\Schedule\Schedule;
use Domain\Student\Student;
use Domain\Teacher\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class ClassroomRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_store()
    {
        $repo = App::make(ClassroomRepository::class);
        $teacher = factory(Teacher::class)->create();
        $schedule = factory(Schedule::class)->create();

        $store = $repo->store([
            'name' => 'oi',
            'teacher_id' => $teacher->id,
            'schedule_id' => $schedule->id,
        ]);

        $this->assertInstanceOf(Classroom::class, $store);
        $this->assertInstanceOf(Carbon::class, $store->created_at);
    }

    public function test_update()
    {
        $repo = App::make(ClassroomRepository::class);

        $classroom = factory(Classroom::class)->create();

        $update = $repo->update(['name' => 'oi 2'], $classroom->id);

        $this->assertInstanceOf(Classroom::class, $update);
        $this->assertEquals($classroom->id, $update->id);
    }

    public function test_get()
    {
        $repo = App::make(ClassroomRepository::class);

        $classroom = factory(Classroom::class)->create();

        $get = $repo->get($classroom->id);

        $this->assertInstanceOf(Classroom::class, $get);
        $this->assertEquals($get->id, $classroom->id);
    }

    public function test_all()
    {
        $repo = App::make(ClassroomRepository::class);

        factory(Classroom::class)->create();

        $all = $repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $all);
        $this->assertInstanceOf(Classroom::class, $all->first());
    }

    public function test_delete()
    {
        $repo = App::make(ClassroomRepository::class);

        $classroom = factory(Classroom::class)->create();

        $delete = $repo->delete($classroom->id);

        $this->assertEquals(1, $delete);
        $this->setExpectedException(RepositoryException::class);

        $repo->withoutTrashed()->get($classroom->id);
    }

    public function test_force_delete()
    {
        $repo = App::make(ClassroomRepository::class);

        $classroom = factory(Classroom::class)->create();

        $repo->forceDelete($classroom->id);

        $this->setExpectedException(RepositoryException::class);
        $trashed = $repo->onlyTrashed()->get($classroom->id);
    }

    /**
     * Test associate matters.
     */
    public function test_associate_matters()
    {
        $classroom = factory(Classroom::class)->create();
        $matters = factory(Matter::class, 30)->create();
        $data = [
            'id' => $classroom->id,
            'matters' => [],
        ];
        $random = $matters->random(3)->each(function ($matter) use (&$data) {
            $data['matters'][] = ['id' => $matter->id];
        });

        $repo = App::make(ClassroomRepository::class);
        $repo->associateMatters($classroom, $data);

        foreach ($data['matters'] as $key => $value) {
            $this->seeInDatabase('classroom_matter', [
                'classroom_id' => $classroom->id,
                'matter_id' => $data['matters'][$key]['id'],
            ]);
        }
    }

    /**
     * Test associate students.
     */
    public function test_associate_students()
    {
        $classroom = factory(Classroom::class)->create();
        $students = factory(Student::class, 30)->create();
        $data = [
            'id' => $classroom->id,
            'students' => [],
        ];
        $random = $students->random(3)->each(function ($student) use (&$data) {
            $data['students'][] = ['id' => $student->id];
        });

        $repo = App::make(ClassroomRepository::class);
        $repo->associateStudents($classroom, $data);

        foreach ($data['students'] as $key => $value) {
            $this->seeInDatabase('classroom_student', [
                'classroom_id' => $classroom->id,
                'student_id' => $data['students'][$key]['id'],
            ]);
        }
    }
}
