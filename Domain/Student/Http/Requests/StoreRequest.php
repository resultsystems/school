<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:50:22
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:15
 */

namespace Domain\Student\Http\Requests;

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

            'father' => 'max:45',
            'mother' => 'max:45',
            'responsible' => 'required|max:45',
            'cpf_responsible' => 'cpf',
            'phone_father' => 'max:11|phone_br',
            'phone_mother' => 'max:11|phone_br',
            'phone_responsible' => 'required|max:11|phone_br',

            'postcode' => 'required|max:8|regex:/[0-9]/',
            'street' => 'required|max:60',
            'number' => 'required|max:10',
            'district' => 'required|max:60',
            'city' => 'required|max:60',
            'state' => 'required|max:2',
            'phone' => 'required|max:10|phone_br',
            'cellphone' => 'required|max:11|cellphone_br',
            'complement' => 'max:30',

            'monthly_payment' => 'required|numeric|positive',
            'day_of_payment' => 'required|integer|min:1|max:31',
            'installments' => 'integer',

            'user' => 'required|array',
            'user.password' => 'required|min:6|max:255',
            'user.username' => 'required|max:30|unique:users,username,NULL,id,owner_type,Domain\Student\Student',
            'user.email' => 'required|max:255|email|unique:users,email,NULL,id,owner_type,Domain\Student\Student',

            'status' => 'boolean',
        ];
    }
}
