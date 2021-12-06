<?php

namespace App\Http\Requests\GasStation;

use App\Http\Requests\JSONRequest;

class AssignFuelTypesToGasStationRequest extends JSONRequest
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
            'fuel_type_ids' => 'required|array',
            'fuel_type_ids.*' => 'required|int',
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
            'fuel_type_ids.required' => 'fuel-type-ids-required',
            'fuel_type_ids.array' => 'fuel-type-ids-must-be-array',
            'fuel_type_ids.*.required' => 'fuel-type-id-is-required',
            'fuel_type_ids.*.int' => 'fuel-type-id-must-be-int',
        ];
    }
}
