<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:40:54
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:42
 */

namespace Domain\Employee\Http\Requests;

use Domain\Employee\Employee;
use Domain\Http\Requests\Request;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('employee');

        return Employee::where('id', $id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('employee');
        $user_id = 'NULL';
        $employee = Employee::find($id);
        $user = $employee->user;
        if (isset($user)) {
            $user_id = $user->id;
        }

        return [
            'name' => 'required|max:45',
            'sex' => 'required|in:male,female',

            'user' => 'required|array',
            'user.username' => 'required|max:30|unique:users,username,'.$user_id.',id,owner_type,Domain\Employee\Employee',
            'user.email' => 'required|max:255|email|unique:users,email,'.$user_id.',id,owner_type,Domain\Employee\Employee',
        ];
    }
}
