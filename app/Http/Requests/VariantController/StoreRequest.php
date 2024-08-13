<?php

namespace App\Http\Requests\VariantController;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'image'=>'required|image',
            'price'=>'required',
            'currency'=>'required',
            'stock'=>'required',
            'product_id'=>'required',
            'active'=>'required',
            'recurring'=>'nullable',
            'stripe_price_id'=>'nullable',
            ];
    }
}
