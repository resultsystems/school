<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:05
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:01
 */

namespace Domain\Matter\Http\Requests;

use Domain\Http\Requests\Request;
use Domain\Matter\Matter;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('matter');

        return Matter::where('id', $id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('matter');

        return [
            'name' => 'required|max:40|unique:matters,name,'.$id,
            'workload' => 'required|integer|min:1|max:9999999',
        ];
    }
}
