<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:40:59
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:51
 */

namespace Domain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    public function __construct()
    {
        $this->validator = app('validator');
        $this->validateConvenioBb($this->validator);

        parent::__construct();
    }

    public function validateConvenioBb($validator)
    {
        $validator->extend('convencioBb', function ($attribute, $value, $parameters) {

            return in_array(strlen($value), [4, 6, 7]);
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function forbiddenResponse()
    {
        return response()->json('Forbidden', 403);
    }

    public function response(array $errors)
    {
        return response()->json($errors, 422);
    }
}
