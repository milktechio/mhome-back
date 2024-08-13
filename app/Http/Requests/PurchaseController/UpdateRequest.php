<?php

namespace App\Http\Requests\PurchaseController;

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
            'product_id' => 'nullable|exists:products,id',
            'company_id' => 'nullable|exists:companys,id',
            'latitude' => 'required',
            'longitude' => 'required',
            'sold' => 'required',
            // 'transactionHash' => 'required|unique:transactions,transaction_hash',
            'transactionIndex' => 'required',
            'payment' => 'required',
        ];
    }
}
