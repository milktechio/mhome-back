<?php

namespace App\Http\Requests\ProfileController;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
        $id = Auth::user()->id;

        return [
            'name' => 'nullable',
            'lastname' => 'nullable',
            'email' => 'nullable',
            'mobile' => 'nullable|digits:10',
            'code_mobile' => 'nullable',
            'gender' => 'nullable',
            'eth_address' => [
                'nullable',
                'string',
                Rule::unique('users', 'eth_address')->ignore($id),
            ],
            'chain_id' => [
                'nullable',
                'string',
                Rule::unique('users', 'chain_id')->ignore($id),
            ],
        ];
    }
}
