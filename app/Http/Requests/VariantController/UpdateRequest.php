<?php

namespace App\Http\Requests\VariantController;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'name'=>'required',
            'description'=>'required',
            'content'=>'required',
            'price'=>'required',
            'currency'=>'required',
            'stock'=>'required',
            'active'=>'required',
        ];
    }
}
