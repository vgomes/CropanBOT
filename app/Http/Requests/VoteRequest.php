<?php

namespace Cropan\Http\Requests;

use Cropan\Http\Requests\Request;

class VoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
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
            'vote' => 'required|boolean'
        ];
    }
}
