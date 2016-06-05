<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:36:16
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:24
 */

namespace Domain\Billet\Http\Requests;

use Domain\Billet\BilletAssignor;
use Domain\Http\Requests\Request;

class StoreRequest extends Request
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
            'amount' => 'required|numeric',
            'discount' => 'numeric',
            'interest' => 'numeric',
            'payment_of_fine' => 'numeric',
            'date' => 'date|date_format:Y-m-d',
            'refer' => 'required|integer|min:201604|date_format:Ym', //YYYYMM
            'student_id' => 'required|exists:students,id,deleted_at,NULL',
            'note' => 'max:65535',
            'due_date' => 'required|date|date_format:Y-m-d|after:today',

            'statement01' => 'max:30',
            'statement02' => 'max:30',
            'statement03' => 'max:30',
            'statement04' => 'max:30',
            'statement05' => 'max:30',
            'instruction01' => 'max:30',
            'instruction02' => 'max:30',
            'instruction03' => 'max:30',
            'instruction04' => 'max:30',
            'instruction05' => 'max:30',
            'acceptance' => 'boolean',
            'kind_document' => 'max:3',
        ];
    }

    public function forbiddenResponse()
    {
        return response()->json(['error' => "There isn't a transferor, must create a transferor to be able to create billet."], 403);
    }
}
