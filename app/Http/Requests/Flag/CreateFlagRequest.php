<?php

namespace App\Http\Requests\Flag;

use App\Http\Requests\JSONRequest;

class CreateFlagRequest extends JSONRequest
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
            'name' => 'required|string',
            'img' => 'nullable|string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'name-required',
            'name.string' => 'name-must-be-string',
            'img.string' => 'img-must-be-string',
        ];
    }
}
