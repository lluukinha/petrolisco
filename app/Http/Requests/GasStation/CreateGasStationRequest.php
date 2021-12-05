<?php

namespace App\Http\Requests\GasStation;

use App\Http\Requests\JSONRequest;

class CreateGasStationRequest extends JSONRequest
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
            'address' => 'required|string',
            'flag_id' => 'required|string',
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
            'address.required' => 'address-required',
            'address.string' => 'address-must-be-string',
            'flag_id.required' => 'flag_id-required',
            'flag_id.string' => 'flag_id-must-be-string',
        ];
    }
}
