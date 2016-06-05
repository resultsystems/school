<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:02
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:56
 */

namespace Domain\Lesson\Http\Requests;

use Domain\Http\Requests\Request;
use Domain\Lesson\Lesson;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('lesson');

        return Lesson::where('id', $id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('lesson');

        return [
            'name' => 'required|max:40|unique:lessons,name,'.$id.',id',
            'description' => 'max:65535',
        ];
    }
}
