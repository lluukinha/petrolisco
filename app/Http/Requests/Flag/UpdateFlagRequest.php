<?php

namespace App\Http\Requests\Flag;

class UpdateFlagRequest extends CreateFlagRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'img' => 'nullable|string'
        ];
    }
}
