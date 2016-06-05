<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-19 09:39:00
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:29
 */

namespace Domain\Classroom\Http\Requests;

use Domain\Classroom\Classroom;
use Domain\Http\Requests\Request;

class AssociateMatterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Classroom::where('id', $this->route('classroom_id'))->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'matters' => 'array',
            'matters.*.id' => 'integer|exists:matters,id',
        ];
    }
}
