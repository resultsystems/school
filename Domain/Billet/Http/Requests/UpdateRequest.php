<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:36:11
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:25
 */

namespace Domain\Billet\Http\Requests;

use Domain\Billet\Billet;
use Domain\Http\Requests\Request;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('billet');

        return Billet::where('id', $id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('billet');

        $billet = Billet::where('id', $id)->first();

        return [
            'amount' => 'required|numeric',
            'discount' => 'numeric',
            'interest' => 'numeric',
            'payment_of_fine' => 'numeric',
            'date' => 'date|date_format:Y-m-d',
            'refer' => 'required|integer|min:201604|date_format:Ym', //YYYYMM
            'student_id' => 'in:'.$billet->student_id,
            'note' => 'max:65535',
            'new_due_date' => 'date|date_format:Y-m-d|after:today',

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
}
