<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:27
 */

namespace Domain\User\Http\Requests;

use Domain\Http\Requests\Request;
use Domain\User\User;

/**
 * Update Request.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('user');

        return User::where('id', $id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('user');

        return [
            'username' => 'required|max:30|unique:users,username,'.$id,
            'email' => 'required|max:255|email|unique:users,email,'.$id,
        ];
    }
}
