<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:36:07
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:25
 */

namespace Domain\Billet\Http\Requests\BilletAssignor;

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
            'name' => 'required|max:60',
            'cnpj' => 'required|max:14|cnpj',
            'payment_of_fine' => 'numeric|min:0',
            'interest' => 'numeric|min:0',
            'is_interest' => 'boolean',
            'days_protest' => 'numeric|min:0',

            'postcode' => 'required|max:8|regex:/[0-9]/',
            'street' => 'required|max:60',
            'number' => 'required|max:10',
            'complement' => 'max:30',
            'district' => 'required|max:60',
            'city' => 'required|max:60',
            'state' => 'required|max:2',

            'bank' => 'required|in:bb,bradesco,caixa,hsbc,itau,santander',
            'agency' => 'required|integer|max:9999',
            'digit_agency' => 'integer|max:9',
            'account' => 'required|integer|max:99999999',
            'digit_account' => 'required_if:bank,bradesco,hsbc,itau|integer|max:9',
            'wallet' => 'required|max:3',
            'agreement' => 'required_if:bank,bb|integer|convencio_bb',
            'portfolio_change' => 'integer|max:999',
            'range' => 'integer|max:999',
            'ios' => 'integer|max:999',
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
            'acceptance' => 'required|boolean',
            'kind_document' => 'max:3',
        ];
    }

    public function all()
    {
        $input = parent::all();
        if (isset($input['is_interest'])) {
            if ($input['is_interest'] == 'true') {
                $input['is_interest'] = true;
            }
            if ($input['is_interest'] == 'false') {
                $input['is_interest'] = false;
            }
        }
        if (isset($input['acceptance'])) {
            if ($input['acceptance'] == 'true') {
                $input['acceptance'] = true;
            }
            if ($input['acceptance'] == 'false') {
                $input['acceptance'] = false;
            }
        }
        foreach ($input as $key => $value) {
            if ($input[$key] == 'null') {
                $input[$key] = '';
            }
        }

        return $input;
    }
}
