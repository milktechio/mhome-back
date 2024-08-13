<?php

namespace App\Http\Requests\TransactionController;

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
            'product_id' => 'nullable|exists:products,id',
            'company_id' => 'nullable|exists:company_commissions,id',
            'variant_id' => 'nullable|exists:variants,id',
            'product' => 'nullable',
            'latitude' => 'required',
            'longitude' => 'required',
            'sold' => 'required',
            // 'transactionHash' => 'required|unique:transactions,transaction_hash',
            'transactionIndex' => 'required',
        ];
    }
}
