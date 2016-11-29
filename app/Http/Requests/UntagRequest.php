<?php

namespace Cropan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UntagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (\Auth::check());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'picture_id' => 'required|integer|exists:pictures,id',
            'person_id'  => 'required|integer|exists:people,id'
        ];
    }
}
