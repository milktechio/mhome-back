<?php

namespace App\Http\Requests\UserController;

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
        $id = $this->route('user');

        return [
            'username' => [
                'required',
                'string',
                Rule::unique('users', 'username')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'rangue_id' => 'required|exists:rangues,id',
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,name',
        ];
    }
}
