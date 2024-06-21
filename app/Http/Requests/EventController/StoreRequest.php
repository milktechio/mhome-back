<?php

namespace App\Http\Requests\EventController;

use Auth;
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
        $roles = collect(Auth::user()->roles)->pluck('name')->unique()->toArray();

        $club_id = 'nullable';
        if (in_array('usuario', $roles)) {
            $club_id = 'required|exists:clubs,id';
        }

        return [
            'title' => 'required|string',
            'body' => 'required',
            'club_id' => $club_id,
            'image' => 'required|image|max:1024',
            'is_news' => 'nullable',
            'concept' => 'nullable',
            'clabe' => 'nullable',
            'price' => 'nullable',
            'currency' => 'nullable',
        ];
    }
}
