<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:31
 */

namespace Student\Billet\Http\Requests;

use Domain\Billet\BilletAssignor;
use Student\Http\Requests\Request;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return BilletAssignor::exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_due_date',
        ];
    }

    public function forbiddenResponse()
    {
        return response()->json(['error' => "There isn't a transferor, must create a transferor to be able to create billet."], 403);
    }
}
