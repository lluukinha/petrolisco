<?php

namespace App\Http\Requests\GasStation;

class UpdateGasStationRequest extends CreateGasStationRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            'flag_id' => 'nullable|string',
            'fuel_type_ids' => 'nullable|array',
            'fuel_type_ids.*' => 'required|int',
        ];
    }
}
