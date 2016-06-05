<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-19 09:39:00
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:30
 */

namespace Domain\Classroom\Http\Requests;

use Domain\Classroom\Classroom;
use Domain\Http\Requests\Request;

class AssociateStudentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('classroom_id');

        return Classroom::where('id', $id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'students' => 'array',
            'students.*.id' => 'integer|exists:students,id',
        ];
    }
}
