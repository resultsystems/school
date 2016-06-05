<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:16
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:16
 */

namespace Domain\Student\Http\Requests;

use Domain\Http\Requests\Request;
use Domain\Student\Student;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('student');

        return Student::where('id', $id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('student');
        $user_id = 'NULL';
        $student = Student::find($id);
        $user = $student->user;
        if (isset($user)) {
            $user_id = $user->id;
        }

        return [
            'name' => 'required|max:45',
            'sex' => 'required|in:male,female',

            'father' => 'max:45',
            'mother' => 'max:45',
            'responsible' => 'required|max:45',
            'cpf_responsible' => 'cpf|unique:students,cpf_responsible,'.$id,
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
            'user.username' => 'required|max:30|unique:users,username,'.$user_id.',id,owner_type,Domain\Student\Student',
            'user.email' => 'required|max:255|email|unique:users,email,'.$user_id.',id,owner_type,Domain\Student\Student',

            'status' => 'boolean',
        ];
    }
}
