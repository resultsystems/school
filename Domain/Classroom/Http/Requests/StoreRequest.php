<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:40:47
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:32
 */

namespace Domain\Classroom\Http\Requests;

use Domain\Http\Requests\Request;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:40|unique:classrooms',
            'teacher_id' => 'required|integer|exists:teachers,id,deleted_at,NULL',
            'schedule_id' => 'required|integer|exists:schedules,id',
            'matters.*.id' => 'integer|exists:matters,id',
            'students.*.id' => 'integer|exists:students,id',
        ];
    }
}
