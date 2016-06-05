<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:27
 */

namespace Domain\User\Http\Requests;

use Domain\Http\Requests\Request;

/**
 * Store Request.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
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
            'username' => 'required|max:30|unique:users',
            'email' => 'required|max:255|email|unique:users',
            'password' => 'required|min:6',
        ];
    }
}
