<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:49:47
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:42
 */

namespace Domain\Employee\Http\Requests;

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
            'name' => 'required|max:45',
            'sex' => 'required|in:male,female',

            'user' => 'required|array',
            'user.password' => 'required|max:255',
            'user.username' => 'required|max:30|unique:users,username,NULL,id,owner_type,Domain\Employee\Employee',
            'user.email' => 'required|max:255|email|unique:users,email,NULL,id,owner_type,Domain\Employee\Employee',

        ];
    }
}
