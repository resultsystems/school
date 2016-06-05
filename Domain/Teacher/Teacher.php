<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:16
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:17
 */

namespace Domain\Teacher;

use Domain\BaseModel;
use Domain\Classroom\Classroom;
use Domain\Matter\Matter;
use Domain\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'sex', 'cpf', 'rg',
        'postcode', 'street', 'number', 'district', 'city', 'state',
        'phone', 'cellphone', 'status', 'salary', 'type_salary',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->morphOne(User::class, 'owner');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function matters()
    {
        return $this->belongsToMany(Matter::class, 'teacher_matter');
    }
}
