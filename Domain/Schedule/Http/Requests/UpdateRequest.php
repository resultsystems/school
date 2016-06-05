<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:11
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:09
 */

namespace Domain\Schedule\Http\Requests;

use Domain\Http\Requests\Request;
use Domain\Schedule\Schedule;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('schedule');

        return Schedule::where('id', $id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('schedule');

        return [
            'name' => 'required|max:40|unique:schedules,name,'.$id,
            'start' => 'required|hora',
            'end' => 'required|hora',
        ];
    }
}
