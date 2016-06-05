<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-10 05:33:58
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:10
 */

namespace Domain\Student;

use Domain\BaseModel;
use Domain\Billet\Billet;
use Domain\Classroom\Classroom;
use Domain\Classroom\ClassroomMatter;
use Domain\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['has_delayed'];

    protected $fillable = [
        'name', 'sex', 'father', 'mother', 'responsible',
        'phone_father', 'phone_mother', 'phone_responsible',
        'cpf_responsible', 'postcode', 'street', 'number',
        'district', 'city', 'state', 'phone', 'cellphone',
        'monthly_payment', 'day_of_payment', 'installments',
        'status',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->morphOne(User::class, 'owner');
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class);
    }

    public function matterCompleteds()
    {
        return $this->belongsToMany(ClassroomMatter::class, 'classroom_matter_student_completed');
    }

    public function billets()
    {
        return $this->hasMany(Billet::class);
    }

    //Service
    public function completeMatter($classroom_id, $matter_id)
    {
        $classroomMatter = ClassroomMatter::where('classroom_id', $classroom_id)
            ->where('matter_id', $matter_id)
            ->first();

        if (is_null($classroomMatter)) {
            return false;
        }

        $this->matterCompleteds()->attach($classroomMatter);

        $student = $this->whereHas('matterCompleteds', function ($q) use ($classroomMatter) {
            $q->where('id', $classroomMatter->id);
        })->first();

        return (!is_null($student));
    }

    public function getPayer()
    {
        return json_encode([
            'nome' => $this->responsible,
            'endereco' => $this->street.', '.$this->number.' '.$this->complement,
            'cep' => $this->postcode,
            'uf' => $this->stateAbbr,
            'cidade' => $this->city,
            'documento' => $this->cpf_responsible,
        ]);
    }

    public function delayedPayments()
    {
        $student = $this->with(['billets' => function ($b) {
            $b->where('due_date', '<', date('Y-m-d'))
                ->where('student_id', $this->id);
        }])
            ->whereHas('billets', function ($b) {
                $b->where('due_date', '<', date('Y-m-d'))
                    ->where('student_id', $this->id);
            })
            ->first();

        if (is_null($student)) {
            return [];
        }

        return ($student->billets);
    }

    public function hasDelayed()
    {
        return ($this->delayedPayments() !== []);
    }

    public function getHasDelayedAttribute()
    {
        return $this->hasDelayed();
    }
}
