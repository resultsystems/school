<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:50:06
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:22
 */

namespace Domain\Teacher\Http\Requests;

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

            'cpf' => 'required|cpf|unique:teachers',
            'rg' => 'max:10',

            'postcode' => 'required|max:8|regex:/[0-9]/',
            'street' => 'required|max:60',
            'number' => 'required|max:10',
            'district' => 'required|max:60',
            'city' => 'required|max:60',
            'state' => 'required|max:2',
            'phone' => 'required|phone_br',
            'cellphone' => 'required|cellphone_br',

            'salary' => 'required|positive',
            'type_salary' => 'required|in:commission,class_time',

            'user' => 'required|array',
            'user.password' => 'required|max:255',
            'user.username' => 'required|max:30|unique:users,username,NULL,id,owner_type,Domain\Teacher\Teacher',
            'user.email' => 'required|max:255|email|unique:users,email,NULL,id,owner_type,Domain\Teacher\Teacher',

            'status' => 'required|boolean',
        ];
    }
}
