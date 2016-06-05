<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:40:47
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:33
 */

namespace Domain\Classroom\Http\Requests;

use Domain\Http\Requests\Request;

class UpdateRequest extends StoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('classroom');

        return [
            'name' => 'required|max:40|unique:classrooms,name,'.$id,
            'teacher_id' => 'required|integer|exists:teachers,id,deleted_at,NULL',
            'schedule_id' => 'required|integer|exists:schedules,id',
            'matters.*.id' => 'integer|exists:matters,id',
            'students.*.id' => 'integer|exists:students,id',
        ];
    }
}
