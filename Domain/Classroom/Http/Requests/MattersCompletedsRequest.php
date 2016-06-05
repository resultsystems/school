<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-28 09:55:48
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:31
 */

namespace Domain\Classroom\Http\Requests;

use Domain\Classroom\Classroom;
use Domain\Http\Requests\Request;

class MattersCompletedsRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*.id' => 'required|exists:classroom_matter',
        ];
    }
}
