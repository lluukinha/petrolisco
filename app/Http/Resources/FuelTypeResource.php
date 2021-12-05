<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FuelTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'fuel-type',
            'id' => (string) $this->id,
            'name' => $this->name,
        ];
    }
}
