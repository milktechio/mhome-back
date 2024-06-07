<?php

namespace App\Http\Requests\UserController;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawBalanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'saldo' => 'nullable',
            'comission' => 'nullable',
            'rank' => 'nullable',
        ];
    }
}
