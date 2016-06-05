<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:36:32
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:18
 */

namespace Domain\Billet;

use Domain\BaseModel;

class BilletAssignor extends BaseModel
{
    protected $fillable = [
        'name',
        'cnpj',
        'payment_of_fine',
        'interest',
        'is_interest',
        'days_protest',
        'postcode',
        'street',
        'complement',
        'number',
        'district',
        'city',
        'state',
        'instruction01',
        'instruction02',
        'instruction03',
        'instruction04',
        'instruction05',

        'bank',
        'agency',
        'digit_agency',
        'account',
        'digit_account',
        'wallet',
        'agreement',
        'portfolio_change',
        'range',
        'ios',
        'client_id',
        'statement01',
        'statement02',
        'statement03',
        'statement04',
        'statement05',
        'acceptance',
        'kind_document',
    ];

    public function getRecipient()
    {
        return json_encode([
            'nome' => $this->name,
            'endereco' => $this->street.', '.$this->number.' '.$this->complement,
            'cep' => $this->postcode,
            'uf' => $this->stateAbbr,
            'cidade' => $this->city,
            'documento' => $this->cnpj,
        ]);
    }

    public function getIsIsterestAttribute($value)
    {
        return (bool) $value;
    }
}
