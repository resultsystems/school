<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-10 05:36:04
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:07
 */

namespace Domain\Student;

use Carbon\Carbon;
use Domain\Billet\Billet;
use Domain\Classroom\Classroom;
use Domain\Classroom\ClassroomMatter;
use Domain\Matter\Matter;
use Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StudentTest extends \TestCase
{
    use DatabaseTransactions;

    public function getPayer($student)
    {
        return json_encode([
            'nome' => $student->responsible,
            'endereco' => $student->street.', '.$student->number.' '.$student->complement,
            'cep' => $student->postcode,
            'uf' => $student->stateAbbr,
            'cidade' => $student->city,
            'documento' => $student->cpf_responsible,
        ]);
    }

    public function test_create_student()
    {
        $student = factory(Student::class)->create(['sex' => 'female']);

        $this->assertInstanceOf(Student::class, $student);

        $this->assertInstanceOf(Carbon::class, $student->created_at);
        $this->assertInstanceOf(Carbon::class, $student->updated_at);

        $this->seeInDatabase('students', [
            'name' => $student->name,
            'sex' => $student->sex,
        ]);

        $this->assertEquals($student->getPayer(), $this->getPayer($student));
    }

    public function test_create_student_with_value()
    {
        $student = factory(Student::class)->create([
            'name' => 'Aluno x',
        ]);

        $this->assertInstanceOf(Student::class, $student);
        $this->seeInDatabase('students', [
            'name' => 'Aluno x',
        ]);
    }

    public function test_create_student_with_deleted()
    {
        $student = factory(Student::class)->create();

        $this->assertInstanceOf(Student::class, $student);
        $student->delete();

        $this->assertInstanceOf(Carbon::class, $student->created_at);
        $this->assertInstanceOf(Carbon::class, $student->updated_at);
        $this->assertInstanceOf(Carbon::class, $student->deleted_at);
    }

    public function test_create_student_without_user()
    {
        $student = factory(Student::class)->create();

        $this->assertInstanceOf(Student::class, $student);
        $this->assertNull($student->user);
    }

    public function test_create_student_with_user()
    {
        $student = factory(Student::class)->create();

        $user = factory(User::class)->create([
            'owner_id' => $student->id,
            'owner_type' => 'Domain\Student\Student',
        ]);

        $this->assertInstanceOf(Student::class, $student);

        $this->assertInstanceOf(User::class, $student->user);
    }

    public function test_create_student_with_classroom()
    {
        $classroom = factory(Classroom::class)->create();

        $student = factory(Student::class)->create();

        $student->classrooms()->attach($classroom);

        $this->assertInstanceOf(Classroom::class, $student->classrooms->first());
    }

    public function test_create_student_with_matter_completed()
    {
        $student = factory(Student::class)->create();
        $classroom = factory(Classroom::class)->create();
        $matter = factory(Matter::class)->create();
        $classroomMatter = factory(ClassroomMatter::class)->create([
            'classroom_id' => $classroom->id,
            'matter_id' => $matter->id,
        ]);

        $student->classrooms()->attach($classroom);

        $student->matterCompleteds()->attach($classroomMatter);

        $this->assertInstanceOf(ClassroomMatter::class, $student->matterCompleteds->first());

        $this->seeInDatabase('classroom_matter_student_completed', [
            'student_id' => $student->id,
            'classroom_matter_id' => $classroomMatter->id,
        ]);
    }

    public function test_complete_matter()
    {
        $student = factory(Student::class)->create();
        $classroom = factory(Classroom::class)->create();
        $matter = factory(Matter::class)->create();

        $classroomMatter = factory(ClassroomMatter::class)->create([
            'classroom_id' => $classroom->id,
            'matter_id' => $matter->id,
        ]);

        $student->classrooms()->attach($classroom);

        $this->assertTrue($student->completeMatter($classroom->id, $matter->id));
    }

    public function test_complete_matter_failed()
    {
        $student = factory(Student::class)->create();
        $classroom = factory(Classroom::class)->create();
        $matter = factory(Matter::class)->create();

        $this->assertTrue(!$student->completeMatter($classroom->id, $matter->id));
    }

    public function test_create_student_with_billet()
    {
        $student = factory(Student::class)->create();
        $billet = factory(Billet::class)->create([
            'student_id' => $student->id,
        ]);

        $this->assertInstanceOf(Billet::class, $student->billets()->first());
    }

    public function test_has_delayed_payments()
    {
        $student = factory(Student::class)->create();
        $billet = factory(Billet::class)->create([
            'student_id' => $student->id,
            'amount' => 100,
            'due_date' => Carbon::yesterday(),
        ]);

        $this->assertTrue($student->hasDelayed());
    }

    public function test_hasnt_delayed_payments()
    {
        $student = factory(Student::class)->create();
        $billet = factory(Billet::class)->create([
            'student_id' => $student->id,
            'amount' => 100,
            'due_date' => Carbon::tomorrow(),
        ]);

        $this->assertFalse($student->hasDelayed());
    }
}
