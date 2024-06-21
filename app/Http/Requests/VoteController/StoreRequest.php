<?php

namespace App\Http\Requests\VoteController;

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
            'title' => 'required',
            'description' => 'required',
            'options' => 'required',
            'minimum_participations' => 'required',
            'status' => 'required',
            'date_end' => 'required',
            'image' => 'required|image',
        ];
    }
}
