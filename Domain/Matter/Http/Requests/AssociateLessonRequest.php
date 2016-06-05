<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-19 10:29:02
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:59
 */

namespace Domain\Matter\Http\Requests;

use Domain\Http\Requests\Request;
use Domain\Matter\Matter;

class AssociateLessonRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Matter::where('id', $this->route('matter_id'))->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lessons' => 'array',
            'lessons.*.id' => 'integer|exists:lessons,id',
        ];
    }
}
