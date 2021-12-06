<?php

namespace App\Http\Requests\GasStationPrice;

use App\Http\Requests\JSONRequest;

class CreateGasStationPriceRequest extends JSONRequest
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
            'prices' => 'required|array',
            'prices.*.fuel_type_id' => 'required|int',
            'prices.*.price' => 'required|numeric|between:0,99.999',
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
            'prices.required' => 'prices-is-required',
            'prices.array' => 'prices-must-be-array',
            'prices.*.fuel_type_id.required' => 'fuel-type-id-is-required',
            'prices.*.fuel_type_id.int' => 'fuel-type-id-must-be-int',
            'prices.*.price.required' => 'price-is-required',
            'prices.*.price.numeric' => 'price-should-be-numeric',
        ];
    }
}
