<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 22:04:06
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:52
 */

namespace Domain\Lesson;

use Domain\BaseModel;
use Domain\Matter\Matter;

class Lesson extends BaseModel
{
    protected $fillable = ['name', 'description'];

    public function matters()
    {
        return $this->belongsToMany(Matter::class, 'matter_lesson');
    }
}
