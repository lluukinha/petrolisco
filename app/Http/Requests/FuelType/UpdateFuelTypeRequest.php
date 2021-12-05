<?php

namespace App\Http\Requests\FuelType;

class UpdateFuelTypeRequest extends CreateFuelTypeRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        ];
    }
}
