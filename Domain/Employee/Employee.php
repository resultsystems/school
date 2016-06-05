<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 19:08:38
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:38
 */

namespace Domain\Employee;

use Domain\BaseModel;
use Domain\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['name', 'sex'];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->morphOne(User::class, 'owner');
    }
}
